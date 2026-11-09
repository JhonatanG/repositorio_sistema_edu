<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador"));

require_once "../CONTROLADOR/paralelos.controlador.php";
require_once "../MODELO/paralelos.modelo.php";

class AjaxParalelo{
/*=============================================
EDITAR INFORMACION Paralelo 
=============================================*/
public $idParalelo;

public function ajaxEditarParalelo(){
    $item="id";
    $valor=$this->idParalelo;
    $respuesta = ControladorParalelos::ctrMostrarParalelos($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}
/*=============================================
VERIFICAR SI EL Paralelo ESTA REPETIDO
=============================================*/
public $validarParalelo;

public function ajaxValidarParalelo(){
    $item="nombre";
    $valor=$this->validarParalelo;
    $respuesta = ControladorParalelos::ctrMostrarParalelos($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}
   
}
/*=============================================
EDITAR INFORMACION Paralelo
=============================================*/
if(isset($_POST["idParalelo"])){
    $editar = new AjaxParalelo();
    $editar -> idParalelo = $_POST["idParalelo"];
    $editar->ajaxEditarParalelo();
}
/*=============================================
VERIFICAR SI LA Paralelo ESTA REPETIDO
=============================================*/
if(isset($_POST["validarParalelo"])){
    $validar = new AjaxParalelo();
    $validar -> validarParalelo=$_POST["validarParalelo"];
    $validar->ajaxValidarParalelo();

}