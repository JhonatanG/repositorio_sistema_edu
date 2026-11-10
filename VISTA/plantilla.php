<?php
session_set_cookie_params(array("httponly"=>true, "samesite"=>"Lax", "secure"=>Seguridad::esHttps()));
session_start();//INICIAR VARIABLES DE SESSION

include "MODULO/config.php";

/*=============================================
PERMISOS: QUÉ ROLES PUEDEN ENTRAR A CADA PÁGINA
=============================================*/
$admin = array("Administrador");
$adminSecre = array("Administrador","Secretaría");
$permisos = array(
  "inicio"               => array("Administrador","Secretaría","Docente","Representante","Estudiante"),
  "salir"                => array("Administrador","Secretaría","Docente","Representante","Estudiante"),
  "cambiarPassword"      => array("Administrador","Secretaría","Docente","Representante","Estudiante"),
  "perfil"               => array("Administrador","Secretaría","Docente","Representante","Estudiante"),
  "usuarios"             => $admin,
  "anioL"                => $admin,
  "materias"             => $admin,
  "grados"               => $admin,
  "paralelos"            => $admin,
  "gparalelos"           => $admin,
  "gmaterias"            => $admin,
  "representantes"       => $adminSecre,
  "agregarAlumno"        => $admin,
  "editarAlumno"         => $adminSecre,
  "madocente"            => $admin,
  "docgrado"             => $admin,
  "horarios"             => $admin,
  "miHorario"            => array("Docente"),
  "justificarFaltas"     => array("Secretaría"),
  "docente"              => $adminSecre,
  "alumnos"              => $adminSecre,
  "matricula"            => $adminSecre,
  "listaMatricula"       => $adminSecre,
  "historial"            => $adminSecre,
  "listadoEstudiantes"   => $adminSecre,
  "editarAsistencia"     => $adminSecre,
  "reporteAsistencia"    => $adminSecre,
  "reporteNotasG"        => $adminSecre,
  "asistencia"           => array("Docente"),
  "notas"                => array("Docente"),
  "reporteNotas"         => array("Docente"),
  "reporteAsistenciaEs"  => array("Secretaría","Estudiante"),
  "reportesNotasEs"      => array("Secretaría","Estudiante"),
  "reporteAsistenciaRep" => array("Secretaría","Representante"),
  "reporteNotasRep"      => array("Secretaría","Representante")
);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>U.E.“Cristóbal Colón”</title>

    <!-- =============================================
PLUGINS CSS
=============================================*/ -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/plugins/fontawesome-free/css/all.min.css">
    <!-- ICONO DE NAVEGACION -->
    <link rel="icon" href="<?php echo SERVERURL;  ?>VISTA/dist/img/escudo.png">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style (incluye Bootstrap 4) -->
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/dist/css/adminlte.min.css">
    <!-- ESTILOS PROPIOS -->
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/dist/css/validacionPass.css">
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/dist/css/login.css">
    <link rel="stylesheet" href="<?php echo SERVERURL;  ?>VISTA/dist/css/tema.css">

    <!-- =============================================
PLUGINS JAVASCRIPT
=============================================*/ -->
<script>var SERVERURL = "<?php echo SERVERURL;  ?>";
var TOKEN = "<?php echo Seguridad::token(); ?>";
// Si una imagen no existe (p. ej. foto borrada del disco), mostrar la imagen por defecto
document.addEventListener("error", function (e) {
  var img = e.target, defecto = SERVERURL + "VISTA/img/anonymous.png";
  if (img.tagName === "IMG" && img.getAttribute("src") && img.src !== defecto) img.src = defecto;
}, true);
</script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/jquery/jquery.min.js"></script>
<script>
// Todas las peticiones AJAX envían el token de seguridad
$.ajaxSetup({ headers: { "X-Token": TOKEN } });
</script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- DATATABLE -->
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- AdminLTE App -->
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/adminlte.min.js"></script>
  <!-- SWEETALERT -->
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/sweetalert2/sweetalert2.all.js"></script>
<script>
// Si una alerta de error no explica el motivo, se agrega el que dio la base de datos
(function () {
  var fire = Swal.fire.bind(Swal);
  Swal.fire = function (opciones) {
    if (opciones && typeof opciones === "object" && opciones.icon === "error" && !opciones.text && window.ERROR_BD) {
      opciones.text = window.ERROR_BD;
    }
    return fire.apply(null, arguments);
  };
})();
</script>

<script src="<?php echo SERVERURL;  ?>VISTA/plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- SELECT2 -->
<script src="<?php echo SERVERURL;  ?>VISTA/plugins/select2/js/select2.full.min.js"></script>

</head>

<?php
if(Seguridad::sesionActiva()){
echo '<body class="hold-transition sidebar-mini layout-fixed">';
echo '<div class="wrapper">';
date_default_timezone_set('America/Guayaquil');
/*=============================================
CABECERA
 =============================================*/
 include 'MODULO/cabecera.php';
 /*=============================================
 MENU
  =============================================*/
 include 'MODULO/menu.php';
 /*=============================================
 INICIO PAGINAS
  =============================================*/
  $rutas = array("inicio");
  if(isset($_GET["ruta"]) && $_GET["ruta"]!=""){
     $rutas = explode("/",$_GET["ruta"]);
  }

  if(!empty($_SESSION["cambiarPassword"]) && $rutas[0]!="salir"){
     /*MIENTRAS TENGA LA CONTRASEÑA INICIAL SOLO PUEDE CAMBIARLA*/
     include "MODULO/cambiarPassword.php";
  }else if(isset($permisos[$rutas[0]])){
     if(in_array(Seguridad::rol(), $permisos[$rutas[0]])){
        include "MODULO/".$rutas[0].".php";
     }else{
        echo '<script>window.location="'.SERVERURL.'inicio/";</script>';
     }
  }else{
     include "MODULO/404.php";
  }

 include "MODULO/footer.php";
 echo '</body>';
}else{
    echo '<body class="hold-transition login-page">';
    include "MODULO/login.php";
    echo '</body>';
}


?>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/plantilla.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/usuarios.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/anio.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/materias.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/grados.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/paralelos.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/validacionPass.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/docentes.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/representantes.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/alumnos.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/madocentes.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/docgrado.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/gparalelo.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/matricula.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/asistencia.js"></script>
<script src="<?php echo SERVERURL;  ?>VISTA/dist/js/notas.js"></script>



</html>
