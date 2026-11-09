<?php

class ControladorMatricula{
    /*=============================================
GUARDAR INFORMACION
=============================================*/
static public function ctrCrearMatricula(){

    if(isset($_POST["nuevaMatricula"])){
        if(isset($_POST["nuevoGrado"])){
        
        $tabla="edu_tab_matricula";  
        $datosRep=array(
    
      "idPeriodo"=>$_POST["nuevoPeriodo"],
      "idEstudiante"=>$_POST["nuevoEstudiante"]);
       $repetidos = ModeloMatricula::mdlMostrarRepetidosM($tabla,$datosRep);
       //var_dump($repetidos);
if(empty($repetidos)){

        $tabla2="edu_tab_grado_paralelo";  

            $datos=array("codigo" => $_POST["nuevaMatricula"],
            "idEstudiante"=>$_POST["nuevoEstudiante"],
            "observacion"=>$_POST["nuevaObservacion"],
          "idGparalelo"=>$_POST["nuevoGrado"],
          "idPeriodo"=>$_POST["nuevoPeriodo"],
          "idUsuario"=>$_POST["nuevoUsuario"]);
          
            $respuesta=ModeloMatricula::mdlCrearMatricula($tabla,$datos);
            $datos2=array("codigo" => $_POST["nuevaMatricula"]);
           // var_dump($datos2);

         
        if($respuesta=="ok"){
            $respuestaNumCupos = ModeloMatricula::mdlActualizaNum($tabla2,$datos2);
            echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡Ha sido guardado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'listaMatricula/";
                }
            });
            
            </script>';
        }else {
          echo'<script>
          Swal.fire({
            icon: "error",
            title: "¡No ha sido guardado correctamente!",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
            closeOnConfirm:false
          }).then(function(result){
              if (result.value) {
              window.location = "'.SERVERURL.'matricula/";
              }
          });
          
          </script>';
      }
    }else{
        echo'<script>
        Swal.fire({
          icon: "error",
          title: "¡El estudiante ya se encuentra matriculado en el periodo actual!",
          showConfirmButton: true,
          confirmButtonText: "Cerrar",
          closeOnConfirm:false
        }).then(function(result){
            if (result.value) {
            window.location = "'.SERVERURL.'matricula/";
            }
        });
        
        </script>';
    }
        }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡Debe seleccionar un grado!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'matricula/";
                }
            });
            
            </script>';
        }
 
    
    }

}
    /*=============================================
MOSTRAR INFORMACION
=============================================*/
static public function ctrMostrarMatricula($item,$valor){
    $tabla="edu_tab_matricula";
    $respuesta= ModeloMatricula::mdlMostrarMatricula($tabla,$item,$valor);
    return $respuesta;
}
    /*=============================================
MOSTRAR INFORMACION DETALLADA
=============================================*/
static public function ctrMostrarMatriculaDet($item,$valor){
    $tabla="edu_tab_matricula";
    $respuesta= ModeloMatricula::mdlMostrarMatriculaDet($tabla,$item,$valor);
    return $respuesta;
}
 /*=============================================
LISTA INFORMACION GRADO PARALELO
=============================================*/
static public function ctrMostrarGparalelos(){
    $respuesta = ModeloMatricula::mdlMostrarGparalelos();
    return $respuesta;
}
   /*=============================================
	LISTAR ESTUDIANTE
	=============================================*/
static public function ctrListarEstudiante(){
    $tabla="edu_tab_estudiantes";
    $respuesta = ModeloMatricula::mdlListarEstudiante($tabla);
    return $respuesta;
}
      /*=============================================
LISTA INFORMACION GRADO PARALELO POR ID
=============================================*/
static public function ctrMostrarGparalelosporId($item,$valor){
  
    $respuesta = ModeloMatricula::mdlMostrarGparalelosporId($item,$valor);
    return $respuesta;
}
/*=============================================
EDITAR Informacion
=============================================*/
static public function ctrEditarMatricula(){
    if(isset($_POST["editarMatricula"])){
 
            $tabla ="edu_tab_matricula";
            $datos=array(
                    "id"=>$_POST["editarMatricula"],
                    "observacion"=>$_POST["editarObservacion"]);
            $respuesta=ModeloMatricula::mdlEditarMatricula($tabla,$datos);
  
            if($respuesta=="ok"){
                echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡Ha sido modificado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'listaMatricula/";
                }
            });
            
            </script>';
            }else{
              echo'<script>
              Swal.fire({
                icon: "error",
                title: "¡No se pudo editar la infomación, intente nuevamente!",
                showConfirmButton: true,
                confirmButtonText: "Cerrar",
                closeOnConfirm:false
              }).then((result)=>{
                if(result.value){
                  window.location="'.SERVERURL.'listaMatricula/";
          
                }
              });
              
              </script>';
            }
            
  
  
    
  
  
    }
  
  
  }
  /*=============================================
ELIMINAR 
=============================================*/
static public function ctrEliminarMatricula(){

    if(isset($_POST["idMatricula"]) && Seguridad::tokenValido()){
        $tabla="edu_tab_matricula";
        $datos=$_POST["idMatricula"];
     
        $respuesta=ModeloMatricula::mdlEliminarMatricula($tabla,$datos);
        var_dump($respuesta);
    if($respuesta=="ok"){
        $respuesta2=ModeloMatricula::mdlActualizarNumAum($tabla,$datos);
        echo'<script>
            Swal.fire({
              icon: "success",
              title: "El Grado y Paralelo han sido borrados correctamente",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'listaMatricula/";
        
              }
            });
            
            </script>';
    }else{
        echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡No se pudo borrar!",
              text: '.json_encode(Conexion::$ultimoError != "" ? Conexion::$ultimoError : "Puede estar la información relacionada con las demás partes del sistema", JSON_UNESCAPED_UNICODE).',
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'listaMatricula/";
              }
            });
            
            </script>';
    }

    }
}
}