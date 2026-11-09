<?php
class ControladorParalelos{
/*=============================================
MOSTRAR INFORMACION
=============================================*/
    static public function ctrMostrarParalelos($item,$valor){
        $tabla="edu_tab_paralelos";
        $respuesta= ModeloParalelos::mdlMostrarParalelos($tabla,$item,$valor);
        return $respuesta;
    }
/*=============================================
CREAR Paralelos
=============================================*/
    static public function ctrCrearParalelos(){
        if(isset($_POST["nuevoParalelo"])){
        if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["nuevoParalelo"])){
        $tabla="edu_tab_paralelos";
        $datos=array("nombre" =>strtoupper( $_POST["nuevoParalelo"]),"observacion"=>$_POST["nuevaObservacion"]);
        $respuesta=ModeloParalelos::mdlCrearParalelos($tabla,$datos);
        if($respuesta=="ok"){
            echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡El paralelo ha sido guardado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'paralelos/";
                }
            });
            
            </script>';
        }
    }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡No se admite caracteres especiales, intente nuevamente!",
              text: "Ejemplo (A)",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'paralelos/";
        
              }
            });
            
            </script>';
          }
        }


    }

/*=============================================
EDITAR Paralelos
=============================================*/
static public function ctrEditarParalelos(){
    if(isset($_POST["editarParalelo"])){
        if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["editarParalelo"])){
            $tabla ="edu_tab_paralelos";
            $datos=array("nombre"=>$_POST["editarParalelo"],
                    "id"=>$_POST["idParalelo"],"observacion"=>$_POST["editarObservacion"]);
            $respuesta=ModeloParalelos::mdlEditarParalelos($tabla,$datos);

            if($respuesta=="ok"){
                echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡El Paralelo ha sido modificado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'paralelos/";
                }
            });
            
            </script>';
            }
            


        }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡No se admite caracteres especiales, intente nuevamente!",
              text: "Ejemplo (A)",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'paralelos/";
        
              }
            });
            
            </script>';
        }


    }


}
/*=============================================
ELIMINAR Paralelos
=============================================*/
static public function ctrEliminarParalelos(){
    if(isset($_POST["idParalelo"]) && Seguridad::tokenValido()){
        $tabla="edu_tab_paralelos";
        $datos=$_POST["idParalelo"];
        $respuesta=ModeloParalelos::mdlEliminarParalelos($tabla,$datos);
    if($respuesta=="ok"){
        echo'<script>
            Swal.fire({
              icon: "success",
              title: "El Paralelo ha sido borrado correctamente",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'paralelos/";
        
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
                window.location="'.SERVERURL.'paralelos/";
              }
            });
            
            </script>';
    }

    }
}

/*=============================================
LISTAR Paralelos
=============================================*/
static public function ctrListarParalelos(){
  $tabla="edu_tab_paralelos";
  $respuesta=ModeloParalelos::mdlListarParalelos($tabla);
  return $respuesta;
}
}