<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador"));

require_once "../CONTROLADOR/madocente.controlador.php";
require_once "../MODELO/madocente.modelo.php";

class AjaxMdocente{
/*=============================================
EDITAR INFORMACION Mdocente
=============================================*/
public $idMdocente;

public function ajaxEditarMdocente(){
    $item="id";
    $valor=$this->idMdocente;
    $respuesta = ControladorMdocentes::ctrMostrarMdocentes($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}

/*=============================================
=         ACTIVAR Mdocente
=============================================*/
public $activarMdocente;
public $activarId;

public function ajaxActivarMdocente(){
    $tabla = "edu_tab_materias_docentes";

    $item1 = "estado";
    $valor1 = $this->activarMdocente;

    $item2 = "id";
    $valor2 = $this->activarId;

    $respuesta = ModeloMdocentes::mdlActualizarMdocente($tabla, $item1, $valor1, $item2, $valor2);


}
}
/*=============================================
EDITAR INFORMACION Mdocente LECTIVO
=============================================*/
if(isset($_POST["idMdocente"])){
    $editar = new AjaxMdocente();
    $editar -> idMdocente = $_POST["idMdocente"];
    $editar->ajaxEditarMdocente();
}

/*=============================================
=         ACTIVAR Mdocente
=============================================*/

if(isset($_POST["activarMdocente"])){

	$activarMdocente = new AjaxMdocente();
	$activarMdocente -> activarMdocente = $_POST["activarMdocente"];
	$activarMdocente -> activarId = $_POST["activarId"];
	$activarMdocente -> ajaxActivarMdocente();

}
