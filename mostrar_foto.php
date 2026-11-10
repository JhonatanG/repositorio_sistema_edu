<?php
/*========================================================================
DEVUELVE UNA FOTO GUARDADA EN LA BASE DE DATOS
mostrar_foto.php?t=U|D|R|E&id=123&v=... (la "v" solo sirve para renovar la caché)
Solo con sesión iniciada: hay fotos de estudiantes menores de edad.
==========================================================================*/
require_once "CONTROLADOR/seguridad.php";
require_once "MODELO/fotos.modelo.php";

session_start();
$conSesion = Seguridad::sesionActiva();
session_write_close(); /*NO BLOQUEA LA SESIÓN MIENTRAS SE CARGAN VARIAS FOTOS*/

$tipo = isset($_GET["t"]) ? $_GET["t"] : "";
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$foto = ($conSesion && isset(ModeloFotos::tablas()[$tipo]) && $id > 0) ? ModeloFotos::mdlObtener($tipo, $id) : false;

if(!$foto){
  /*SIN FOTO O SIN PERMISO: IMAGEN POR DEFECTO*/
  header("Content-Type: image/png");
  header("Cache-Control: no-store");
  readfile(__DIR__."/VISTA/img/anonymous.png");
  exit;
}

header("Content-Type: ".(in_array($foto["mime"], array("image/jpeg","image/png")) ? $foto["mime"] : "application/octet-stream"));
header("Content-Length: ".strlen($foto["datos"]));
header("X-Content-Type-Options: nosniff");
/*CADA FOTO NUEVA TIENE OTRA "v", ASÍ QUE ESTA DIRECCIÓN NUNCA CAMBIA DE CONTENIDO*/
header("Cache-Control: private, max-age=31536000, immutable");
echo $foto["datos"];
