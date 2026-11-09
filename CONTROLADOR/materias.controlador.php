<?php
class ControladorMaterias{
/*=============================================
MOSTRAR INFORMACION
=============================================*/
    static public function ctrMostrarMaterias($item,$valor){
        $tabla="edu_tab_materias";
        $respuesta= ModeloMaterias::mdlMostrarMaterias($tabla,$item,$valor);
        return $respuesta;
    }
/*=============================================
CREAR Materias
=============================================*/
    static public function ctrCrearMaterias(){
        if(isset($_POST["nuevaMateria"])){
        if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["nuevaMateria"])){
        $tabla="edu_tab_materias";
        $datos=array("nombre" => $_POST["nuevaMateria"],"observacion"=>$_POST["nuevaObservacion"]);
        $respuesta=ModeloMaterias::mdlCrearMaterias($tabla,$datos);
        if($respuesta=="ok"){
            echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡La materia ha sido guardado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'materias/";
                }
            });
            
            </script>';
        }
    }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡No se caracteres especiales, intente nuevamente!",
              text: "Ejemplo (Ciencias Sociales)",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'materias/";
        
              }
            });
            
            </script>';
          }
        }


    }

/*=============================================
EDITAR Materias
=============================================*/
static public function ctrEditarMaterias(){
    if(isset($_POST["editarMateria"])){
        if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["editarMateria"])){
            $tabla ="edu_tab_materias";
            $datos=array("nombre"=>$_POST["editarMateria"],
                    "id"=>$_POST["idMateria"],"observacion"=>$_POST["editarObservacion"]);
            $respuesta=ModeloMaterias::mdlEditarMaterias($tabla,$datos);

            if($respuesta=="ok"){
                echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡La materia ha sido modificado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'materias/";
                }
            });
            
            </script>';
            }
            


        }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡No se admite caracteres especiales, intente nuevamente!",
              text: "Ejemplo (Ciencias Sociales)",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'materias/";
        
              }
            });
            
            </script>';
        }


    }


}
/*=============================================
ELIMINAR Materias
=============================================*/
static public function ctrEliminarMaterias(){
    if(isset($_POST["idMateria"]) && Seguridad::tokenValido()){
        $tabla="edu_tab_materias";
        $datos=$_POST["idMateria"];
        $respuesta=ModeloMaterias::mdlEliminarMaterias($tabla,$datos);
    if($respuesta=="ok"){
        echo'<script>
            Swal.fire({
              icon: "success",
              title: "La materia ha sido borrado correctamente",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'materias/";
        
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
                window.location="'.SERVERURL.'materias/";
              }
            });
            
            </script>';
    }

    }
}
/*=============================================
LISTAR MATERIAS
=============================================*/
static public function ctrListarMaterias(){
  $tabla="edu_tab_materias";
  $respuesta=ModeloMaterias::mdlListarMaterias($tabla);
  return $respuesta;
}
}