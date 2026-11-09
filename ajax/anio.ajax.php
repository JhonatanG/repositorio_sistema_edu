<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador"));

require_once "../CONTROLADOR/anio.controlador.php";
require_once "../MODELO/anio.modelo.php";

class AjaxAnio{
/*=============================================
EDITAR INFORMACION ANIO LECTIVO
=============================================*/
public $idAnio;

public function ajaxEditarAnio(){
    $item="id";
    $valor=$this->idAnio;
    $respuesta = ControladorAnio::ctrMostrarAnio($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}
/*=============================================
VERIFICAR SI EL ANIO LECTIVO ESTA REPETIDO
=============================================*/
public $validarAnio;

public function ajaxValidarAnio(){
    $item="nombre";
    $valor=$this->validarAnio;
    $respuesta = ControladorAnio::ctrMostrarAnio($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}
/*=============================================
=         ACTIVAR ANIO
=============================================*/
public $activarAnio;
public $activarId;

public function ajaxActivarAnio(){
    $tabla = "edu_tab_periodo";

    $item1 = "estado";
    $valor1 = $this->activarAnio;

    $item2 = "id";
    $valor2 = $this->activarId;

    $respuesta = ModeloAnio::mdlActualizarAnio($tabla, $item1, $valor1, $item2, $valor2);


}
}
/*=============================================
EDITAR INFORMACION ANIO LECTIVO
=============================================*/
if(isset($_POST["idAnio"])){
    $editar = new AjaxAnio();
    $editar -> idAnio = $_POST["idAnio"];
    $editar->ajaxEditarAnio();
}
/*=============================================
VERIFICAR SI EL ANIO LECTIVO ESTA REPETIDO
=============================================*/
if(isset($_POST["validarAnio"])){
    $validar = new AjaxAnio();
    $validar -> validarAnio=$_POST["validarAnio"];
    $validar->ajaxValidarAnio();

}
/*=============================================
=         ACTIVAR ANIO LECTIVO
=============================================*/

if(isset($_POST["activarAnio"])){

	$activarAnio = new AjaxAnio();
	$activarAnio -> activarAnio = $_POST["activarAnio"];
	$activarAnio -> activarId = $_POST["activarId"];
	$activarAnio -> ajaxActivarAnio();

}
