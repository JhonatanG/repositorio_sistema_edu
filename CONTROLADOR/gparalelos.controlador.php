<?php

class ControladorGparalelos{

    /*=============================================
MOSTRAR INFORMACION
=============================================*/
static public function ctrMostrarGparalelos($item,$valor){
    $tabla="edu_tab_grado_paralelo";
    $respuesta=ModeloGparalelos::mdlMostrarGparalelos($tabla,$item,$valor);
    return $respuesta;
}
/*=============================================
GUARDAR INFORMACION
=============================================*/
    static public function ctrCrearGparalelos(){

        if(isset($_POST["idGrado"])){
            if(isset($_POST["idParalelo"]) && is_array($_POST["idParalelo"])){
            $tabla="edu_tab_grado_paralelo";  
            $idParalelo=$_POST["idParalelo"];

            foreach ($idParalelo as $value) {
              $datos=array("idGrado" => $_POST["idGrado"]
              ,"idParalelo"=>$value,
              "idPeriodo"=>$_POST["nuevoPeriodo"]);
              $respuesta=ModeloGparalelos::mdlMostrarRepetidosG($tabla,$datos);
            //  var_dump($respuesta);
          }
          if(empty($respuesta)){

            foreach ($idParalelo as $value) {
                $datos=array("idGrado" => $_POST["idGrado"]
                ,"idParalelo"=>$value,
                "cupos"=>$_POST["nuevoCupos"],
                "observacion"=>$_POST["nuevaObservacion"],
              "estado"=>$_POST["nuevoEstado"],
              "idPeriodo"=>$_POST["nuevoPeriodo"]);
                $respuesta=ModeloGparalelos::mdlCrearGparalelos($tabla,$datos);
            }
            if($respuesta=="ok"){
                echo'<script>
                Swal.fire({
                  icon: "success",
                  title: "¡Ha sido guardado correctamente!",
                  showConfirmButton: true,
                  confirmButtonText: "Cerrar",
                  closeOnConfirm:false
                }).then(function(result){
                    if (result.value) {
                    window.location = "'.SERVERURL.'gparalelos/";
                    }
                });
                
                </script>';
            }else {
              echo'<script>
              Swal.fire({
                icon: "error",
                title: "¡La relación grado-paralelo NO ha sido guardado correctamente!",
                showConfirmButton: true,
                confirmButtonText: "Cerrar",
                closeOnConfirm:false
              }).then(function(result){
                  if (result.value) {
                  window.location = "'.SERVERURL.'gparalelos/";
                  }
              });
              
              </script>';
          }
          }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡Ya existe relación entre el grado y el paralelo!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'gparalelos/";
                }
            });
            
            </script>';
          }
      }
        else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡Debe seleccionar los campos!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'gparalelos/";
                }
            });
            
            </script>';
        }
        
        }

    }
/*=============================================
ELIMINAR Grados
=============================================*/
static public function ctrEliminarGparalelos(){
    if(isset($_POST["idGparalelo"]) && Seguridad::tokenValido()){
        $tabla="edu_tab_grado_paralelo";
        $datos=$_POST["idGparalelo"];
        $respuesta=ModeloGparalelos::mdlEliminarGparalelos($tabla,$datos);
    if($respuesta=="ok"){
        echo'<script>
            Swal.fire({
              icon: "success",
              title: "El Grado y Paralelo han sido borrados correctamente",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'gparalelos/";
        
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
                window.location="'.SERVERURL.'gparalelos/";
              }
            });
            
            </script>';
    }

    }
}
/*=============================================
EDITAR Informacion
=============================================*/
static public function ctrEditarGparalelo(){
  if(isset($_POST["editarCupos"])){
      if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["editarCupos"])){
          $tabla ="edu_tab_grado_paralelo";
          $datos=array(
                  "id"=>$_POST["editarGparalelo"],
                  "cupos"=>$_POST["editarCupos"],
                  "observacion"=>$_POST["editarObservacion"]);
          $respuesta=ModeloGparalelos::mdlEditarGparalelos($tabla,$datos);

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
              window.location = "'.SERVERURL.'gparalelos/";
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
                window.location="'.SERVERURL.'gparalelos/";
        
              }
            });
            
            </script>';
          }
          


      }else{
          echo'<script>
          Swal.fire({
            icon: "error",
            title: "¡No se admite caracteres especiales, intente nuevamente!",
            text: "Ejemplo (Este grado esta deshabilitado)",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
            closeOnConfirm:false
          }).then((result)=>{
            if(result.value){
              window.location="'.SERVERURL.'gparalelos/";
      
            }
          });
          
          </script>';
      }


  }


}
    
}