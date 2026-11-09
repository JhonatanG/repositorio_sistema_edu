<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador"));

require_once "../CONTROLADOR/gparalelos.controlador.php";
require_once "../MODELO/gparalelos.modelo.php";

class AjaxGparalelo{
/*=============================================
EDITAR INFORMACION Gparalelo
=============================================*/
public $idGparalelo;

public function ajaxEditarGparalelo(){
    $item="id";
    $valor=$this->idGparalelo;
    $respuesta = ControladorGparalelos::ctrMostrarGparalelos($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}

/*=============================================
=         ACTIVAR Gparalelo
=============================================*/
public $activarGparalelo;
public $activarId;

public function ajaxActivarGparalelo(){
    $tabla = "edu_tab_grado_paralelo";

    $item1 = "estado";
    $valor1 = $this->activarGparalelo;

    $item2 = "id";
    $valor2 = $this->activarId;

    $respuesta = ModeloGparalelos::mdlActualizarGparalelos($tabla, $item1, $valor1, $item2, $valor2);


}
}
/*=============================================
EDITAR INFORMACION Gparalelo LECTIVO
=============================================*/
if(isset($_POST["idGparalelo"])){
    $editar = new AjaxGparalelo();
    $editar -> idGparalelo = $_POST["idGparalelo"];
    $editar->ajaxEditarGparalelo();
}

/*=============================================
=         ACTIVAR Gparalelo
=============================================*/

if(isset($_POST["activarGparalelo"])){

	$activarGparalelo = new AjaxGparalelo();
	$activarGparalelo -> activarGparalelo = $_POST["activarGparalelo"];
	$activarGparalelo -> activarId = $_POST["activarId"];
	$activarGparalelo -> ajaxActivarGparalelo();

}
