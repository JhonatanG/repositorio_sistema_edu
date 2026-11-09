<?php

class ControladorNotas{
/*=============================================
LISTAR ESTUDIANTES DE LA CLASE CON SUS NOTAS
=============================================*/
   static public function ctrListaNotas(){
    if(isset($_POST["idGrado"]) && isset($_POST["idMateria"])){
       $datos=array(
         "idGParalelo"=>$_POST["idGrado"],
         "idDocente"=>$_POST["idDocenteG"],
         "idMateria"=>$_POST["idMateria"]
       );
       return ModeloNotas::mdlListaNotas($datos);
    }
   }

/*=============================================
TIPO DE EVALUACIÓN DEL GRADO SELECCIONADO
=============================================*/
   static public function ctrTipoGrado(){
    if(isset($_POST["idGrado"]) && $_POST["idGrado"]!=""){
      return ModeloNotas::mdlTipoGradoParalelo($_POST["idGrado"]);
    }
    return ModeloNotas::mdlTipoPeriodoActivo();
   }

/*=============================================
GUARDAR NOTAS (CREA LAS NUEVAS Y ACTUALIZA LAS EXISTENTES)
=============================================*/
static public function ctrGuardarNotas(){
  if(!isset($_POST["idClaseNotas"])){
    return;
  }
  $idClase = $_POST["idClaseNotas"];
  if(!Seguridad::tokenValido()){
    echo Seguridad::alerta("error", "¡Sesión caducada!", "Recargue la página e intente de nuevo.", "notas/");
    return;
  }
  if(!Seguridad::esClaseDelDocente($idClase)){
    echo Seguridad::alerta("error", "¡No autorizado!", "Solo puede registrar datos de sus propias clases.", "notas/");
    return;
  }
  $estudiantes = isset($_POST["idEstudiante"]) ? (array)$_POST["idEstudiante"] : array();
  if(empty($estudiantes)){
    echo Seguridad::alerta("error", "¡Debe seleccionar los campos!", "No hay estudiantes para calificar.", "notas/");
    return;
  }

  /*EL NÚMERO DE PERIODOS LO DEFINE EL AÑO LECTIVO DE LA CLASE, NO EL FORMULARIO*/
  $cfg = Calificaciones::config(ModeloNotas::mdlTipoClase($idClase));
  $periodos = $cfg["periodos"];

  /*VALIDAR TODO ANTES DE GUARDAR: NOTAS ENTRE 0 Y 10*/
  $listas = array();
  for($k=1; $k<=$periodos; $k++){
    foreach(Calificaciones::campos() as $c){
      $clave = $c[0]."_".$k;
      if(!isset($_POST[$clave]) || count((array)$_POST[$clave]) != count($estudiantes)){
        echo Seguridad::alerta("error", "¡Faltan notas!", "Complete las notas de todos los ".strtolower($cfg["nombre"])."s.", "notas/");
        return;
      }
      $listas[] = $_POST[$clave];
    }
  }
  if(!Seguridad::notasValidas($listas)){
    echo Seguridad::alerta("error", "¡Notas no válidas!", "Todas las notas deben ser números entre 0 y 10. No se guardó ningún cambio.", "notas/");
    return;
  }

  $errores = 0;
  foreach($estudiantes as $i=>$idEstudiante){
    $notas = array();
    for($k=1; $k<=$periodos; $k++){
      foreach(Calificaciones::campos() as $c){
        $notas[$k][$c[0]] = $_POST[$c[0]."_".$k][$i];
      }
    }
    /*LOS PROMEDIOS SE CALCULAN EN EL SERVIDOR*/
    $resultado = Calificaciones::calcular($notas, $periodos);
    $respuesta = ModeloNotas::mdlGuardarNota(array(
      "idClase"=>$idClase,
      "idEstudiante"=>$idEstudiante,
      "notas"=>$notas,
      "promedios"=>$resultado["promedios"],
      "final"=>$resultado["final"],
      "periodos"=>$periodos
    ));
    if($respuesta!="ok"){
      $errores++;
    }
  }

  if($errores==0){
    echo Seguridad::alerta("success", "¡Se ha guardado correctamente!", "Las notas y promedios quedaron registrados.", "notas/");
  }else{
    echo Seguridad::alerta("error", "¡No se guardaron todas las notas!", $errores." estudiante(s) no se pudieron guardar. ".Conexion::$ultimoError, "notas/");
  }
}

/*=============================================
REPORTE NOTAS PARA CADA ESTUDIANTE
=============================================*/
  static public function ctrReportNotEstud($valor){
    return ModeloNotas::mdlReportNotasEstud($valor);
  }
}
