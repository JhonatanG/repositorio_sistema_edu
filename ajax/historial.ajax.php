<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador","Secretaría"));
require_once "../MODELO/matricula.modelo.php";
require_once "../CONTROLADOR/calificaciones.php";
require_once "../CONTROLADOR/historial.controlador.php";

/*=============================================
MATRÍCULA: HISTORIAL DEL ESTUDIANTE, NIVEL QUE LE CORRESPONDE Y CURSOS DEL AÑO
=============================================*/
header("Content-Type: application/json; charset=utf-8");
$idEstudiante = isset($_POST["estudiante"]) ? (int)$_POST["estudiante"] : 0;
$idPeriodo = isset($_POST["periodo"]) ? (int)$_POST["periodo"] : 0;
if(!$idEstudiante || !$idPeriodo){
  echo json_encode(array("ok"=>false));
  exit;
}

$historial = ControladorHistorial::historial($idEstudiante);
$yaMatriculado = null;
foreach($historial as $h){
  if($h["id_periodo"] == $idPeriodo){
    $yaMatriculado = $h["grado"]." ".$h["paralelo"];
  }
}
$rec = ControladorHistorial::recomendacion($idEstudiante, $idPeriodo);
$cursos = array();
foreach(ModeloMatricula::mdlCursosPeriodo($idPeriodo) as $c){
  $c["corresponde"] = $rec["libre"] || $rec["idGrado"] == $c["id_grado"];
  $cursos[] = $c;
}
echo json_encode(array(
  "ok"=>true,
  "historial"=>ControladorHistorial::htmlHistorial($historial, false),
  "recomendacion"=>$rec,
  "yaMatriculado"=>$yaMatriculado,
  "cursos"=>$cursos
), JSON_UNESCAPED_UNICODE);
