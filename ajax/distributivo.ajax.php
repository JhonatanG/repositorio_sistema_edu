<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador"));
require_once "../MODELO/docgrado.modelo.php";

/*=============================================
DISTRIBUTIVO EN CASCADA: NIVEL -> PARALELOS -> MATERIAS DEL NIVEL
=============================================*/
header("Content-Type: application/json; charset=utf-8");
$accion = isset($_POST["accion"]) ? $_POST["accion"] : "";

/* Paralelos del nivel en el año lectivo activo */
if($accion == "paralelos" && isset($_POST["idGrado"])){
  echo json_encode(ModeloDocg::mdlParalelosNivel($_POST["idGrado"]));
  exit;
}

/* Materias del nivel; por cada paralelo elegido indica si ya tiene docente */
if($accion == "materias" && isset($_POST["idGrado"])){
  $paralelos = isset($_POST["paralelos"]) ? (array)$_POST["paralelos"] : array();
  $materias = ModeloDocg::mdlMateriasNivel($_POST["idGrado"]);
  foreach($materias as $i=>$m){
    $asignadas = array();
    foreach($paralelos as $gp){
      $clase = ModeloDocg::mdlClaseAsignada($gp, $m["id"]);
      if($clase){
        $asignadas[] = array("gp"=>(int)$gp, "docente"=>$clase["docente"], "idDocente"=>(int)$clase["id_docente"]);
      }
    }
    $materias[$i]["asignadas"] = $asignadas;
  }
  echo json_encode($materias);
  exit;
}

echo json_encode(array());
