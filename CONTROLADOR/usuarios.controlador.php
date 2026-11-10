<?php
class ControladorUsuarios{
/*========================================================================
CONTROLO DE INGRESO DE USUARIOS
==========================================================================*/
public function ctrIngresoUsuario(){
    if(isset($_POST["usuario"])){
      if(Seguridad::tipoUsuario($_POST["usuario"])=="U"){
        if(preg_match('/^[a-zA-Z0-9]+$/',$_POST["usuario"])){
            $tabla="edu_tab_usuarios";
            $item="usuario";
            $valor=$_POST["usuario"];
            $respuesta=ModeloUsuarios::mdlMostrarUsuarios($tabla,$item,$valor);
            if($respuesta && $respuesta["usuario"]==$_POST["usuario"] 
            && password_verify($_POST["password"],$respuesta["password"])){ 
                if($respuesta["estado"]==1){
                $_SESSION["iniciarSesion"]="ok";
              $_SESSION["id"]=$respuesta["id"];
               $_SESSION["nombre"]=$respuesta["nombre"];
               $_SESSION["usuario"]=$respuesta["usuario"];
               $_SESSION["foto"]=$respuesta["foto"];
               $_SESSION["rol"]=$respuesta["rol"];
               /*NUEVO ID DE SESIÓN Y CONTROL DE CONTRASEÑA INICIAL (IGUAL AL USUARIO)*/
               session_regenerate_id(true);
               $_SESSION["tablaSesion"]=$tabla;
               $_SESSION["cambiarPassword"]=password_verify($respuesta["usuario"],$respuesta["password"]);
          /*=============================================
               =   REGISTRAR FECHA PARA SABER EL ULTIMO LOGIN  =
               =============================================*/
               date_default_timezone_set("America/Guayaquil");
               $fecha = date('Y-m-d');
               $hora = date('H:i:s');
               $fechaActual=$fecha.' '.$hora;
               $item1="ultimo_login";
               $valor1 = $fechaActual;

               $item2="id";
               $valor2=$respuesta["id"];
               $ultimoLogin = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2);

               if($ultimoLogin == "ok"){
                echo '<script>
                window.location="'.SERVERURL.'inicio/"
                </script>';
                
               }


              }else{
                echo'<br><div class="alert alert-danger">El usuario aún no está activado, Comunicate con el Administrador</div>';
                
              }
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
static public function ctrCrearUsuarios(){
    if(isset($_POST["nuevoUsuario"])){
        if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["nuevoNombre"])&&
        preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoUsuario"])){
      /*=============================================
      =   VALIDAR Y GUARDAR IMAGEN (JPG O PNG, MÁX 2MB)  =
      =============================================*/
      if(!Seguridad::passwordValida($_POST["nuevoPassword"])){
        echo Seguridad::alerta("error", "¡Contraseña muy débil!", "Use al menos 8 caracteres, con letras y números.", "usuarios/");
        return;
      }
      $contra=$_POST["nuevoPassword"];
      $encriptar=password_hash($contra,PASSWORD_DEFAULT );
      //password hash
      
      
       $tabla = "edu_tab_usuarios";
        $datos = array("nombre"=>$_POST["nuevoNombre"],
                  "usuario"=>$_POST["nuevoUsuario"],
                  "password"=>$encriptar,
                  "rol"=>$_POST["nuevoRol"],
                   "foto"=>"");
        $respuesta=ModeloUsuarios::mdlCrearUsuario($tabla,$datos);
          if($respuesta=="ok"){
            /*LA FOTO SE GUARDA EN LA BASE CUANDO EL USUARIO YA TIENE ID*/
            Seguridad::guardarFotoNuevo(isset($_FILES["nuevaFoto"]) ? $_FILES["nuevaFoto"] : array(), "U", $_POST["nuevoUsuario"]);
            echo'<script>
            Swal.fire({
              icon: "success",
              title: "¡El usuario ha sido guardado correctamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'usuarios/";
      
              }
            });
            
            </script>';
          }else{
            echo'<script>
            Swal.fire({
              icon: "error",
              title: "¡No se pudo guardar usuario, intente nuevamente!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar",
              closeOnConfirm:false
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'usuarios/";
        
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
              window.location="'.SERVERURL.'usuarios/";
      
            }
          });
          
          </script>';
        }
      }
}
/*========================================================================
CONTROLO DE MOSTRAR USUARIOS
==========================================================================*/
static public function ctrMostrarUsuarios($item,$valor){
    $tabla="edu_tab_usuarios";
    $respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla,$item,$valor);

    return $respuesta;

}
/*========================================================================
CONTROL EDITAR USUARIOS REGISTRADOS EN LA BASE DE DATOS               
==========================================================================*/
public function ctrEditarUsuario(){
    if(isset($_POST["editarUsuario"])){
      if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["editarNombre"])){
  
        /*LA FOTO Y LA CONTRASEÑA ACTUALES SE LEEN DE LA BASE, NO DEL FORMULARIO*/
        $tabla = "edu_tab_usuarios";
        $actual = ModeloUsuarios::mdlMostrarUsuarios($tabla, "usuario", $_POST["editarUsuario"]);
        if(!$actual){
          echo Seguridad::alerta("error", "¡No se pudo editar!", "El usuario no existe.", "usuarios/");
          return;
        }
        $ruta = $actual["foto"];
        $nuevaRuta = Seguridad::guardarFoto(isset($_FILES["editarFoto"]) ? $_FILES["editarFoto"] : array(), "U", $actual["id"]);
        if($nuevaRuta != ""){
          $ruta = $nuevaRuta;
        }

      if($_POST["editarPassword"]!=""){
        $contra=$_POST["editarPassword"];
        $encriptar=password_hash($contra,PASSWORD_DEFAULT );
  
      }else{
        $encriptar=$actual["password"];
      }
      $datos = array("nombre"=>$_POST["editarNombre"],
              "usuario"=>$_POST["editarUsuario"],
              "password"=>$encriptar,
              "rol"=>$_POST["editarRol"],
               "foto"=>$ruta);
      
      $respuesta=ModeloUsuarios::mdlEditarUsuario($tabla,$datos);
      if($respuesta=="ok"){
        echo'<script>
        Swal.fire({
          icon: "success",
          title: "¡El usuario ha sido modificado correctamente!",
          showConfirmButton: true,
          confirmButtonText: "Cerrar",
          closeOnConfirm:false
        }).then((result)=>{
          if(result.value){
            window.location="'.SERVERURL.'usuarios/";
  
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
            window.location="'.SERVERURL.'usuarios/";
    
          }
        });
        
        </script>';
  
      
    }
  
  }
  }
  static public function ctrBorrarUsuario(){

    if(isset($_POST["idUsuario"]) && Seguridad::tokenValido()){
  
      $tabla ="edu_tab_usuarios";
      $datos = $_POST["idUsuario"];

      /*NO SE PERMITE BORRAR EL USUARIO CON EL QUE SE INICIÓ SESIÓN*/
      if($datos == $_SESSION["id"] && isset($_SESSION["iniciarSesion"])){
        echo Seguridad::alerta("warning", "¡No se pudo borrar!", "No puede borrar el usuario con el que inició sesión.", "usuarios/");
        return;
      }

      $respuesta = ModeloUsuarios::mdlBorrarUsuario($tabla, $datos);

      if($respuesta == "ok"){

        /*LA FOTO SOLO SE BORRA SI EL REGISTRO SE BORRÓ*/
        Seguridad::borrarFoto("U", $datos);
  
        echo'<script>
  
        Swal.fire({
            icon: "success",
            title: "El usuario ha sido borrado correctamente",
            showConfirmButton: true,
            confirmButtonText: "Cerrar"
            }).then(function(result){
                if (result.value) {
  
                window.location = "'.SERVERURL.'usuarios/";
  
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
              confirmButtonText: "Cerrar"
            }).then((result)=>{
              if(result.value){
                window.location="'.SERVERURL.'usuarios/";
              }
            });
            </script>';
      }

    }

  }

/*========================================================================
CAMBIAR LA CONTRASEÑA PROPIA (OBLIGATORIO SI AÚN ES LA INICIAL)
==========================================================================*/
static public function ctrCambiarPassword(){
  if(!isset($_POST["nuevaPasswordPropia"])){
    return;
  }
  $volver = "cambiarPassword/";
  if(!Seguridad::tokenValido()){
    echo Seguridad::alerta("error", "¡Sesión caducada!", "Recargue la página e intente de nuevo.", $volver);
    return;
  }
  $tablas = array("edu_tab_usuarios","edu_tab_docentes","edu_tab_representantes","edu_tab_estudiantes");
  $tabla = isset($_SESSION["tablaSesion"]) ? $_SESSION["tablaSesion"] : "";
  if(!in_array($tabla, $tablas)){
    echo Seguridad::alerta("error", "¡No se pudo cambiar!", "Vuelva a iniciar sesión e intente de nuevo.", "salir/");
    return;
  }
  $nueva = $_POST["nuevaPasswordPropia"];
  $usuario = ModeloUsuarios::mdlMostrarUsuarios($tabla, "id", $_SESSION["id"]);
  if(!$usuario || !password_verify($_POST["passwordAnterior"], $usuario["password"])){
    echo Seguridad::alerta("error", "¡Contraseña actual incorrecta!", "Escriba la contraseña con la que inició sesión.", $volver);
    return;
  }
  if($nueva !== $_POST["confirmarPasswordPropia"]){
    echo Seguridad::alerta("error", "¡Las contraseñas no coinciden!", "Escriba la misma contraseña nueva en los dos campos.", $volver);
    return;
  }
  if(!Seguridad::passwordValida($nueva) || strcasecmp($nueva, $usuario["usuario"])==0){
    echo Seguridad::alerta("error", "¡Contraseña muy débil!", "Use al menos 8 caracteres, con letras y números, y distinta a su usuario.", $volver);
    return;
  }
  $respuesta = ModeloUsuarios::mdlActualizarUsuario($tabla, "password", password_hash($nueva, PASSWORD_DEFAULT), "id", $_SESSION["id"]);
  if($respuesta == "ok"){
    $_SESSION["cambiarPassword"] = false;
    echo Seguridad::alerta("success", "¡Contraseña actualizada!", "Desde ahora ingrese con su nueva contraseña.", "inicio/");
  }else{
    echo Seguridad::alerta("error", "¡No se pudo cambiar!", Conexion::$ultimoError != "" ? Conexion::$ultimoError : "Intente nuevamente.", $volver);
  }
}

}
