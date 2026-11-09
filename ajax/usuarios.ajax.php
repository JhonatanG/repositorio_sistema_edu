<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador"));
require_once "../CONTROLADOR/usuarios.controlador.php";
require_once "../MODELO/usuarios.modelo.php";

class AjaxUsuarios{
/*=============================================
=         EDITAR INFORMACION USUARIO       =
=============================================*/
public $idUsuario;

public function ajaxEditarUsuario(){
    $item ="id";
    $valor = $this->idUsuario;
    $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));

}

/*=============================================
=         ACTIVAR USUARIO      =
=============================================*/
public $activarUsuario;
public $activarId;

public function ajaxActivarUsuario(){
    $tabla = "edu_tab_usuarios";

    $item1 = "estado";
    $valor1 = $this->activarUsuario;

    $item2 = "id";
    $valor2 = $this->activarId;

    $respuesta = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);


}
/*=============================================
         REVISAR USUARIO REPETIDO           
=============================================*/
public $validarUsuario;
public function ajaxValidarUsuario(){
    $item ="usuario";
    $valor = $this->validarUsuario;
    $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));

}

}
/*=============================================
=         EDITAR INFORMACION USUARIO       =
=============================================*/
if(isset($_POST["idUsuario"])){
    $editar = new AjaxUsuarios();
    $editar -> idUsuario = $_POST["idUsuario"];
    $editar -> ajaxEditarUsuario();

}
/*=============================================
=         ACTIVAR USUARIO      =
=============================================*/

if(isset($_POST["activarUsuario"])){

	$activarUsuario = new AjaxUsuarios();
	$activarUsuario -> activarUsuario = $_POST["activarUsuario"];
	$activarUsuario -> activarId = $_POST["activarId"];
	$activarUsuario -> ajaxActivarUsuario();

}

/*=============================================
         REVISAR USUARIO REPETIDO           
=============================================*/
if(isset($_POST["validarUsuario"])){
    $valUsuario = new AjaxUsuarios();
    $valUsuario -> validarUsuario = $_POST["validarUsuario"];
    $valUsuario -> ajaxValidarUsuario();

}