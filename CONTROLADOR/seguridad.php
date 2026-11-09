<?php
/*========================================================================
FUNCIONES DE SEGURIDAD COMPARTIDAS POR TODO EL SISTEMA
==========================================================================*/
class Seguridad{

/*=============================================
SESIÓN Y ROLES
=============================================*/
static public function sesionActiva(){
  foreach(array("iniciarSesion","iniciarSesionD","iniciarSesionR","iniciarSesionE") as $clave){
    if(isset($_SESSION[$clave]) && $_SESSION[$clave]=="ok"){
      return true;
    }
  }
  return false;
}

/*=============================================
USUARIOS DE DOCENTES, REPRESENTANTES Y ESTUDIANTES: LETRA + CÉDULA (D1712345014)
Se acepta también el formato anterior con guion (D-1712345014).
=============================================*/
static public function normalizarUsuario($usuario){
  $usuario = trim($usuario);
  if(preg_match('/^([DRE])-?([0-9]{6,})$/i', $usuario, $m)){
    return strtoupper($m[1]).$m[2];
  }
  return $usuario;
}

/* "D" docente, "R" representante, "E" estudiante, "U" usuario del sistema (administrador o secretaría) */
static public function tipoUsuario($usuario){
  return preg_match('/^([DRE])[0-9]{6,}$/', self::normalizarUsuario($usuario), $m) ? $m[1] : "U";
}

static public function rol(){
  return isset($_SESSION["rol"]) ? $_SESSION["rol"] : "";
}

static public function tieneRol($roles){
  return self::sesionActiva() && in_array(self::rol(), $roles);
}

/* Para los archivos ajax: corta la petición si no hay sesión o el rol no está permitido */
static public function exigirAjax($roles){
  if(session_status() !== PHP_SESSION_ACTIVE){
    session_start();
  }
  $tokenAjax = isset($_SERVER["HTTP_X_TOKEN"]) ? $_SERVER["HTTP_X_TOKEN"] : "";
  if(!self::tieneRol($roles) || empty($_SESSION["token"]) || !hash_equals($_SESSION["token"], $tokenAjax)){
    http_response_code(403);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(array("error"=>"No autorizado"));
    exit;
  }
}

/* Quita el hash de la contraseña de una fila (o lista de filas) antes de enviarla al navegador */
static public function sinPassword($datos){
  if(!is_array($datos)){
    return $datos;
  }
  if(isset($datos["password"])){
    $hash = $datos["password"];
    foreach($datos as $clave=>$valor){
      if($valor===$hash){
        unset($datos[$clave]);
      }
    }
    return $datos;
  }
  foreach($datos as $clave=>$fila){
    $datos[$clave] = self::sinPassword($fila);
  }
  return $datos;
}

/*=============================================
RECORDAR LOS FILTROS DE UNA PÁGINA (GRADO, MATERIA...) PARA QUE NO SE
PIERDAN AL GUARDAR NOTAS O ASISTENCIA Y VOLVER A CARGAR LA PÁGINA
=============================================*/
static public function recordarFiltros($pagina, $campos){
  /*EL DOCENTE SOLO PUEDE CONSULTAR SUS PROPIAS CLASES*/
  if(self::rol()=="Docente"){
    $_POST["idDocenteG"] = $_SESSION["id"];
  }
  $completos = true;
  foreach($campos as $c){
    if(!isset($_POST[$c]) || $_POST[$c]===""){
      $completos = false;
    }
  }
  if($completos){
    foreach($campos as $c){
      $_SESSION["filtros"][$pagina][$c] = $_POST[$c];
    }
  }else if(isset($_SESSION["filtros"][$pagina])){
    foreach($_SESSION["filtros"][$pagina] as $c=>$v){
      if(!isset($_POST[$c]) || $_POST[$c]===""){
        $_POST[$c] = $v;
      }
    }
  }
}

/*=============================================
TOKEN CONTRA PETICIONES FALSIFICADAS (CSRF)
=============================================*/
static public function token(){
  if(empty($_SESSION["token"])){
    $_SESSION["token"] = bin2hex(random_bytes(32));
  }
  return $_SESSION["token"];
}

static public function campoToken(){
  return '<input type="hidden" name="token" value="'.self::token().'">';
}

static public function tokenValido(){
  return isset($_POST["token"], $_SESSION["token"]) && hash_equals($_SESSION["token"], $_POST["token"]);
}

/*=============================================
FOTOS: GUARDAR Y BORRAR DE FORMA SEGURA
=============================================*/
/* Guarda la imagen subida en $carpeta redimensionada a 500x500.
   Devuelve la ruta guardada, o "" si no se subió una imagen válida (JPG o PNG, máximo 2MB). */
static public function guardarFoto($archivo, $carpeta){
  if(!isset($archivo["tmp_name"]) || $archivo["tmp_name"]=="" || $archivo["error"]!==UPLOAD_ERR_OK){
    return "";
  }
  if($archivo["size"] > 2000000){
    return "";
  }
  $info = @getimagesize($archivo["tmp_name"]);
  if($info===false || !in_array($info[2], array(IMAGETYPE_JPEG, IMAGETYPE_PNG))){
    return "";
  }
  if(!is_dir($carpeta)){
    mkdir($carpeta, 0755, true);
  }
  /*RECORTE CUADRADO AL CENTRO PARA QUE LA FOTO NO SE DEFORME*/
  list($ancho, $alto) = $info;
  $lado = min($ancho, $alto);
  $x = (int)(($ancho - $lado) / 2);
  $y = (int)(($alto - $lado) / 2);
  $destino = imagecreatetruecolor(500, 500);
  $aleatorio = date("YmdHis").mt_rand(10,99);
  if($info[2]==IMAGETYPE_JPEG){
    $origen = imagecreatefromjpeg($archivo["tmp_name"]);
    imagecopyresampled($destino,$origen,0,0,$x,$y,500,500,$lado,$lado);
    $ruta = $carpeta."/".$aleatorio.".jpg";
    imagejpeg($destino,$ruta,90);
  }else{
    imagealphablending($destino, false);
    imagesavealpha($destino, true);
    $origen = imagecreatefrompng($archivo["tmp_name"]);
    imagecopyresampled($destino,$origen,0,0,$x,$y,500,500,$lado,$lado);
    $ruta = $carpeta."/".$aleatorio.".png";
    imagepng($destino,$ruta);
  }
  return $ruta;
}

/* Borra la foto solo si está dentro de $base; si la carpeta queda vacía también la borra */
static public function borrarFoto($ruta, $base){
  if($ruta==""){
    return;
  }
  $baseReal = realpath($base);
  $archivo = realpath($ruta);
  if($baseReal===false || $archivo===false || strpos($archivo, $baseReal.DIRECTORY_SEPARATOR)!==0 || !is_file($archivo)){
    return;
  }
  unlink($archivo);
  $carpeta = dirname($archivo);
  if($carpeta!==$baseReal && count(scandir($carpeta))==2){
    rmdir($carpeta);
  }
}

/*=============================================
VALIDACIONES
=============================================*/
/* Cédula ecuatoriana: 10 dígitos, provincia 01-24 (o 30) y dígito verificador */
static public function cedulaValida($cedula){
  if(!preg_match('/^[0-9]{10}$/', $cedula)){
    return false;
  }
  $provincia = (int)substr($cedula,0,2);
  if(($provincia<1 || $provincia>24) && $provincia!=30){
    return false;
  }
  if((int)$cedula[2] > 5){
    return false;
  }
  $suma = 0;
  for($i=0; $i<9; $i++){
    $valor = (int)$cedula[$i] * ($i%2==0 ? 2 : 1);
    $suma += $valor>9 ? $valor-9 : $valor;
  }
  $verificador = (10 - $suma%10) % 10;
  return $verificador == (int)$cedula[9];
}

/* El docente que inició sesión solo puede registrar notas y asistencias de sus propias clases */
static public function esClaseDelDocente($idClase){
  if(self::rol()!="Docente"){
    return false;
  }
  $stmt = Conexion::conectar()->prepare("SELECT id_docente FROM edu_tab_grad_docente WHERE id = :id");
  $stmt -> bindParam(":id", $idClase, PDO::PARAM_INT);
  $stmt -> execute();
  return $stmt -> fetchColumn() == $_SESSION["id"];
}

/* Notas: todas deben ser números entre 0 y 10 */
static public function notasValidas($listas){
  foreach($listas as $lista){
    foreach((array)$lista as $nota){
      if(!is_numeric($nota) || $nota < 0 || $nota > 10){
        return false;
      }
    }
  }
  return true;
}

/* Contraseña nueva: mínimo 8 caracteres, con letras y números */
static public function passwordValida($password){
  return strlen($password)>=8 && preg_match('/[A-Za-z]/',$password) && preg_match('/[0-9]/',$password);
}

/* Alerta SweetAlert lista para imprimir */
static public function alerta($icono, $titulo, $texto, $redireccion){
  return '<script>
  Swal.fire({
    icon: '.json_encode($icono).',
    title: '.json_encode($titulo, JSON_UNESCAPED_UNICODE).',
    text: '.json_encode($texto, JSON_UNESCAPED_UNICODE).',
    showConfirmButton: true,
    confirmButtonText: "Cerrar"
  }).then(function(){
    window.location = '.json_encode(SERVERURL.$redireccion).';
  });
  </script>';
}

}
