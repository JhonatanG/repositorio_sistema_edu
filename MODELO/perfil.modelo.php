<?php
require_once "conexion.php";

class ModeloPerfil{
/* Las tablas y columnas vienen de ControladorPerfil::config() (lista fija), nunca del formulario */

static public function mdlObtener($tabla, $id){
  $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = :id");
  $stmt->bindParam(":id",$id,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

static public function mdlActualizarFoto($tabla, $id, $ruta){
  $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET foto = :foto WHERE id = :id");
  $stmt->bindParam(":foto",$ruta,PDO::PARAM_STR);
  $stmt->bindParam(":id",$id,PDO::PARAM_INT);
  return Conexion::ejecutar($stmt) ? "ok" : "error";
}

/* $datos: columna => valor (solo columnas permitidas por el rol) */
static public function mdlActualizarDatos($tabla, $id, $datos){
  $sets = array();
  foreach($datos as $columna=>$valor){
    $sets[] = "$columna = :$columna";
  }
  $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET ".implode(", ", $sets)." WHERE id = :id");
  foreach($datos as $columna=>$valor){
    $stmt->bindValue(":$columna", $valor, PDO::PARAM_STR);
  }
  $stmt->bindValue(":id", $id, PDO::PARAM_INT);
  return Conexion::ejecutar($stmt) ? "ok" : "error";
}

/* Curso actual del estudiante (año lectivo activo) */
static public function mdlCursoEstudiante($idEstudiante){
  $stmt = Conexion::conectar()->prepare("SELECT CONCAT(etg.nombre,' ',etp.nombre,' (',etpe.nombre,')')
  FROM edu_tab_matricula etm
  INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etm.id_grado_paralelo
  INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
  INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
  INNER JOIN edu_tab_periodo etpe ON etpe.id = etm.id_periodo AND etpe.estado = '1'
  WHERE etm.id_estudiante = :id ORDER BY etpe.id DESC LIMIT 1");
  $stmt->bindParam(":id",$idEstudiante,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchColumn();
}
}
