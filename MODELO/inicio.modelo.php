<?php
require_once "conexion.php";
class ModeloInicio{
  /*=============================================
	TOTALES PARA EL TABLERO DE INICIO
	=============================================*/
  static public function mdlTotales(){
    $link = Conexion::conectar();
    $periodo = $link->query("SELECT id, nombre FROM edu_tab_periodo WHERE estado = '1' ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $totales = array(
      "periodo" => $periodo ? $periodo["nombre"] : "",
      "estudiantes" => (int)$link->query("SELECT COUNT(*) FROM edu_tab_estudiantes")->fetchColumn(),
      "docentes" => (int)$link->query("SELECT COUNT(*) FROM edu_tab_docentes")->fetchColumn(),
      "representantes" => (int)$link->query("SELECT COUNT(*) FROM edu_tab_representantes")->fetchColumn(),
      "matriculas" => 0
    );
    if($periodo){
      $stmt = $link->prepare("SELECT COUNT(*) FROM edu_tab_matricula WHERE id_periodo = :id");
      $stmt -> bindParam(":id", $periodo["id"], PDO::PARAM_INT);
      $stmt -> execute();
      $totales["matriculas"] = (int)$stmt -> fetchColumn();
    }
    return $totales;
  }
}
