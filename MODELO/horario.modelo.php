<?php
require_once "conexion.php";

class ModeloHorario{

/* Datos de una clase con su curso y asignatura (para mostrar en el horario) */
static private function selectClase(){
  return "SELECT eth.id, eth.id_clase, eth.dia, TIME_FORMAT(eth.hora_inicio,'%H:%i') AS inicio, TIME_FORMAT(eth.hora_fin,'%H:%i') AS fin,
    CONCAT(etg.nombre,' ',etp.nombre) AS curso, etm.nombre AS materia, CONCAT(etd.nombres,' ',etd.apellidos) AS docente,
    etgd.id_docente, etgd.id_grado_docente, etgd.id_materia
  FROM edu_tab_horario eth
  INNER JOIN edu_tab_grad_docente etgd ON etgd.id = eth.id_clase
  INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etgd.id_grado_docente
  INNER JOIN edu_tab_periodo etpe ON etpe.id = etgp.id_periodo AND etpe.estado = '1'
  INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
  INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
  INNER JOIN edu_tab_materias etm ON etm.id = etgd.id_materia
  INNER JOIN edu_tab_docentes etd ON etd.id = etgd.id_docente";
}

/*=============================================
CLASES DEL DOCENTE EN EL AÑO LECTIVO ACTIVO
=============================================*/
static public function mdlClasesDocente($idDocente){
  $stmt = Conexion::conectar()->prepare("SELECT etgd.id, CONCAT(etg.nombre,' ',etp.nombre) AS curso, etm.nombre AS materia
  FROM edu_tab_grad_docente etgd
  INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etgd.id_grado_docente
  INNER JOIN edu_tab_periodo etpe ON etpe.id = etgp.id_periodo AND etpe.estado = '1'
  INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
  INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
  INNER JOIN edu_tab_materias etm ON etm.id = etgd.id_materia
  WHERE etgd.id_docente = :id AND etgd.estado = '1'
  ORDER BY etg.nombre, etp.nombre, etm.nombre");
  $stmt->bindParam(":id",$idDocente,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*=============================================
HORARIO COMPLETO DEL DOCENTE
=============================================*/
static public function mdlHorarioDocente($idDocente){
  $stmt = Conexion::conectar()->prepare(self::selectClase()." WHERE etgd.id_docente = :id ORDER BY eth.dia, eth.hora_inicio");
  $stmt->bindParam(":id",$idDocente,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* Bloque por id */
static public function mdlBloque($id){
  $stmt = Conexion::conectar()->prepare(self::selectClase()." WHERE eth.id = :id");
  $stmt->bindParam(":id",$id,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

/*=============================================
CHOQUES: MISMO DÍA Y HORAS QUE SE CRUZAN, PARA EL DOCENTE O PARA EL CURSO
=============================================*/
static public function mdlChoques($idDocente, $idGradoParalelo, $dia, $inicio, $fin, $excluir = 0){
  $stmt = Conexion::conectar()->prepare(self::selectClase()."
  WHERE eth.dia = :dia AND eth.hora_inicio < :fin AND eth.hora_fin > :inicio
    AND (etgd.id_docente = :docente OR etgd.id_grado_docente = :gp) AND eth.id <> :excluir");
  $stmt->bindParam(":excluir",$excluir,PDO::PARAM_INT);
  $stmt->bindParam(":dia",$dia,PDO::PARAM_INT);
  $stmt->bindParam(":inicio",$inicio,PDO::PARAM_STR);
  $stmt->bindParam(":fin",$fin,PDO::PARAM_STR);
  $stmt->bindParam(":docente",$idDocente,PDO::PARAM_INT);
  $stmt->bindParam(":gp",$idGradoParalelo,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*=============================================
GUARDAR Y BORRAR BLOQUES
=============================================*/
static public function mdlCrear($datos){
  $link = Conexion::conectar();
  $stmt = $link->prepare("INSERT INTO edu_tab_horario (id_clase, dia, hora_inicio, hora_fin) VALUES (:clase, :dia, :inicio, :fin)");
  $stmt->bindParam(":clase",$datos["clase"],PDO::PARAM_INT);
  $stmt->bindParam(":dia",$datos["dia"],PDO::PARAM_INT);
  $stmt->bindParam(":inicio",$datos["inicio"],PDO::PARAM_STR);
  $stmt->bindParam(":fin",$datos["fin"],PDO::PARAM_STR);
  /*DEVUELVE EL ID DEL BLOQUE CREADO (0 SI FALLA)*/
  return Conexion::ejecutar($stmt) ? (int)$link->lastInsertId() : 0;
}

static public function mdlEliminar($id){
  $stmt = Conexion::conectar()->prepare("DELETE FROM edu_tab_horario WHERE id = :id");
  $stmt->bindParam(":id",$id,PDO::PARAM_INT);
  return Conexion::ejecutar($stmt) ? "ok" : "error";
}

/*=============================================
DÍAS EN QUE SE DICTA UNA CLASE (PARA TOMAR ASISTENCIA)
=============================================*/
static public function mdlDiasClase($idClase){
  $stmt = Conexion::conectar()->prepare("SELECT DISTINCT dia FROM edu_tab_horario WHERE id_clase = :id ORDER BY dia");
  $stmt->bindParam(":id",$idClase,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/* Id de la clase a partir del curso, docente y materia elegidos en los filtros */
static public function mdlIdClase($idGradoParalelo, $idDocente, $idMateria){
  $stmt = Conexion::conectar()->prepare("SELECT id FROM edu_tab_grad_docente
  WHERE id_grado_docente = :gp AND id_docente = :d AND id_materia = :m LIMIT 1");
  $stmt->bindParam(":gp",$idGradoParalelo,PDO::PARAM_INT);
  $stmt->bindParam(":d",$idDocente,PDO::PARAM_INT);
  $stmt->bindParam(":m",$idMateria,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchColumn();
}

/* Clases que el docente tiene en un día */
static public function mdlClasesDelDia($idDocente, $dia){
  $stmt = Conexion::conectar()->prepare(self::selectClase()." WHERE etgd.id_docente = :id AND eth.dia = :dia ORDER BY eth.hora_inicio");
  $stmt->bindParam(":id",$idDocente,PDO::PARAM_INT);
  $stmt->bindParam(":dia",$dia,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*=============================================
HORARIO POR CURSO (NIVEL + PARALELO)
=============================================*/
/* Cursos del año lectivo activo */
static public function mdlCursosActivos(){
  return Conexion::conectar()->query("SELECT etgp.id, CONCAT(etg.nombre,' ',etp.nombre) AS curso
  FROM edu_tab_grado_paralelo etgp
  INNER JOIN edu_tab_periodo etpe ON etpe.id = etgp.id_periodo AND etpe.estado = '1'
  INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
  INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
  ORDER BY etg.nombre, etp.nombre")->fetchAll(PDO::FETCH_ASSOC);
}

/* Clases (asignatura + docente) del curso */
static public function mdlClasesCurso($idGradoParalelo){
  $stmt = Conexion::conectar()->prepare("SELECT etgd.id, etgd.id_materia, etm.nombre AS materia, CONCAT(etd.nombres,' ',etd.apellidos) AS docente
  FROM edu_tab_grad_docente etgd
  INNER JOIN edu_tab_materias etm ON etm.id = etgd.id_materia
  INNER JOIN edu_tab_docentes etd ON etd.id = etgd.id_docente
  WHERE etgd.id_grado_docente = :gp AND etgd.estado = '1'
  ORDER BY etm.nombre");
  $stmt->bindParam(":gp",$idGradoParalelo,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* Bloques del horario del curso */
static public function mdlHorarioCurso($idGradoParalelo){
  $stmt = Conexion::conectar()->prepare(self::selectClase()." WHERE etgd.id_grado_docente = :gp ORDER BY eth.dia, eth.hora_inicio");
  $stmt->bindParam(":gp",$idGradoParalelo,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* Mover, cambiar duración o cambiar la clase de un bloque */
static public function mdlActualizar($id, $datos){
  $stmt = Conexion::conectar()->prepare("UPDATE edu_tab_horario SET id_clase = :clase, dia = :dia, hora_inicio = :inicio, hora_fin = :fin WHERE id = :id");
  $stmt->bindParam(":clase",$datos["clase"],PDO::PARAM_INT);
  $stmt->bindParam(":dia",$datos["dia"],PDO::PARAM_INT);
  $stmt->bindParam(":inicio",$datos["inicio"],PDO::PARAM_STR);
  $stmt->bindParam(":fin",$datos["fin"],PDO::PARAM_STR);
  $stmt->bindParam(":id",$id,PDO::PARAM_INT);
  return Conexion::ejecutar($stmt) ? "ok" : "error";
}
}
