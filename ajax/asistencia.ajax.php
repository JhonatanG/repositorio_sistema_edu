<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Docente","Administrador","Secretaría"));
require_once "../CONTROLADOR/asistencia.controlador.php";
require_once "../MODELO/asistencia.modelo.php";

class AjaxAsistencias{
/*=============================================
=   DEVUELVE MATERIAS SEGUN DOCENTE   =
=============================================*/
public $idDocente;
public $idGradoD;

public function ajaxMateriasDocente(){
    $item="id_docente";
    $valor = $this->idDocente;
    $item2="id_grado_docente";
    $valor2 = $this->idGradoD;
 // $respuesta=ControladorAsistencia::ctrListaMateriasDocente($item,$item2,$valor,$valor2);
   //$respuesta=ControladorAsistencia::ctrListaGradosDocente($item,$valor);
  $respuesta = ControladorAsistencia::ctrListaMDoc($item,$valor,$item2,$valor2);
  //var_dump($respuesta);
    echo json_encode(Seguridad::sinPassword($respuesta));
}

/*=============================================
=  EDITAR ASISTENCIA
=============================================*/
public $idAsistencia;
public function ajaxEditarAsistencia(){
  $item="id";
  $valor=$this->idAsistencia;
  $respuesta=ControladorAsistencia::ctrMostrarAsistencia($item,$valor);
  echo json_encode(Seguridad::sinPassword($respuesta));
}





}
/*=============================================
=           DEVUELVE MATERIAS SEGUN DOCENTE   =
=============================================*/
if(isset($_POST["idDocente"])){
    $materias = new AjaxAsistencias();
    $materias -> idDocente = $_POST["idDocente"];
  $materias -> idGradoD = $_POST["idGradoD"];
    $materias -> ajaxMateriasDocente();

}

/*=============================================
=  EDITAR ASISTENCIA
=============================================*/
if(isset($_POST["idAsistencia"])){
  $editar = new AjaxAsistencias();
  $editar -> idAsistencia = $_POST["idAsistencia"];
  $editar->ajaxEditarAsistencia();
}