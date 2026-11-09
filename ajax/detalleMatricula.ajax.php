<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador","Secretaría"));

require_once "../CONTROLADOR/matricula.controlador.php";
require_once "../MODELO/matricula.modelo.php";

class AjaxMatricula{
/*=============================================
EDITAR INFORMACION Gparalelo
=============================================*/
public $idGparalelo;
public $idMatricula;


public function ajaxEditarMatricula(){
    $item="id";
    $valor=$this->idMatricula;
    $respuesta = ControladorMatricula::ctrMostrarMatricula($item,$valor);
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
EDITAR INFORMACION 
=============================================*/
if(isset($_POST["idMatricula"])){
    $editar = new AjaxMatricula();
    $editar -> idMatricula = $_POST["idMatricula"];
    $editar->ajaxEditarMatricula();
}

/*=============================================
=         ACTIVAR Gparalelo
=============================================*/

if(isset($_POST["activarGparalelo"])){

	$activarGparalelo = new AjaxMatricula();
	$activarGparalelo -> activarGparalelo = $_POST["activarGparalelo"];
	$activarGparalelo -> activarId = $_POST["activarId"];
	$activarGparalelo -> ajaxActivarGparalelo();

}
