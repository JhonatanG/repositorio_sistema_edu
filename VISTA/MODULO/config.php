<?php
/* URL base calculada a partir de la petición, para que las imágenes y recursos
   funcionen igual en XAMPP (http://localhost:81/EDU/), Docker (http://localhost:8080/)
   o cualquier otro host/puerto/carpeta. */
if (!defined("SERVERURL")) {
    $esquema = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") ? "https" : "http";
    $host = isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : "localhost";
    $base = str_replace("\\", "/", dirname(isset($_SERVER["SCRIPT_NAME"]) ? $_SERVER["SCRIPT_NAME"] : "/index.php"));
    $base = preg_replace('#/ajax$#', '', $base);
    $base = rtrim($base, "/") . "/";
    define("SERVERURL", $esquema . "://" . $host . $base);
}
