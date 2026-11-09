<?php
/*========================================================================
MI PERFIL: CADA USUARIO CAMBIA SU FOTO Y SUS DATOS DE CONTACTO
La tabla y la carpeta salen del rol de la sesión, nunca del formulario.
==========================================================================*/
class ControladorPerfil{

static public function config(){
  $rol = Seguridad::rol();
  $mapa = array(
    "Administrador" => array("tabla"=>"edu_tab_usuarios", "carpeta"=>"VISTA/dist/img/usuarios", "campos"=>array("nombre")),
    "Secretaría"    => array("tabla"=>"edu_tab_usuarios", "carpeta"=>"VISTA/dist/img/usuarios", "campos"=>array("nombre")),
    "Docente"       => array("tabla"=>"edu_tab_docentes", "carpeta"=>"VISTA/img/docentes", "campos"=>array("email","telefono","direccion")),
    "Representante" => array("tabla"=>"edu_tab_representantes", "carpeta"=>"VISTA/img/representantes", "campos"=>array("email","telefono","direccion")),
    "Estudiante"    => array("tabla"=>"edu_tab_estudiantes", "carpeta"=>"VISTA/img/estudiantes", "campos"=>array("email","telefono","direccion"))
  );
  return isset($mapa[$rol]) ? $mapa[$rol] : null;
}

static public function datos(){
  $cfg = self::config();
  return $cfg ? ModeloPerfil::mdlObtener($cfg["tabla"], $_SESSION["id"]) : null;
}

/*=============================================
CAMBIAR O QUITAR LA FOTO
=============================================*/
static public function ctrFoto(){
  if(!isset($_POST["accionFoto"])){
    return;
  }
  $cfg = self::config();
  if(!$cfg || !Seguridad::tokenValido()){
    echo Seguridad::alerta("error", "¡Sesión caducada!", "Recargue la página e intente de nuevo.", "perfil/");
    return;
  }
  $actual = ModeloPerfil::mdlObtener($cfg["tabla"], $_SESSION["id"]);
  $fotoAnterior = $actual && isset($actual["foto"]) ? $actual["foto"] : "";

  if($_POST["accionFoto"] == "quitar"){
    if(ModeloPerfil::mdlActualizarFoto($cfg["tabla"], $_SESSION["id"], "") == "ok"){
      Seguridad::borrarFoto($fotoAnterior, $cfg["carpeta"]);
      $_SESSION["foto"] = "";
      echo Seguridad::alerta("success", "¡Foto quitada!", "Se mostrará la imagen por defecto.", "perfil/");
    }
    return;
  }

  $ruta = Seguridad::guardarFoto(isset($_FILES["fotoPerfil"]) ? $_FILES["fotoPerfil"] : array(), $cfg["carpeta"]."/".basename($actual["usuario"]));
  if($ruta == ""){
    echo Seguridad::alerta("error", "¡Foto no válida!", "Elija una imagen JPG o PNG de máximo 2 MB.", "perfil/");
    return;
  }
  if(ModeloPerfil::mdlActualizarFoto($cfg["tabla"], $_SESSION["id"], $ruta) == "ok"){
    if($fotoAnterior != $ruta){
      Seguridad::borrarFoto($fotoAnterior, $cfg["carpeta"]);
    }
    $_SESSION["foto"] = $ruta;
    echo Seguridad::alerta("success", "¡Foto actualizada!", "Su nueva foto ya se muestra en el sistema.", "perfil/");
  }else{
    Seguridad::borrarFoto($ruta, $cfg["carpeta"]);
    echo Seguridad::alerta("error", "¡No se pudo guardar!", Conexion::$ultimoError, "perfil/");
  }
}

/*=============================================
ACTUALIZAR DATOS DE CONTACTO (O EL NOMBRE, PARA USUARIOS DEL SISTEMA)
=============================================*/
static public function ctrDatos(){
  if(!isset($_POST["actualizarPerfil"])){
    return;
  }
  $cfg = self::config();
  if(!$cfg || !Seguridad::tokenValido()){
    echo Seguridad::alerta("error", "¡Sesión caducada!", "Recargue la página e intente de nuevo.", "perfil/");
    return;
  }
  $datos = array();
  foreach($cfg["campos"] as $campo){
    $datos[$campo] = trim(isset($_POST[$campo]) ? $_POST[$campo] : "");
  }
  if(isset($datos["nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{3,60}$/u', $datos["nombre"])){
    echo Seguridad::alerta("error", "¡Nombre no válido!", "Use solo letras (3 a 60 caracteres).", "perfil/");
    return;
  }
  if(isset($datos["email"]) && $datos["email"] != "" && !filter_var($datos["email"], FILTER_VALIDATE_EMAIL)){
    echo Seguridad::alerta("error", "¡Correo no válido!", "Revise el correo electrónico.", "perfil/");
    return;
  }
  if(isset($datos["telefono"]) && $datos["telefono"] != "" && !preg_match('/^[0-9 +()-]{7,20}$/', $datos["telefono"])){
    echo Seguridad::alerta("error", "¡Teléfono no válido!", "Use solo números, espacios o guiones.", "perfil/");
    return;
  }
  if(isset($datos["direccion"]) && mb_strlen($datos["direccion"]) > 150){
    echo Seguridad::alerta("error", "¡Dirección muy larga!", "Use máximo 150 caracteres.", "perfil/");
    return;
  }
  if(ModeloPerfil::mdlActualizarDatos($cfg["tabla"], $_SESSION["id"], $datos) == "ok"){
    if(isset($datos["nombre"])){
      $_SESSION["nombre"] = $datos["nombre"];
    }
    echo Seguridad::alerta("success", "¡Datos actualizados!", "Su perfil quedó guardado.", "perfil/");
  }else{
    echo Seguridad::alerta("error", "¡No se pudo guardar!", Conexion::$ultimoError, "perfil/");
  }
}
}
