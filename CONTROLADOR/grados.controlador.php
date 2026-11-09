<?php
class ControladorGrados{
/*=============================================
MOSTRAR INFORMACION
=============================================*/
    static public function ctrMostrarGrados($item,$valor){
        $tabla="edu_tab_grado";
        $respuesta= ModeloGrados::mdlMostrarGrados($tabla,$item,$valor);
        return $respuesta;
    }
/*=============================================
CREAR Grados
=============================================*/
    static public function ctrCrearGrados(){
        if(isset($_POST["nuevoGrado"])){
        if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["nuevoGrado"])){
        $tabla="edu_tab_grado";
        $datos=array("nombre" => $_POST["nuevoGrado"],"observacion"=>$_POST["nuevaObservacion"],
        "orden"=>(isset($_POST["nuevoOrden"]) && $_POST["nuevoOrden"]!=="") ? (int)$_POST["nuevoOrden"] : null
       );
        $respuesta=ModeloGrados::mdlCrearGrados($tabla,$datos);
        if($respuesta=="ok"){
            echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡El grado ha sido guardado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'grados/";
                }
            });
            
            </script>';
        }
    }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡No se admite caracteres especiales, intente nuevamente!",
              text: "Ejemplo (Primero)",
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

/*=============================================
EDITAR Grados
=============================================*/
static public function ctrEditarGrados(){
    if(isset($_POST["editarGrado"])){
        if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["editarGrado"])){
            $tabla ="edu_tab_grado";
            $datos=array("nombre"=>$_POST["editarGrado"],
                    "id"=>$_POST["idGrado"],
                    "observacion"=>$_POST["editarObservacion"],
                    "orden"=>(isset($_POST["editarOrden"]) && $_POST["editarOrden"]!=="") ? (int)$_POST["editarOrden"] : null);
            $respuesta=ModeloGrados::mdlEditarGrados($tabla,$datos);

            if($respuesta=="ok"){
                echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡El grado ha sido modificado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'grados/";
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
                  window.location="'.SERVERURL.'grados/";
          
                }
              });
              
              </script>';
            }
            


        }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡No se admite caracteres especiales, intente nuevamente!",
              text: "Ejemplo (Primero)",
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
/*=============================================
ELIMINAR Grados
=============================================*/
static public function ctrEliminarGrados(){
    if(isset($_POST["idGrado"]) && Seguridad::tokenValido()){
        $tabla="edu_tab_grado";
        $datos=$_POST["idGrado"];
        $respuesta=ModeloGrados::mdlEliminarGrados($tabla,$datos);
    if($respuesta=="ok"){
        echo'<script>
            Swal.fire({
              icon: "success",
              title: "El Grado ha sido borrado correctamente",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'grados/";
        
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
/*=============================================
LISTAR Grados
=============================================*/
static public function ctrListarGrados(){
  $tabla="edu_tab_grado";
  $respuesta=ModeloGrados::mdlListarGrados($tabla);
  return $respuesta;
}
}