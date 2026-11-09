<?php
class ControladorDocg{
  /*=============================================
MOSTRAR INFORMACION
=============================================*/
static public function ctrMostrarDocg($item,$valor){
  $tabla="edu_tab_grad_docente";
  $respuesta=ModeloDocg::mdlMostrarDocg($tabla,$item,$valor);
  return $respuesta;
}
/*=============================================
LISTAR GRADO CON PARALELO
=============================================*/
static public function ctrListarGradosParalelos(){
  $respuesta=ModeloDocg::mdlListarGradoParalelo();
  //var_dump($respuesta);
  return $respuesta;
}
/*=============================================
GUARDAR INFORMACION
=============================================*/
static public function ctrCrearDocg(){
  if(!isset($_POST["idDocenteD"]) || !isset($_POST["idNivel"])){
    return;
  }
  $paralelos = isset($_POST["idGrados"]) ? (array)$_POST["idGrados"] : array();
  $materias = isset($_POST["idMateria"]) ? (array)$_POST["idMateria"] : array();
  if($_POST["idDocenteD"]=="" || empty($paralelos) || empty($materias)){
    echo Seguridad::alerta("error", "¡Faltan datos!", "Seleccione el docente, al menos un paralelo y al menos una asignatura.", "docgrado/");
    return;
  }
  $tabla = "edu_tab_grad_docente";
  $creadas = 0;
  $omitidas = array();
  foreach($paralelos as $gp){
    foreach($materias as $m){
      /*UNA ASIGNATURA DE UN PARALELO SOLO PUEDE TENER UN DOCENTE*/
      $existente = ModeloDocg::mdlClaseAsignada($gp, $m);
      if($existente){
        $materia = ModeloMaterias::mdlMostrarMaterias("edu_tab_materias", "id", $m);
        $omitidas[] = ModeloDocg::mdlNombreCurso($gp)." - ".($materia ? $materia["nombre"] : "").": ya la tiene ".$existente["docente"];
        continue;
      }
      $datos = array("idDocenteD"=>$_POST["idDocenteD"], "idGrados"=>$gp, "observacion"=>$_POST["nuevaObservacion"], "estado"=>1, "idMaterias"=>$m);
      if(ModeloDocg::mdlCrearDocg($tabla, $datos)=="ok"){
        $creadas++;
      }else{
        $omitidas[] = ModeloDocg::mdlNombreCurso($gp).": ".Conexion::$ultimoError;
      }
    }
  }
  $texto = "Se crearon ".$creadas." clase(s).";
  if(!empty($omitidas)){
    $texto .= " No se crearon: ".implode("; ", $omitidas).".";
  }
  echo Seguridad::alerta($creadas > 0 ? (empty($omitidas) ? "success" : "warning") : "error",
    $creadas > 0 ? "¡Distributivo guardado!" : "¡No se crearon clases!", $texto, "docgrado/");
}
/*=============================================
ELIMINAR INFORMACION
=============================================*/
static public function ctrEliminarDocgrados(){
  if(isset($_POST["idDocgrado"]) && Seguridad::tokenValido()){
      $tabla="edu_tab_grad_docente";
      $datos=$_POST["idDocgrado"];
      $respuesta=ModeloDocg::mdlEliminarDocgrados($tabla,$datos);
  if($respuesta=="ok"){
      echo'<script>
          Swal.fire({
            icon: "success",
            title: "La relación docente-grado ha sido borrado correctamente",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
          }).then((result)=>{
            if(result.value){
              window.location="'.SERVERURL.'docgrado/";
      
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
              window.location="'.SERVERURL.'docgrado/";
            }
          });
          
          </script>';
  }

  }
}
/*=============================================
EDITAR Informacion
=============================================*/
static public function ctrEditarDocgrado(){
  if(isset($_POST["editarObservacion"])){
      if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["editarObservacion"])){
          $tabla ="edu_tab_grad_docente";
          $datos=array(
                  "id"=>$_POST["editarDocgrado"],
                  "observacion"=>$_POST["editarObservacion"]);
          $respuesta=ModeloDocg::mdlEditarDocgrado($tabla,$datos);

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
              window.location = "'.SERVERURL.'docgrado/";
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
                window.location="'.SERVERURL.'docgrado/";
        
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
              window.location="'.SERVERURL.'docgrado/";
      
            }
          });
          
          </script>';
      }


  }


}
}