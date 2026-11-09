<?php
class ControladorAnio{
/*=============================================
MOSTRAR INFORMACION
=============================================*/
    static public function ctrMostrarAnio($item,$valor){
        $tabla="edu_tab_periodo";
        $respuesta= ModeloAnio::mdlMostrarAnio($tabla,$item,$valor);
        return $respuesta;
    }
    /*=============================================
Listar iNFORMACION
=============================================*/
static public function ctrListarAnio(){
  $tabla="edu_tab_periodo";
  $respuesta= ModeloAnio::mdlListarAnio($tabla);
  return $respuesta;
}
/*=============================================
CREAR ANIO
=============================================*/
    static public function ctrCrearAnio(){
        if(isset($_POST["nuevoAnio"])){
        if(preg_match('/^[0-9-]+$/', $_POST["nuevoAnio"])){
        $tabla="edu_tab_periodo";
        $tipo = isset($_POST["nuevoTipo"]) && Calificaciones::tipoValido($_POST["nuevoTipo"]) ? $_POST["nuevoTipo"] : "QUIMESTRAL";
        $datos=array("nombre" => $_POST["nuevoAnio"],"observacion"=>$_POST["nuevaObservacion"],"tipo"=>$tipo);
        $respuesta=ModeloAnio::mdlCrearAnio($tabla,$datos);
        if($respuesta=="ok"){
            echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡El Año Lectivo ha sido guardado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'anioL/";
                }
            });
            
            </script>';
        }
    }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡No se admite letras, intente nuevamente!",
              text: "Ejemplo (2020-2021)",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'anioL/";
        
              }
            });
            
            </script>';
          }
        }


    }

/*=============================================
EDITAR ANIO
=============================================*/
static public function ctrEditarAnio(){
    if(isset($_POST["editarAnio"])){
        if(preg_match('/^[0-9-]+$/', $_POST["editarAnio"])){
            $tabla ="edu_tab_periodo";
            $tipo = isset($_POST["editarTipo"]) && Calificaciones::tipoValido($_POST["editarTipo"]) ? $_POST["editarTipo"] : "QUIMESTRAL";
            /*NO SE PUEDE CAMBIAR EL TIPO DE EVALUACIÓN SI EL AÑO YA TIENE NOTAS*/
            $anterior = ModeloAnio::mdlMostrarAnio($tabla, "id", $_POST["idAnio"]);
            if($anterior && $anterior["tipo_evaluacion"] != $tipo && ModeloNotas::mdlPeriodoTieneNotas($_POST["idAnio"])){
                echo Seguridad::alerta("error", "¡No se puede cambiar la evaluación!", "Este año lectivo ya tiene notas registradas como ".strtolower(Calificaciones::config($anterior["tipo_evaluacion"])["etiqueta"]).". Puede editar el nombre y la observación.", "anioL/");
                return;
            }
            $datos=array("nombre"=>$_POST["editarAnio"],
                    "id"=>$_POST["idAnio"],"observacion"=>$_POST["editarObservacion"],"tipo"=>$tipo);
            $respuesta=ModeloAnio::mdlEditarAnio($tabla,$datos);

            if($respuesta=="ok"){
                echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡El Año Lectivo ha sido modificado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'anioL/";
                }
            });
            
            </script>';
            }
            


        }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡No se admite letras, intente nuevamente!",
              text: "Ejemplo (2020-2021)",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'anioL/";
        
              }
            });
            
            </script>';
        }


    }


}
/*=============================================
ELIMINAR ANIO
=============================================*/
static public function ctrEliminarAnio(){
    if(isset($_POST["idAnio"]) && Seguridad::tokenValido()){
        $tabla="edu_tab_periodo";
        $datos=$_POST["idAnio"];
        $respuesta=ModeloAnio::mdlEliminarAnio($tabla,$datos);
    if($respuesta=="ok"){
        echo'<script>
            Swal.fire({
              icon: "success",
              title: "El Año Lectivo ha sido borrado correctamente",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'anioL/";
        
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
                window.location="'.SERVERURL.'anioL/";
        
              }
            });
            
            </script>';
    }

    }
}
}