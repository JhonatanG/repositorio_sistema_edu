<?php
/*=============================================
ACCESOS DIRECTOS: SOLO SE MUESTRAN LOS QUE EL ROL PUEDE ABRIR ($permisos VIENE DE plantilla.php)
=============================================*/
$accesos = array(
  array("asistencia", "Tomar asistencia", "fas fa-calendar-check", "Registre la asistencia de sus clases"),
  array("notas", "Ingresar calificaciones", "fas fa-pen-alt", "Parciales, exámenes y promedios"),
  array("reporteNotas", "Reporte de notas", "fas fa-chart-bar", "Notas de sus estudiantes"),
  array("matricula", "Crear matrícula", "fas fa-file-signature", "Matricule a un estudiante"),
  array("listaMatricula", "Matrículas", "fas fa-list-alt", "Consulte las matrículas"),
  array("listadoEstudiantes", "Listado de estudiantes", "fas fa-list-ol", "Por nivel y paralelo, en PDF"),
  array("alumnos", "Estudiantes", "fas fa-user-graduate", "Registro de estudiantes"),
  array("docente", "Docentes", "fas fa-chalkboard-teacher", "Registro de docentes"),
  array("representantes", "Representantes", "fas fa-user-friends", "Registro de representantes"),
  array("reporteAsistencia", "Reporte de asistencia", "fas fa-clipboard-list", "Asistencia por grado"),
  array("reporteNotasG", "Reporte de notas por grado", "fas fa-chart-line", "Calificaciones por grado"),
  array("reporteAsistenciaEs", "Mi asistencia", "fas fa-calendar-check", "Consulte su asistencia"),
  array("reportesNotasEs", "Mis calificaciones", "fas fa-chart-bar", "Consulte sus notas"),
  array("reporteAsistenciaRep", "Asistencia de mi representado", "fas fa-calendar-check", "Consulte la asistencia"),
  array("reporteNotasRep", "Notas de mi representado", "fas fa-chart-bar", "Consulte las calificaciones"),
  array("usuarios", "Usuarios del sistema", "fas fa-users-cog", "Cuentas y roles"),
  array("anioL", "Año lectivo", "fas fa-calendar-alt", "Periodos académicos")
);
$rol = Seguridad::rol();
$verTotales = in_array($rol, array("Administrador","Secretaría"));
if($verTotales){
  $totales = ModeloInicio::mdlTotales();
}
$hora = (int)date("G");
$saludo = $hora < 12 ? "Buenos días" : ($hora < 19 ? "Buenas tardes" : "Buenas noches");
$nombre = trim($_SESSION["nombre"]." ".(isset($_SESSION["apellidos"]) ? $_SESSION["apellidos"] : ""));
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Inicio</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Inicio</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="bienvenida mb-4">
        <img src="<?php echo SERVERURL; ?>VISTA/dist/img/escudo.png" alt="Escudo">
        <div>
          <h2><?php echo $saludo.", ".htmlspecialchars($nombre); ?></h2>
          <p>
            <span class="badge badge-light"><?php echo htmlspecialchars($rol); ?></span>
            <?php if($verTotales){ ?>
            <span class="ml-2">
              <?php echo $totales["periodo"] != "" ? "Año lectivo activo: <b>".htmlspecialchars($totales["periodo"])."</b>" : "No hay un año lectivo activo"; ?>
            </span>
            <?php } ?>
          </p>
        </div>
      </div>

      <?php if($verTotales){ ?>
      <div class="row">
        <?php
        $tarjetas = array(
          array($totales["estudiantes"], "Estudiantes", "fas fa-user-graduate", "bg-azul", "alumnos"),
          array($totales["docentes"], "Docentes", "fas fa-chalkboard-teacher", "bg-rojo", "docente"),
          array($totales["representantes"], "Representantes", "fas fa-user-friends", "bg-dorado", "representantes"),
          array($totales["matriculas"], "Matrículas del año activo", "fas fa-file-signature", "bg-verde", "listaMatricula")
        );
        foreach($tarjetas as $t){
          $enlace = in_array($rol, $permisos[$t[4]]) ? SERVERURL.$t[4]."/" : "#";
          echo '<div class="col-lg-3 col-6">
            <div class="small-box '.$t[3].'">
              <div class="inner">
                <h3>'.$t[0].'</h3>
                <p>'.$t[1].'</p>
              </div>
              <div class="icon"><i class="'.$t[2].'"></i></div>
              <a href="'.$enlace.'" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>';
        }
        ?>
      </div>
      <?php } ?>

      <h5 class="titulo-seccion">Accesos directos</h5>
      <div class="row">
        <?php
        foreach($accesos as $a){
          if(!isset($permisos[$a[0]]) || !in_array($rol, $permisos[$a[0]])){
            continue;
          }
          echo '<div class="col-xl-3 col-lg-4 col-sm-6">
            <a class="acceso" href="'.SERVERURL.$a[0].'/">
              <span class="acceso-icono"><i class="'.$a[2].'"></i></span>
              <span>
                <b>'.$a[1].'</b>
                <small>'.$a[3].'</small>
              </span>
            </a>
          </div>';
        }
        ?>
      </div>

    </div>
  </section>
  <!-- /.content -->
</div>
