<?php
class ControladorDocentes{
  /*========================================================================
LISTAR DOCENTES
==========================================================================*/
  static public function ctrListarDocentes(){
    $tabla="edu_tab_docentes";
    $respuesta=ModeloDocentes::mdlListarDocente($tabla);
    return $respuesta;
  }
/*========================================================================
CONTROLO DE INGRESO DE USUARIOS
==========================================================================*/
    public function ctrIngresoDocente(){
        if(isset($_POST["usuario"])){
            if(Seguridad::tipoUsuario($_POST["usuario"])=="D"){
            if(preg_match('/^[a-zA-Z0-9-]+$/',$_POST["usuario"])){
                $tabla="edu_tab_docentes";
                $item="usuario";
                $valor=$_POST["usuario"];
                $respuesta=ModeloDocentes::mdlMostrarDocentes($tabla,$item,$valor);
                if($respuesta && $respuesta["usuario"]==$_POST["usuario"] 
                && password_verify($_POST["password"],$respuesta["password"])){ 
                  if($respuesta["estado"]==1){
                 $_SESSION["iniciarSesionD"]="ok";
                  $_SESSION["id"]=$respuesta["id"];
                   $_SESSION["nombre"]=$respuesta["nombres"];
                   $_SESSION["apellidos"]=$respuesta["apellidos"];
                   $_SESSION["foto"]=$respuesta["foto"];
                   $_SESSION["usuario"]=$respuesta["usuario"];
                   $_SESSION["rol"]=$respuesta["rol"];
                   /*NUEVO ID DE SESIÓN Y CONTROL DE CONTRASEÑA INICIAL (IGUAL AL USUARIO)*/
                   session_regenerate_id(true);
                   $_SESSION["tablaSesion"]=$tabla;
                   $_SESSION["cambiarPassword"]=password_verify($respuesta["usuario"],$respuesta["password"]);
    
                    echo '<script>
                    window.location="'.SERVERURL.'inicio/"
                    </script>';
                  }else{
                    echo'<br><div class="alert alert-danger">Tú usuario no está activado. Comunicate con el Administrador.</div>';
                    
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
static public function ctrCrearDocentes(){
    if(isset($_POST["nuevoUsuarioD"])){
      $_POST["nuevoUsuarioD"] = Seguridad::normalizarUsuario($_POST["nuevoUsuarioD"]);
        if(preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["nuevoNombreD"])&&
        preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $_POST["nuevoApellidoD"])&&
        preg_match('/^[a-zA-Z0-9-]+$/', $_POST["nuevoUsuarioD"])){
      /*=============================================
      =   VALIDAR CÉDULA Y GUARDAR IMAGEN (JPG O PNG, MÁX 2MB)  =
      =============================================*/
      if(!Seguridad::cedulaValida($_POST["nuevaCedulaD"])){
        echo Seguridad::alerta("error", "¡Cédula no válida!", "Revise el número de cédula ingresado.", "docente/");
        return;
      }
      $contra=$_POST["nuevaPasswordD"];
      $encriptar=password_hash($contra,PASSWORD_DEFAULT );
      //password hash

       $tabla = "edu_tab_docentes";
        $datos = array("cedula"=>$_POST["nuevaCedulaD"],
        "nombres"=>$_POST["nuevoNombreD"],
        "apellidos"=>$_POST["nuevoApellidoD"],
        "genero"=>$_POST["nuevoGeneroD"],
        "email"=>$_POST["nuevoEmailD"],
        "telefono"=>$_POST["nuevoTelefonoD"],
        "direccion"=>$_POST["nuevaDireccionD"], 
        "fecha"=>$_POST["nuevaFechaD"],
        "usuario"=>$_POST["nuevoUsuarioD"],
        "password"=>$encriptar,
         "rol"=>$_POST["nuevoRolD"],
          "foto"=>"");

      $respuesta=ModeloDocentes::mdlCrearDocente($tabla,$datos);
      if($respuesta=="ok"){
        /*LA FOTO SE GUARDA EN LA BASE CUANDO EL DOCENTE YA TIENE ID*/
        Seguridad::guardarFotoNuevo(isset($_FILES["nuevaFotoD"]) ? $_FILES["nuevaFotoD"] : array(), "D", $_POST["nuevoUsuarioD"]);
        echo'<script>
        Swal.fire({
          icon: "success",
          title: "¡El/La docente ha sido guardado correctamente!",
          showConfirmButton: true,
          confirmButtonText: "Cerrar",
          closeOnConfirm:false
        }).then((result)=>{
          if(result.value){
            window.location="'.SERVERURL.'docente/";
  
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
            window.location="'.SERVERURL.'docente/";
    
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
              window.location="'.SERVERURL.'docente/";
      
            }
          });
          
          </script>';
        }
      }
}
/*========================================================================
CONTROLO DE MOSTRAR DOCENTES
==========================================================================*/
static public function ctrMostrarDocentes($item,$valor){
  $tabla="edu_tab_docentes";
  $respuesta = ModeloDocentes::mdlMostrarDocentes($tabla,$item,$valor);
  return $respuesta;
}
/*========================================================================
CONTROL EDITAR DOCENTES REGISTRADOS EN LA BASE DE DATOS               
==========================================================================*/
public function ctrEditarDocente(){
  if(isset($_POST["editarUsuarioD"])){
    if(preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{2,60}$/u', trim($_POST["editarNombreD"]))&&
    preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{2,60}$/u', trim($_POST["editarApellidoD"]))&&
    preg_match('/^[a-zA-Z0-9-]+$/', $_POST["editarUsuarioD"])){

      /*LA FOTO Y LA CONTRASEÑA ACTUALES SE LEEN DE LA BASE, NO DEL FORMULARIO*/
      $tabla = "edu_tab_docentes";
      $actual = ModeloDocentes::mdlMostrarDocentes($tabla, "usuario", $_POST["editarUsuarioD"]);
      if(!$actual){
        echo Seguridad::alerta("error", "¡No se pudo editar!", "El docente no existe.", "docente/");
        return;
      }
      $ruta = $actual["foto"];
      $nuevaRuta = Seguridad::guardarFoto(isset($_FILES["editarFotoD"]) ? $_FILES["editarFotoD"] : array(), "D", $actual["id"]);
      if($nuevaRuta != ""){
        $ruta = $nuevaRuta;
      }

    if($_POST["editarPassword"]!=""){
      $contra=$_POST["editarPassword"];
      $encriptar=password_hash($contra,PASSWORD_DEFAULT );

    }else{
      $encriptar=$actual["password"];
    }
    $datos = array(
    "nombres"=>trim($_POST["editarNombreD"]),
    "apellidos"=>trim($_POST["editarApellidoD"]),
    "email"=>$_POST["editarEmailD"],
    "telefono"=>$_POST["editarTelefonoD"],
    "direccion"=>$_POST["editarDireccionD"],
    "usuario"=>$_POST["editarUsuarioD"],
    "password"=>$encriptar,
      "foto"=>$ruta);
    
    $respuesta=ModeloDocentes::mdlEditarDocente($tabla,$datos);
    if($respuesta=="ok"){
      echo'<script>
      Swal.fire({
        icon: "success",
        title: "¡El/La docente ha sido modificado correctamente!",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        closeOnConfirm:false
      }).then((result)=>{
        if(result.value){
          window.location="'.SERVERURL.'docente/";

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
          window.location="'.SERVERURL.'docente/";
  
        }
      });
      
      </script>';

    
  }

}
}
static public function ctrBorrarDocente(){

  if(isset($_POST["idDocente"]) && Seguridad::tokenValido() && Seguridad::rol()=="Administrador"){

    $tabla ="edu_tab_docentes";
    $datos = $_POST["idDocente"];

    $respuesta = ModeloDocentes::mdlBorrarDocente($tabla, $datos);

    if($respuesta == "ok"){

      /*LA FOTO SOLO SE BORRA SI EL REGISTRO SE BORRÓ*/
      Seguridad::borrarFoto("D", $datos);

      echo'<script>

      Swal.fire({
          icon: "success",
          title: "El/La docente ha sido borrado correctamente",
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
          }).then(function(result){
              if (result.value) {

              window.location = "'.SERVERURL.'docente/";

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
              window.location="'.SERVERURL.'docente/";
            }
          });
          
          </script>';
  }

  }

}

}