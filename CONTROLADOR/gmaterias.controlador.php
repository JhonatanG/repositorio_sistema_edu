<?php

class ControladorGmaterias{

    /*=============================================
MOSTRAR INFORMACION
=============================================*/
static public function ctrMostrarGmaterias($item,$valor){
    $tabla="edu_tab_materias_grado";
    $respuesta=ModeloGmaterias::mdlMostrarGmaterias($tabla,$item,$valor);
    return $respuesta;
}
/*=============================================
GUARDAR INFORMACION
=============================================*/
    static public function ctrCrearGmaterias(){

        if(isset($_POST["idGrado"])){
            if(isset($_POST["idMateria"]) && is_array($_POST["idMateria"])){
            $tabla="edu_tab_materias_grado";  
            $idMateria=$_POST["idMateria"];
            foreach ($idMateria as $value) {
                $datos=array("idGrado" => $_POST["idGrado"]
                ,"idMateria"=>$value,"observacion"=>$_POST["nuevaObservacion"]);
                $respuesta=ModeloGmaterias::mdlCrearGmaterias($tabla,$datos);
                
            }
            if($respuesta=="ok"){
                echo'<script>
                Swal.fire({
                  icon: "success",
                  title: "¡El grado y los materias han sido guardados correctamente!",
                  showConfirmButton: true,
                  confirmButtonText: "Cerrar",
                  closeOnConfirm:false
                }).then(function(result){
                    if (result.value) {
                    window.location = "'.SERVERURL.'gmaterias/";
                    }
                });
                
                </script>';
            }

        }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡Debe seleccionar los campos!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'gmaterias/";
                }
            });
            
            </script>';
        }
        }

    }
/*=============================================
ELIMINAR Grados
=============================================*/
static public function ctrEliminarGmaterias(){
    if(isset($_POST["idGmateria"]) && Seguridad::tokenValido()){
        $tabla="edu_tab_materias_grado";
        $datos=$_POST["idGmateria"];
        $respuesta=ModeloGmaterias::mdlEliminarGmaterias($tabla,$datos);
    if($respuesta=="ok"){
        echo'<script>
            Swal.fire({
              icon: "success",
              title: "El Grado y materia han sido borrado correctamente",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'gmaterias/";
        
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
                window.location="'.SERVERURL.'grados/";
              }
            });
            
            </script>';
    }

    }
}

    
}