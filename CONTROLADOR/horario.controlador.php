<?php
/*========================================================================
HORARIO DE LOS DOCENTES
La asistencia solo se puede tomar el día en que la clase está en el horario.
==========================================================================*/
class ControladorHorario{

static public function dias(){
  return array(1=>"Lunes", 2=>"Martes", 3=>"Miércoles", 4=>"Jueves", 5=>"Viernes", 6=>"Sábado", 7=>"Domingo");
}

static public function nombreDia($dia){
  $dias = self::dias();
  return isset($dias[$dia]) ? $dias[$dia] : "";
}

/* Día de hoy (1 = lunes ... 7 = domingo) en la hora de Ecuador */
static public function diaHoy(){
  date_default_timezone_set('America/Guayaquil');
  return (int)date("N");
}

/*=============================================
¿SE PUEDE TOMAR ASISTENCIA HOY DE ESTA CLASE?
=============================================*/
static public function puedeTomarAsistencia($idClase){
  $dias = ModeloHorario::mdlDiasClase($idClase);
  if(empty($dias)){
    return array(false, "Esta clase todavía no tiene horario. Pida al administrador que lo registre en Docente → Horarios.");
  }
  if(!in_array(self::diaHoy(), $dias)){
    $nombres = array_map(array("ControladorHorario","nombreDia"), $dias);
    return array(false, "Hoy es ".strtolower(self::nombreDia(self::diaHoy())).". Esta clase se dicta solo el/los día(s): ".strtolower(implode(", ", $nombres)).".");
  }
  return array(true, "");
}

/*=============================================
GUARDAR UN BLOQUE (NUEVO O EXISTENTE) DEL HORARIO DE UN CURSO
Valida horas, que la clase sea del curso y que no haya choques
con otras clases del docente ni del curso. Devuelve [ok, mensaje, id].
=============================================*/
static public function guardarBloque($idCurso, $idBloque, $idClase, $dia, $inicio, $fin){
  $dia = (int)$dia;
  if($dia < 1 || $dia > 7 || !preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $inicio) || !preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $fin)){
    return array(false, "Revise el día y las horas.", 0);
  }
  if($inicio >= $fin){
    return array(false, "La hora de fin debe ser mayor que la de inicio.", 0);
  }
  /*LA CLASE DEBE PERTENECER AL CURSO*/
  $clase = null;
  foreach(ModeloHorario::mdlClasesCurso($idCurso) as $c){
    if($c["id"] == $idClase){
      $clase = $c;
    }
  }
  if(!$clase){
    return array(false, "Elija una asignatura de este curso.", 0);
  }
  /*SI SE EDITA UN BLOQUE, DEBE SER DE ESTE CURSO*/
  if($idBloque){
    $bloque = ModeloHorario::mdlBloque($idBloque);
    if(!$bloque || $bloque["id_grado_docente"] != $idCurso){
      return array(false, "El bloque no pertenece a este curso.", 0);
    }
  }
  $datosClase = ModeloDocg::mdlMostrarDocg("edu_tab_grad_docente", "id", $idClase);
  $choques = ModeloHorario::mdlChoques($datosClase["id_docente"], $idCurso, $dia, $inicio, $fin, (int)$idBloque);
  if(!empty($choques)){
    $detalle = array();
    foreach($choques as $ch){
      if($ch["id_grado_docente"] == $idCurso){
        $detalle[] = "el curso ya tiene ".$ch["materia"]." de ".$ch["inicio"]." a ".$ch["fin"];
      }else{
        $detalle[] = $clase["docente"]." ya tiene ".$ch["materia"]." en ".$ch["curso"]." de ".$ch["inicio"]." a ".$ch["fin"];
      }
    }
    return array(false, self::nombreDia($dia).": ".implode("; ", $detalle).".", 0);
  }
  $datos = array("clase"=>$idClase, "dia"=>$dia, "inicio"=>$inicio, "fin"=>$fin);
  if($idBloque){
    $ok = ModeloHorario::mdlActualizar($idBloque, $datos) == "ok";
    return array($ok, $ok ? "" : Conexion::$ultimoError, (int)$idBloque);
  }
  $nuevo = ModeloHorario::mdlCrear($datos);
  return array($nuevo > 0, $nuevo > 0 ? "" : Conexion::$ultimoError, $nuevo);
}

/* Bloque con el formato que usa el calendario (semana de referencia: lunes 1 de enero de 2024) */
static public function eventoCalendario($b){
  $fecha = "2024-01-0".$b["dia"];
  return array(
    "id"=>(int)$b["id"],
    "title"=>$b["materia"],
    "start"=>$fecha."T".$b["inicio"],
    "end"=>$fecha."T".$b["fin"],
    "extendedProps"=>array("clase"=>(int)$b["id_clase"], "docente"=>$b["docente"], "materia"=>(int)$b["id_materia"])
  );
}

/*=============================================
DIBUJAR LA SEMANA (DE LUNES A VIERNES, Y SÁBADO/DOMINGO SI SE USAN)
=============================================*/
static public function semana($bloques, $conAcciones = false, $mostrarDocente = false){
  $porDia = array();
  $ultimoDia = 5;
  foreach($bloques as $b){
    $porDia[$b["dia"]][] = $b;
    $ultimoDia = max($ultimoDia, (int)$b["dia"]);
  }
  $hoy = self::diaHoy();
  echo '<div class="horario-semana" style="--dias: '.$ultimoDia.'">';
  for($d=1; $d<=$ultimoDia; $d++){
    echo '<div class="horario-dia'.($d==$hoy ? ' hoy' : '').'"><div class="horario-dia-titulo">'.self::nombreDia($d).($d==$hoy ? ' <small>(hoy)</small>' : '').'</div>';
    if(empty($porDia[$d])){
      echo '<div class="horario-vacio">Sin clases</div>';
    }else{
      foreach($porDia[$d] as $b){
        echo '<div class="horario-bloque">
          <div class="horario-hora"><i class="far fa-clock"></i> '.$b["inicio"].' - '.$b["fin"].'</div>
          <b>'.htmlspecialchars($b["materia"]).'</b>
          <div>'.htmlspecialchars($b["curso"]).'</div>'.
          ($mostrarDocente ? '<small class="text-muted">'.htmlspecialchars($b["docente"]).'</small>' : '').
          ($conAcciones ? '<button type="button" class="btn btn-xs btn-outline-danger btnBorrarBloque" data-id="'.$b["id"].'" title="Borrar"><i class="fas fa-trash"></i></button>' : '').
        '</div>';
      }
    }
    echo '</div>';
  }
  echo '</div>';
}
}
