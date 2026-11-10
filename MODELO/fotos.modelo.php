<?php
require_once "conexion.php";

/*========================================================================
FOTOS GUARDADAS EN LA BASE DE DATOS (EL DISCO DEL HOSTING SE BORRA EN CADA DEPLOY)
Tipo: U usuario del sistema, D docente, R representante, E estudiante
==========================================================================*/
class ModeloFotos{

static public function tablas(){
  return array("U"=>"edu_tab_usuarios", "D"=>"edu_tab_docentes", "R"=>"edu_tab_representantes", "E"=>"edu_tab_estudiantes");
}

static public function mdlObtener($tipo, $id){
  $stmt = Conexion::conectar()->prepare("SELECT datos, mime FROM edu_tab_fotos WHERE tipo = :tipo AND id_persona = :id");
  $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
  $stmt->bindParam(":id", $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

static public function mdlGuardar($tipo, $id, $datos, $mime){
  $stmt = Conexion::conectar()->prepare("INSERT INTO edu_tab_fotos (tipo, id_persona, datos, mime) VALUES (:tipo, :id, :datos, :mime)
    ON DUPLICATE KEY UPDATE datos = VALUES(datos), mime = VALUES(mime), actualizado = CURRENT_TIMESTAMP");
  $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
  $stmt->bindParam(":id", $id, PDO::PARAM_INT);
  $stmt->bindParam(":datos", $datos, PDO::PARAM_LOB);
  $stmt->bindParam(":mime", $mime, PDO::PARAM_STR);
  return Conexion::ejecutar($stmt) ? "ok" : "error";
}

static public function mdlBorrar($tipo, $id){
  $stmt = Conexion::conectar()->prepare("DELETE FROM edu_tab_fotos WHERE tipo = :tipo AND id_persona = :id");
  $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
  $stmt->bindParam(":id", $id, PDO::PARAM_INT);
  return Conexion::ejecutar($stmt) ? "ok" : "error";
}

/* Id del registro recién creado, buscado por su usuario (es único) */
static public function mdlIdPorUsuario($tipo, $usuario){
  $tablas = self::tablas();
  $stmt = Conexion::conectar()->prepare("SELECT id FROM ".$tablas[$tipo]." WHERE usuario = :usuario");
  $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchColumn();
}

/* La columna foto guarda la dirección de la foto (mostrar_foto.php?...) o "" si no tiene */
static public function mdlActualizarRuta($tipo, $id, $ruta){
  $tablas = self::tablas();
  $stmt = Conexion::conectar()->prepare("UPDATE ".$tablas[$tipo]." SET foto = :foto WHERE id = :id");
  $stmt->bindParam(":foto", $ruta, PDO::PARAM_STR);
  $stmt->bindParam(":id", $id, PDO::PARAM_INT);
  return Conexion::ejecutar($stmt) ? "ok" : "error";
}
}
