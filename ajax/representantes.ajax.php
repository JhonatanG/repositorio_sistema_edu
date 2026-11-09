<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador","Secretaría"));
require_once "../CONTROLADOR/representantes.controlador.php";
require_once "../MODELO/representantes.modelo.php";

class AjaxRepresentantes{
/*=============================================
=         EDITAR INFORMACIONRepresentante     =
=============================================*/
public $idRepresentante;

public function ajaxEditarRepresentante(){
    $item ="id";
    $valor = $this->idRepresentante;
    $respuesta = ControladorRepresentantes::ctrMostrarRepresentantes($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));

}

/*=============================================
         REVISAR Representante REPETIDO           
=============================================*/
public $validarRepresentante;
public function ajaxValidarRepresentante(){
    $item ="cedula";
    $valor = $this->validarRepresentante;
    $respuesta = ControladorRepresentantes::ctrMostrarRepresentantes($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));

}

}
/*=============================================
=         EDITAR INFORMACIONRepresentante     =
=============================================*/
if(isset($_POST["idRepresentante"])){
    $editar = new AjaxRepresentantes();
    $editar -> idRepresentante = $_POST["idRepresentante"];
    $editar -> ajaxEditarRepresentante();

}

/*=============================================
         REVISAR Representante REPETIDO           
=============================================*/
if(isset($_POST["validarRepresentante"])){
    $valRepresentante = new AjaxRepresentantes();
    $valRepresentante -> validarRepresentante = $_POST["validarRepresentante"];
    $valRepresentante -> ajaxValidarRepresentante();

}