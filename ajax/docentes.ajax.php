<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador","Secretaría"));
require_once "../CONTROLADOR/docentes.controlador.php";
require_once "../MODELO/docentes.modelo.php";

class AjaxDocentes{
/*=============================================
=         EDITAR INFORMACION Docente       =
=============================================*/
public $idDocente;

public function ajaxEditarDocente(){
    $item ="id";
    $valor = $this->idDocente;
    $respuesta = ControladorDocentes::ctrMostrarDocentes($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));

}
/*=============================================
=         VISUA INFORMACION Docente       =
=============================================*/
public function ajaxVisuaDocente(){
    $item ="id";
    $valor = $this->idDocente;
    $respuesta = ControladorDocentes::ctrMostrarDocentes($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));

}
/*=============================================
=         ACTIVAR Docente      =
=============================================*/
public $activarDocente;
public $activarId;

public function ajaxActivarDocente(){
    $tabla = "edu_tab_docentes";

    $item1 = "estado";
    $valor1 = $this->activarDocente;

    $item2 = "id";
    $valor2 = $this->activarId;

    $respuesta = ModeloDocentes::mdlActualizarDocente($tabla, $item1, $valor1, $item2, $valor2);


}
/*=============================================
         REVISAR Docente REPETIDO           
=============================================*/
public $validarDocente;
public function ajaxValidarDocente(){
    $item ="cedula";
    $valor = $this->validarDocente;
    $respuesta = ControladorDocentes::ctrMostrarDocentes($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));

}

}
/*=============================================
=         EDITAR INFORMACION Docente       =
=============================================*/
if(isset($_POST["idDocente"])){
    $editar = new AjaxDocentes();
    $editar -> idDocente = $_POST["idDocente"];
    $editar -> ajaxEditarDocente();

}
/*=============================================
=         VISUALIZAR INFORMACION Docente       =
=============================================*/
if(isset($_POST["idDocenteV"])){
    $visua = new AjaxDocentes();
    $visua -> idDocente = $_POST["idDocente"];
    $visua -> ajaxVisuaDocente();

}
/*=============================================
=         ACTIVAR Docente      =
=============================================*/

if(isset($_POST["activarDocente"])){

	$activarDocente = new AjaxDocentes();
	$activarDocente -> activarDocente = $_POST["activarDocente"];
	$activarDocente -> activarId = $_POST["activarId"];
	$activarDocente -> ajaxActivarDocente();

}

/*=============================================
         REVISAR Docente REPETIDO           
=============================================*/
if(isset($_POST["validarDocente"])){
    $valDocente = new AjaxDocentes();
    $valDocente -> validarDocente = $_POST["validarDocente"];
    $valDocente -> ajaxValidarDocente();

}