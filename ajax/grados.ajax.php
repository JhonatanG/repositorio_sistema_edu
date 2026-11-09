<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador"));

require_once "../CONTROLADOR/grados.controlador.php";
require_once "../MODELO/grados.modelo.php";

class AjaxGrados{
/*=============================================
EDITAR INFORMACION Grados 
=============================================*/
public $idGrados;

public function ajaxEditarGrados(){
    $item="id";
    $valor=$this->idGrados;
    $respuesta = ControladorGrados::ctrMostrarGrados($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}
/*=============================================
VERIFICAR SI EL Grados  ESTA REPETIDO
=============================================*/
public $validarGrados;

public function ajaxValidarGrados(){
    $item="nombre";
    $valor=$this->validarGrados;
    $respuesta = ControladorGrados::ctrMostrarGrados($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}

}
/*=============================================
EDITAR INFORMACION Grados 
=============================================*/
if(isset($_POST["idGrados"])){
    $editar = new AjaxGrados();
    $editar -> idGrados = $_POST["idGrados"];
    $editar->ajaxEditarGrados();
}
/*=============================================
VERIFICAR SI EL Grados  ESTA REPETIDO
=============================================*/
if(isset($_POST["validarGrados"])){
    $validar = new AjaxGrados();
    $validar -> validarGrados=$_POST["validarGrados"];
    $validar->ajaxValidarGrados();

}
