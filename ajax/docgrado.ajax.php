<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador"));

require_once "../CONTROLADOR/docgrado.controlador.php";
require_once "../MODELO/docgrado.modelo.php";

class AjaxDocgrado{
/*=============================================
EDITAR INFORMACION Docgrado
=============================================*/
public $idDocgrado;

public function ajaxEditarDocgrado(){
    $item="id";
    $valor=$this->idDocgrado;
    $respuesta = ControladorDocg::ctrMostrarDocg($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}

/*=============================================
=         ACTIVAR Docgrado
=============================================*/
public $activarDocgrado;
public $activarId;

public function ajaxActivarDocgrado(){
    $tabla = "edu_tab_grad_docente";

    $item1 = "estado";
    $valor1 = $this->activarDocgrado;

    $item2 = "id";
    $valor2 = $this->activarId;

    $respuesta = ModeloDocg::mdlActualizarDocgrado($tabla, $item1, $valor1, $item2, $valor2);


}
}
/*=============================================
EDITAR INFORMACION Docgrado LECTIVO
=============================================*/
if(isset($_POST["idDocgrado"])){
    $editar = new AjaxDocgrado();
    $editar -> idDocgrado = $_POST["idDocgrado"];
    $editar->ajaxEditarDocgrado();
}

/*=============================================
=         ACTIVAR Docgrado
=============================================*/

if(isset($_POST["activarDocgrado"])){

	$activarDocgrado = new AjaxDocgrado();
	$activarDocgrado -> activarDocgrado = $_POST["activarDocgrado"];
	$activarDocgrado -> activarId = $_POST["activarId"];
	$activarDocgrado -> ajaxActivarDocgrado();

}
