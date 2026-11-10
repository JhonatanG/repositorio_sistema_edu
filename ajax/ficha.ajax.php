<?php
/*========================================================================
FICHA DE DOCENTES, ESTUDIANTES Y REPRESENTANTES (ADMINISTRADOR Y SECRETARÍA)
POST tipo = D | E | R, id = registro
==========================================================================*/
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador","Secretaría"));
require_once "../MODELO/conexion.php";

header("Content-Type: application/json; charset=utf-8");

$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
$tablas = array("D"=>"edu_tab_docentes", "E"=>"edu_tab_estudiantes", "R"=>"edu_tab_representantes");
if(!isset($tablas[$tipo]) || $id <= 0){
  echo json_encode(array("error"=>"Datos no válidos"));
  exit;
}

function consultar($sql, $parametros){
  $stmt = Conexion::conectar()->prepare($sql);
  $stmt->execute($parametros);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*SOLO LAS COLUMNAS QUE SE MUESTRAN: NUNCA LA CONTRASEÑA*/
$columnas = "id, cedula, nombres, apellidos, genero, email, telefono, direccion, usuario, foto"
  .($tipo != "R" ? ", fecha_nacimiento" : "")
  .($tipo == "D" ? ", estado" : "")
  .($tipo == "E" ? ", id_representante" : "");
$fila = consultar("SELECT $columnas FROM ".$tablas[$tipo]." WHERE id = :id", array(":id"=>$id));
if(empty($fila)){
  echo json_encode(array("error"=>"No se encontró el registro"));
  exit;
}
$ficha = $fila[0];

/*AÑO LECTIVO ACTIVO*/
$periodo = consultar("SELECT id, nombre FROM edu_tab_periodo WHERE estado = '1' ORDER BY id DESC LIMIT 1", array());
$ficha["periodo"] = $periodo ? $periodo[0]["nombre"] : "";
$idPeriodo = $periodo ? $periodo[0]["id"] : 0;

if($tipo == "D"){
  /*DISTRIBUTIVO DEL AÑO ACTIVO*/
  $ficha["clases"] = consultar("SELECT CONCAT(etg.nombre,' ',etp.nombre) AS curso, etm.nombre AS materia
    FROM edu_tab_grad_docente etgd
    INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etgd.id_grado_docente
    INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
    INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
    INNER JOIN edu_tab_materias etm ON etm.id = etgd.id_materia
    WHERE etgd.id_docente = :id AND etgp.id_periodo = :periodo
    ORDER BY etg.orden, etg.nombre, etp.nombre, etm.nombre", array(":id"=>$id, ":periodo"=>$idPeriodo));
}

if($tipo == "E"){
  $curso = consultar("SELECT CONCAT(etg.nombre,' ',etp.nombre) AS curso
    FROM edu_tab_matricula etm
    INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etm.id_grado_paralelo
    INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
    INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
    WHERE etm.id_estudiante = :id AND etm.id_periodo = :periodo LIMIT 1", array(":id"=>$id, ":periodo"=>$idPeriodo));
  $ficha["curso"] = $curso ? $curso[0]["curso"] : "";
  $rep = consultar("SELECT id, cedula, nombres, apellidos, telefono, email, foto FROM edu_tab_representantes WHERE id = :id",
    array(":id"=>(int)$ficha["id_representante"]));
  $ficha["representante"] = $rep ? $rep[0] : null;
}

if($tipo == "R"){
  /*ESTUDIANTES A SU CARGO CON SU CURSO DEL AÑO ACTIVO*/
  $ficha["representados"] = consultar("SELECT ete.id, ete.cedula, ete.nombres, ete.apellidos, ete.foto,
      (SELECT CONCAT(etg.nombre,' ',etp.nombre)
         FROM edu_tab_matricula etm
         INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etm.id_grado_paralelo
         INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
         INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
        WHERE etm.id_estudiante = ete.id AND etm.id_periodo = :periodo LIMIT 1) AS curso
    FROM edu_tab_estudiantes ete WHERE ete.id_representante = :id ORDER BY ete.apellidos, ete.nombres",
    array(":id"=>$id, ":periodo"=>$idPeriodo));
}

echo json_encode($ficha);
