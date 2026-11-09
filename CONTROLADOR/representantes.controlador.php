<?php
class ControladorRepresentantes{
  /*=============================================
LISTAR Grados
=============================================*/
static public function ctrListarRepresentante(){
  $tabla="edu_tab_representantes";
  $respuesta=ModeloRepresentantes::mdlListarRepresentante($tabla);
  return $respuesta;
}
/*========================================================================
CONTROLO DE INGRESO
==========================================================================*/
   public function ctrIngresoRepresentante(){
        if(isset($_POST["usuario"])){
            if(Seguridad::tipoUsuario($_POST["usuario"])=="R"){
            if(preg_match('/^[a-zA-Z0-9-]+$/',$_POST["usuario"])){
                $tabla="edu_tab_representantes";
                $item="usuario";
                $valor=$_POST["usuario"];
                $respuesta=ModeloRepresentantes::mdlMostrarRepresentantes($tabla,$item,$valor);
                if($respuesta && $respuesta["usuario"]==$_POST["usuario"] 
                && password_verify($_POST["password"],$respuesta["password"])){ 
           
                 $_SESSION["iniciarSesionR"]="ok";
                  $_SESSION["id"]=$respuesta["id"];
                   $_SESSION["nombre"]=$respuesta["nombres"];
                   $_SESSION["apellidos"]=$respuesta["apellidos"];
                   $_SESSION["usuario"]=$respuesta["usuario"];
                   $_SESSION["rol"]=$respuesta["rol"];
                   $_SESSION["foto"]=isset($respuesta["foto"]) ? $respuesta["foto"] : "";
                   /*NUEVO ID DE SESIÓN Y CONTROL DE CONTRASEÑA INICIAL (IGUAL AL USUARIO)*/
                   session_regenerate_id(true);
                   $_SESSION["tablaSesion"]=$tabla;
                   $_SESSION["cambiarPassword"]=password_verify($respuesta["usuario"],$respuesta["password"]);
    
                    echo '<script>
                    window.location="'.SERVERURL.'inicio/"
                    </script>';
                 
                  }else{
                    
                    echo'<br><div class="alert alert-danger">Contraseña o Usuario Incorrecto, vuelve a intentarlo</div>';
                  }
    
            }
        }
        }
    
    
    }
/*========================================================================
CONTROLADOR DE CREAR USUARIOS
==========================================================================*/
static public function ctrCrearRepresentante(){
    if(isset($_POST["nuevoNombreR"])){
      if(isset($_POST["nuevoUsuarioR"])){ $_POST["nuevoUsuarioR"] = Seguridad::normalizarUsuario($_POST["nuevoUsuarioR"]); }
        if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["nuevoNombreR"])&&
        preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["nuevoApellidoR"])){
          if(!Seguridad::cedulaValida($_POST["nuevaCedulaR"])){
            echo Seguridad::alerta("error", "¡Cédula no válida!", "Revise el número de cédula del representante.", "representantes/");
            return;
          }
          $contra=$_POST["nuevaPasswordR"];
          $encriptar=password_hash($contra,PASSWORD_DEFAULT );

       $tabla = "edu_tab_representantes";
        $datos = array("cedula"=>$_POST["nuevaCedulaR"],
        "nombres"=>$_POST["nuevoNombreR"],
        "apellidos"=>$_POST["nuevoApellidoR"],
        "genero"=>$_POST["nuevoGeneroR"],
        "email"=>$_POST["nuevoEmailR"],
        "telefono"=>$_POST["nuevoTelefonoR"],
        "direccion"=>$_POST["nuevaDireccionR"],
        "usuario"=>$_POST["nuevoUsuarioR"],
        "password"=>$encriptar,
        "rol"=>$_POST["nuevoRolR"]

      );

      $respuesta=ModeloRepresentantes::mdlCrearRepresentante($tabla,$datos);
      if($respuesta=="ok"){
        echo'<script>
        Swal.fire({
          icon: "success",
          title: "¡El representante ha sido guardado correctamente!",
          showConfirmButton: true,
          confirmButtonText: "Cerrar",
          closeOnConfirm:false
        }).then((result)=>{
          if(result.value){
            window.location="'.SERVERURL.'representantes/";
  
          }
        });
        
        </script>';
      }else{
        echo'<script>
        Swal.fire({
          icon: "error",
          title: "¡No se pudo guardar, intente nuevamente!",
          showConfirmButton: true,
          confirmButtonText: "Cerrar",
          closeOnConfirm:false
        }).then((result)=>{
          if(result.value){
            window.location="'.SERVERURL.'representantes/";
    
          }
        });
        
        </script>';
      }

        
        }else{
          echo'<script>
          Swal.fire({
            icon: "error",
            title: "¡El usuario no puede llevar caracteres especiales!",
            showConfirmButton: true,
            confirmButtonText: "Cerrar",
            closeOnConfirm:false
          }).then((result)=>{
            if(result.value){
              window.location="'.SERVERURL.'representantes/";
      
            }
          });
          
          </script>';
        }
      }
}
/*========================================================================
CONTROLO DE MOSTRAR Representantes
==========================================================================*/
static public function ctrMostrarRepresentantes($item,$valor){
  $tabla="edu_tab_representantes";
  $respuesta = ModeloRepresentantes::mdlMostrarRepresentantes($tabla,$item,$valor);
  return $respuesta;
}
/*========================================================================
CONTROL EDITAR Representantes REGISTRADOS EN LA BASE DE DATOS               
==========================================================================*/
public function ctrEditarRepresentante(){
  if(isset($_POST["editarNombreR"])){
    if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["editarNombreR"])&&
    preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["editarApellidoR"])){

   $tabla = "edu_tab_representantes";
   if($_POST["editarPassword"]!=""){
    $contra=$_POST["editarPassword"];
    $encriptar=password_hash($contra,PASSWORD_DEFAULT );

  }else{
    /*LA CONTRASEÑA ACTUAL SE LEE DE LA BASE, NO DEL FORMULARIO*/
    $actual = ModeloRepresentantes::mdlMostrarRepresentantes("edu_tab_representantes", "cedula", $_POST["editarCedulaR"]);
    $encriptar = $actual ? $actual["password"] : "";
  }
    $datos = array(  
      "cedula"=>$_POST["editarCedulaR"],
      "email"=>$_POST["editarEmailR"],
    "telefono"=>$_POST["editarTelefonoR"],
    "direccion"=>$_POST["editarDireccionR"],
    "password"=>$encriptar);
        
    $respuesta=ModeloRepresentantes::mdlEditarRepresentante($tabla,$datos);
    if($respuesta=="ok"){
      echo'<script>
      Swal.fire({
        icon: "success",
        title: "¡El representante ha sido modificado correctamente!",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        closeOnConfirm:false
      }).then((result)=>{
        if(result.value){
          window.location="'.SERVERURL.'representantes/";

        }
      });
      
      </script>';
    }
  }else{
      echo'<script>
      Swal.fire({
        icon: "error",
        title: "¡No puede llevar caracteres especiales!",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        closeOnConfirm:false
      }).then((result)=>{
        if(result.value){
          window.location="'.SERVERURL.'representantes/";
  
        }
      });
      
      </script>';

    
  }

}
}
static public function ctrBorrarRepresentante(){

  if(isset($_POST["idRepresentante"]) && Seguridad::tokenValido()){

    $tabla ="edu_tab_representantes";
    $datos = $_POST["idRepresentante"];

    $respuesta = ModeloRepresentantes::mdlBorrarRepresentante($tabla, $datos);

    if($respuesta == "ok"){

      echo'<script>

      Swal.fire({
          icon: "success",
          title: "El representante ha sido borrado correctamente",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
          }).then(function(result){
              if (result.value) {

              window.location = "'.SERVERURL.'representantes/";

              }
            })

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
              window.location="'.SERVERURL.'representantes/";
            }
          });
          
          </script>';
  }

  }

}

}