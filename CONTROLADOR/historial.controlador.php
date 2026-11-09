<?php
/*========================================================================
HISTORIAL ACADÉMICO Y PROMOCIÓN DE NIVEL
- Un año se APRUEBA cuando todas sus asignaturas tienen nota final >= 7.
- Si aprobó, la nueva matrícula corresponde al nivel siguiente (según el orden de los grados).
- Si reprobó, repite el mismo nivel.
- Sin historial (primer ingreso), puede matricularse en cualquier nivel.
- Cualquier otro nivel exige escribir el motivo (traslado, pase de año, etc.).
==========================================================================*/
class ControladorHistorial{

const NOTA_MINIMA = 7;

static public function estados(){
  return array(
    "APROBADO"   => array("Aprobado", "success", "fa-check-circle"),
    "REPROBADO"  => array("Reprobado", "danger", "fa-times-circle"),
    "EN_CURSO"   => array("En curso", "info", "fa-hourglass-half"),
    "INCOMPLETO" => array("Notas incompletas", "warning", "fa-exclamation-triangle"),
    "SIN_MATERIAS" => array("Sin asignaturas", "secondary", "fa-question-circle")
  );
}

/*=============================================
ESTADO DE UNA MATRÍCULA SEGÚN LAS NOTAS FINALES
=============================================*/
static public function estado($matricula, $idEstudiante){
  $notas = ModeloMatricula::mdlNotasFinalesCurso($idEstudiante, $matricula["id_grado_paralelo"]);
  $conNota = array_filter($notas, function($n){ return $n["nota_final"] !== null; });
  $reprobadas = array_filter($conNota, function($n){ return $n["nota_final"] < ControladorHistorial::NOTA_MINIMA; });
  $promedio = count($conNota) ? round(array_sum(array_column($conNota, "nota_final")) / count($conNota), 2) : null;
  if(empty($notas)){
    $estado = "SIN_MATERIAS";
  }else if(count($conNota) == count($notas)){
    $estado = empty($reprobadas) ? "APROBADO" : "REPROBADO";
  }else{
    $estado = $matricula["periodo_activo"] == 1 ? "EN_CURSO" : "INCOMPLETO";
  }
  return array("estado"=>$estado, "promedio"=>$promedio, "notas"=>$notas,
    "reprobadas"=>array_column($reprobadas, "materia"), "pendientes"=>count($notas) - count($conNota));
}

static public function historial($idEstudiante){
  $lista = array();
  foreach(ModeloMatricula::mdlMatriculasEstudiante($idEstudiante) as $m){
    $lista[] = array_merge($m, self::estado($m, $idEstudiante));
  }
  return $lista;
}

/*=============================================
NIVEL QUE LE CORRESPONDE PARA UN AÑO LECTIVO
Devuelve: tipo, idGrado (null = cualquiera si libre=true / ninguno si libre=false), mensaje
=============================================*/
static public function recomendacion($idEstudiante, $idPeriodo){
  $anteriores = array_values(array_filter(self::historial($idEstudiante), function($m) use ($idPeriodo){
    return $m["id_periodo"] != $idPeriodo;
  }));
  if(empty($anteriores)){
    return array("tipo"=>"NUEVO", "idGrado"=>null, "libre"=>true, "nivel"=>"",
      "mensaje"=>"Primer ingreso: no tiene matrículas anteriores, puede matricularse en cualquier nivel.");
  }
  $ultima = $anteriores[0];
  $origen = $ultima["grado"]." (".$ultima["periodo"].")";
  if($ultima["estado"] == "REPROBADO"){
    return array("tipo"=>"REPITE", "idGrado"=>$ultima["id_grado"], "libre"=>false, "nivel"=>$ultima["grado"],
      "mensaje"=>"Reprobó ".$origen." en: ".implode(", ", $ultima["reprobadas"]).". Debe repetir ".$ultima["grado"].".");
  }
  if($ultima["estado"] == "APROBADO"){
    if($ultima["orden"] === null){
      return array("tipo"=>"SIN_ORDEN", "idGrado"=>null, "libre"=>true, "nivel"=>"",
        "mensaje"=>"Aprobó ".$origen.", pero el nivel no tiene orden configurado en Parámetros → Grados; no se puede saber cuál sigue.");
    }
    $siguiente = ModeloMatricula::mdlGradoSiguiente($ultima["orden"]);
    if(!$siguiente){
      return array("tipo"=>"CULMINO", "idGrado"=>null, "libre"=>false, "nivel"=>"",
        "mensaje"=>"Aprobó ".$origen.", que es el último nivel de la institución.");
    }
    return array("tipo"=>"PROMOVIDO", "idGrado"=>$siguiente["id"], "libre"=>false, "nivel"=>$siguiente["nombre"],
      "mensaje"=>"Aprobó ".$origen." con promedio ".number_format($ultima["promedio"], 2).". Le corresponde ".$siguiente["nombre"].".");
  }
  $e = self::estados()[$ultima["estado"]][0];
  return array("tipo"=>$ultima["estado"], "idGrado"=>null, "libre"=>false, "nivel"=>"",
    "mensaje"=>$origen.": ".strtolower($e).($ultima["pendientes"] ? " (".$ultima["pendientes"]." asignatura(s) sin nota final)" : "").". No se puede determinar si aprobó.");
}

/*=============================================
REGISTRAR MATRÍCULA (VALIDA EL NIVEL EN EL SERVIDOR)
=============================================*/
static public function ctrRegistrarMatricula(){
  if(!isset($_POST["matriculaEstudiante"])){
    return;
  }
  if(!Seguridad::tokenValido()){
    echo Seguridad::alerta("error", "¡Sesión caducada!", "Recargue la página e intente de nuevo.", "matricula/");
    return;
  }
  $idEstudiante = (int)$_POST["matriculaEstudiante"];
  $idPeriodo = (int)$_POST["matriculaPeriodo"];
  $idCurso = isset($_POST["matriculaCurso"]) ? (int)$_POST["matriculaCurso"] : 0;
  $motivo = trim(isset($_POST["motivoCambioNivel"]) ? $_POST["motivoCambioNivel"] : "");

  $curso = null;
  foreach(ModeloMatricula::mdlCursosPeriodo($idPeriodo) as $c){
    if($c["id"] == $idCurso){
      $curso = $c;
    }
  }
  if(!$idEstudiante || !$curso){
    echo Seguridad::alerta("error", "¡Faltan datos!", "Elija el estudiante, el año lectivo y el curso.", "matricula/");
    return;
  }
  if(!empty(ModeloMatricula::mdlMostrarRepetidosM("edu_tab_matricula", array("idPeriodo"=>$idPeriodo, "idEstudiante"=>$idEstudiante)))){
    echo Seguridad::alerta("error", "¡Ya está matriculado!", "El estudiante ya tiene matrícula en ese año lectivo.", "matricula/");
    return;
  }
  if($curso["cupos"] <= 0){
    echo Seguridad::alerta("error", "¡Sin cupos!", $curso["grado"]." ".$curso["paralelo"]." no tiene cupos disponibles.", "matricula/");
    return;
  }
  /*EL NIVEL DEBE SER EL QUE CORRESPONDE, SALVO QUE SE ESCRIBA EL MOTIVO DEL CAMBIO*/
  $rec = self::recomendacion($idEstudiante, $idPeriodo);
  $corresponde = $rec["libre"] || $rec["idGrado"] == $curso["id_grado"];
  if(!$corresponde && strlen($motivo) < 5){
    echo Seguridad::alerta("error", "¡Nivel no corresponde!", $rec["mensaje"]." Si es un caso especial, marque «Cambiar de nivel» y escriba el motivo.", "matricula/");
    return;
  }
  $observacion = trim($_POST["matriculaObservacion"]);
  if(!$corresponde){
    $observacion = trim("Cambio de nivel: ".$motivo.". ".$observacion);
  }
  $respuesta = ModeloMatricula::mdlCrearMatricula("edu_tab_matricula", array(
    "codigo"=>ModeloMatricula::mdlSiguienteCodigo(),
    "idEstudiante"=>$idEstudiante,
    "observacion"=>$observacion,
    "idGparalelo"=>$curso["id"],
    "idPeriodo"=>$idPeriodo,
    "idUsuario"=>$_SESSION["id"]
  ));
  if($respuesta == "ok"){
    ModeloMatricula::mdlDescontarCupo($curso["id"]);
    echo Seguridad::alerta("success", "¡Matrícula registrada!", "Matriculado en ".$curso["grado"]." ".$curso["paralelo"].".", "listaMatricula/");
  }else{
    echo Seguridad::alerta("error", "¡No se pudo matricular!", Conexion::$ultimoError, "matricula/");
  }
}

/*=============================================
LÍNEA DE TIEMPO DEL HISTORIAL (HTML)
=============================================*/
static public function htmlHistorial($historial, $conNotas = true){
  if(empty($historial)){
    return '<div class="text-muted"><i class="fas fa-user-plus"></i> Sin matrículas anteriores (primer ingreso).</div>';
  }
  $estados = self::estados();
  $html = '<div class="linea-tiempo">';
  foreach($historial as $h){
    $e = $estados[$h["estado"]];
    $html .= '<div class="hito"><span class="punto bg-'.$e[1].'"></span>
      <div class="d-flex justify-content-between flex-wrap">
        <div><b>'.htmlspecialchars($h["periodo"]).'</b> · '.htmlspecialchars($h["grado"]." ".$h["paralelo"]).'</div>
        <div><span class="badge badge-'.$e[1].'"><i class="fas '.$e[2].'"></i> '.$e[0].'</span>'.
        ($h["promedio"] !== null ? ' <span class="badge badge-light">Promedio '.number_format($h["promedio"], 2).'</span>' : '').'</div>
      </div>';
    if(!empty($h["reprobadas"])){
      $html .= '<small class="text-danger">Reprobó: '.htmlspecialchars(implode(", ", $h["reprobadas"])).'</small>';
    }
    if($conNotas && !empty($h["notas"])){
      $html .= '<div class="notas-hito">';
      foreach($h["notas"] as $n){
        $html .= '<span class="nota-hito">'.htmlspecialchars($n["materia"]).': '.Calificaciones::insignia($n["nota_final"]).'</span>';
      }
      $html .= '</div>';
    }
    if($h["observacion"] != ""){
      $html .= '<small class="text-muted d-block">'.htmlspecialchars($h["observacion"]).'</small>';
    }
    $html .= '</div>';
  }
  return $html.'</div>';
}
}
