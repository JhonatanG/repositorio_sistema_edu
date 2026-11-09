<?php
 if($_SESSION["rol"]=="Docente"
 || $_SESSION["rol"]=="Representante"|| $_SESSION["rol"]=="Administrador" ){
  echo  '<script> 
  window.location="'.SERVERURL.'inicio/";
  </script>
   ';
   return;
 }



?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-calendar-week"></i> Reporte Asistencia</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Reporte Asistencia</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-body">

<?php
/*RESUMEN POR ASIGNATURA Y DETALLE CON JUSTIFICACIONES DEL ESTUDIANTE*/
ControladorAsistencia::tablaResumen(ControladorAsistencia::ctrReportAsisEstud($_SESSION["id"]), "materia", "Asignatura", true);
ControladorAsistencia::tablaDetalle(ControladorAsistencia::ctrDetalleEstudiante($_SESSION["id"]), "materia", "Asignatura");
?>

            </div>

        </div>


    </section>
</div>