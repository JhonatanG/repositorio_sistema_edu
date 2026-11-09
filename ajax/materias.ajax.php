<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador"));

require_once "../CONTROLADOR/materias.controlador.php";
require_once "../MODELO/materias.modelo.php";

class AjaxMateria{
/*=============================================
EDITAR INFORMACION Materia LECTIVO
=============================================*/
public $idMateria;

public function ajaxEditarMateria(){
    $item="id";
    $valor=$this->idMateria;
    $respuesta = ControladorMaterias::ctrMostrarMaterias($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}
/*=============================================
VERIFICAR SI EL Materia LECTIVO ESTA REPETIDO
=============================================*/
public $validarMateria;

public function ajaxValidarMateria(){
    $item="nombre";
    $valor=$this->validarMateria;
    $respuesta = ControladorMaterias::ctrMostrarMaterias($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));
}
   
}
/*=============================================
EDITAR INFORMACION Materia
=============================================*/
if(isset($_POST["idMateria"])){
    $editar = new AjaxMateria();
    $editar -> idMateria = $_POST["idMateria"];
    $editar->ajaxEditarMateria();
}
/*=============================================
VERIFICAR SI LA Materia ESTA REPETIDO
=============================================*/
if(isset($_POST["validarMateria"])){
    $validar = new AjaxMateria();
    $validar -> validarMateria=$_POST["validarMateria"];
    $validar->ajaxValidarMateria();

}