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
                    <h1><i class="fas fa-notes-medical"></i></i> Reporte Notas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Reporte Notas</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-body">

<?php
/*TABLA CON LAS COLUMNAS SEGÚN EL TIPO DE EVALUACIÓN DEL AÑO LECTIVO ACTIVO*/
$listaEstudiante = ControladorNotas::ctrReportNotEstud($_SESSION["id"]);
$tipo = !empty($listaEstudiante) ? $listaEstudiante[0]["tipo"] : ModeloNotas::mdlTipoPeriodoActivo();
Calificaciones::tablaReporte($listaEstudiante, $tipo, "materia", "Materia");
?>

            </div>

        </div>


    </section>
</div>