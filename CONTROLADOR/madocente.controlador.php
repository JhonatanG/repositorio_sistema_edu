<?php

class ControladorMdocentes{
/*=============================================
MOSTRAR INFORMACION
=============================================*/
static public function ctrMostrarMdocentes($item,$valor){
    $tabla="edu_tab_materias_docentes";
    $respuesta=ModeloMdocentes::mdlMostrarMdocentes($tabla,$item,$valor);
    return $respuesta;
}
/*=============================================
MOSTRAR INFORMACION
=============================================*/
static public function ctrMostrarRepetidos(){
  if(isset($_POST["idDocenteD"])){
    if(isset($_POST["idMateria"]) && is_array($_POST["idMateria"])){
    $tabla="edu_tab_materias_docentes";  
    $idMateria=$_POST["idMateria"];
    foreach ($idMateria as $value) {
        $datos=array("idDocenteD" => $_POST["idDocenteD"]
        ,"idMateria"=>$value);
        $respuesta=ModeloMdocentes::mdlMostrarRepetidos($tabla,$datos);
        var_dump($respuesta);
    }
    if(empty($respuesta)){
        echo'<script>
        Swal.fire({
          icon: "success",
          title: "¡ERROR!",
          showConfirmButton: true,
          confirmButtonText: "Cerrar",
          closeOnConfirm:false
        }).then(function(result){
            if (result.value) {
            window.location = "'.SERVERURL.'madocente/";
            }
        });
        
        </script>';
    }else{
      echo'<script>
      Swal.fire({
        icon: "error",
        title: "¡ERROR!",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        closeOnConfirm:false
      }).then(function(result){
          if (result.value) {
          window.location = "'.SERVERURL.'madocente/";
          }
      });
      
      </script>';
    }

  //return $respuesta;
}
  }
}
/*=============================================
GUARDAR INFORMACION
=============================================*/
    static public function ctrCrearMdocentes(){

        if(isset($_POST["idDocenteD"])){
            if(isset($_POST["idMateria"]) && is_array($_POST["idMateria"])){
            $tabla="edu_tab_materias_docentes";  
            $idMateria=$_POST["idMateria"];
     
            foreach ($idMateria as $value) {
              $datos=array("idDocenteD" => $_POST["idDocenteD"]
              ,"idMateria"=>$value);
              $respuesta=ModeloMdocentes::mdlMostrarRepetidos($tabla,$datos);
             // var_dump($respuesta);
          }
            if(empty($respuesta)){
              foreach ($idMateria as $value) {
                $datos=array("idDocenteD" => $_POST["idDocenteD"]
                ,"idMateria"=>$value,"observacion"=>$_POST["nuevaObservacion"],
                "estado"=>$_POST["nuevoEstado"]);
                $respuesta=ModeloMdocentes::mdlCrearMdocentes($tabla,$datos);
                //var_dump($respuesta);
            }
            if ($respuesta=="ok"){
                echo'<script>
                Swal.fire({
                  icon: "success",
                  title: "¡La relación docente-materia ha sido guardado correctamente!",
                  showConfirmButton: true,
                  confirmButtonText: "Cerrar",
                  closeOnConfirm:false
                }).then(function(result){
                    if (result.value) {
                    window.location = "'.SERVERURL.'madocente/";
                    }
                });
                
                </script>';
            }else {
                echo'<script>
                Swal.fire({
                  icon: "error",
                  title: "¡La relación docente-materia NO ha sido guardado correctamente!",
                  showConfirmButton: true,
                  confirmButtonText: "Cerrar",
                  closeOnConfirm:false
                }).then(function(result){
                    if (result.value) {
                    window.location = "'.SERVERURL.'madocente/";
                    }
                });
                
                </script>';
            }
          }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡Ya existe relación entre el docente con la materia!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then(function(result){
                if (result.value) {
                window.location = "'.SERVERURL.'madocente/";
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
                window.location = "'.SERVERURL.'madocente/";
                }
            });
            
            </script>';
        }
        }

    }
/*=============================================
ELIMINAR INFORMACION
=============================================*/
static public function ctrEliminarMdocentes(){
    if(isset($_POST["idMdocente"]) && Seguridad::tokenValido()){
        $tabla="edu_tab_materias_docentes";
        $datos=$_POST["idMdocente"];
        $respuesta=ModeloMdocentes::mdlEliminarMdocentes($tabla,$datos);
    if($respuesta=="ok"){
        echo'<script>
            Swal.fire({
              icon: "success",
              title: "La relación docente-materia ha sido borrado correctamente",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'madocente/";
        
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
                window.location="'.SERVERURL.'madocente/";
              }
            });
            
            </script>';
    }

    }
}
/*=============================================
EDITAR Informacion
=============================================*/
static public function ctrEditarMdocente(){
  if(isset($_POST["editarObservacion"])){
      if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["editarObservacion"])){
          $tabla ="edu_tab_materias_docentes";
          $datos=array(
                  "id"=>$_POST["editarMdocente"],
                  "observacion"=>$_POST["editarObservacion"]);
          $respuesta=ModeloMdocentes::mdlEditarMdocente($tabla,$datos);

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
              window.location = "'.SERVERURL.'madocente/";
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
                window.location="'.SERVERURL.'madocente/";
        
              }
            });
            
            </script>';
          }
          


      }else{
          echo'<script>
          Swal.fire({
            icon: "error",
            title: "¡No se admite caracteres especiales, intente nuevamente!",
            text: "No puedo ingresar campos vacios",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
            closeOnConfirm:false
          }).then((result)=>{
            if(result.value){
              window.location="'.SERVERURL.'madocente/";
      
            }
          });
          
          </script>';
      }


  }


}

    
}