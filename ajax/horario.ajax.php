<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador"));
require_once "../MODELO/horario.modelo.php";
require_once "../MODELO/docgrado.modelo.php";
require_once "../CONTROLADOR/horario.controlador.php";

/*=============================================
HORARIO POR CURSO: LISTAR, GUARDAR (CREAR / MOVER / CAMBIAR) Y BORRAR BLOQUES
=============================================*/
header("Content-Type: application/json; charset=utf-8");
$accion = isset($_POST["accion"]) ? $_POST["accion"] : "";
$curso = isset($_POST["curso"]) ? (int)$_POST["curso"] : 0;

function responder($datos){
  echo json_encode($datos, JSON_UNESCAPED_UNICODE);
  exit;
}

if($accion == "eventos"){
  responder(array_map(array("ControladorHorario","eventoCalendario"), ModeloHorario::mdlHorarioCurso($curso)));
}

if($accion == "guardar"){
  list($ok, $mensaje, $id) = ControladorHorario::guardarBloque(
    $curso,
    isset($_POST["id"]) ? (int)$_POST["id"] : 0,
    isset($_POST["clase"]) ? (int)$_POST["clase"] : 0,
    isset($_POST["dia"]) ? $_POST["dia"] : 0,
    isset($_POST["inicio"]) ? $_POST["inicio"] : "",
    isset($_POST["fin"]) ? $_POST["fin"] : ""
  );
  if(!$ok){
    responder(array("ok"=>false, "mensaje"=>$mensaje));
  }
  responder(array("ok"=>true, "evento"=>ControladorHorario::eventoCalendario(ModeloHorario::mdlBloque($id))));
}

if($accion == "borrar"){
  $bloque = ModeloHorario::mdlBloque(isset($_POST["id"]) ? (int)$_POST["id"] : 0);
  if(!$bloque || $bloque["id_grado_docente"] != $curso){
    responder(array("ok"=>false, "mensaje"=>"El bloque no pertenece a este curso."));
  }
  $ok = ModeloHorario::mdlEliminar($bloque["id"]) == "ok";
  responder(array("ok"=>$ok, "mensaje"=>$ok ? "" : Conexion::$ultimoError));
}

responder(array("ok"=>false, "mensaje"=>"Acción no válida."));
