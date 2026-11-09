<?php
require_once "../CONTROLADOR/seguridad.php";
Seguridad::exigirAjax(array("Administrador","Secretaría"));
require_once "../CONTROLADOR/alumno.controlador.php";
require_once "../MODELO/alumno.modelo.php";

class AjaxAlumnos{
/*=============================================
=         EDITAR INFORMACION Alumno       =
=============================================*/
public $idAlumno;
public $idAlumnoV;

public function ajaxEditarAlumno(){
    $item ="id";
    $valor = $this->idAlumno;
    $respuesta = ControladorAlumnos::ctrMostrarAlumnos($item,$valor);
     echo json_encode(Seguridad::sinPassword($respuesta));

}
/*=============================================
=         VISUA INFORMACION Alumno       =
=============================================*/
public function ajaxVisuaAlumno(){
    $item ="id";
    $valor = $this->idAlumnoV;
   $respuesta = ControladorAlumnos::ctrMostrarAlumnoRepresentante($item,$valor);
 // $respuesta = ControladorAlumnos::ctrMostrarAlumnos($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));

}
/*=============================================
=         ACTIVAR Alumno      =
=============================================*/
public $activarAlumno;
public $activarId;

public function ajaxActivarAlumno(){
    $tabla = "edu_tab_Alumnos";

    $item1 = "estado";
    $valor1 = $this->activarAlumno;

    $item2 = "id";
    $valor2 = $this->activarId;

    $respuesta = ModeloAlumnos::mdlActualizarAlumno($tabla, $item1, $valor1, $item2, $valor2);


}
/*=============================================
         REVISAR Alumno REPETIDO           
=============================================*/
public $validarAlumno;
public function ajaxValidarAlumno(){
    $item ="cedula";
    $valor = $this->validarAlumno;
    $respuesta = ControladorAlumnos::ctrMostrarAlumnos($item,$valor);
    echo json_encode(Seguridad::sinPassword($respuesta));

}

}
/*=============================================
=         EDITAR INFORMACION Alumno       =
=============================================*/
if(isset($_POST["idAlumno"])){
    $editar = new AjaxAlumnos();
    $editar -> idAlumno = $_POST["idAlumno"];
    $editar -> ajaxEditarAlumno();

}
/*=============================================
=         VISUALIZAR INFORMACION Alumno       =
=============================================*/
if(isset($_POST["idAlumnoV"])){
    $visua = new AjaxAlumnos();
    $visua -> idAlumnoV = $_POST["idAlumnoV"];
    $visua -> ajaxVisuaAlumno();

}
/*=============================================
=         ACTIVAR Alumno      =
=============================================*/

if(isset($_POST["activarAlumno"])){

	$activarAlumno = new AjaxAlumnos();
	$activarAlumno -> activarAlumno = $_POST["activarAlumno"];
	$activarAlumno -> activarId = $_POST["activarId"];
	$activarAlumno -> ajaxActivarAlumno();

}

/*=============================================
         REVISAR Alumno REPETIDO           
=============================================*/
if(isset($_POST["validarAlumno"])){
    $valAlumno = new AjaxAlumnos();
    $valAlumno -> validarAlumno = $_POST["validarAlumno"];
    $valAlumno -> ajaxValidarAlumno();

}