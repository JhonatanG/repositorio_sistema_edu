<?php
 if($_SESSION["rol"]=="Docente"
 || $_SESSION["rol"]=="Estudiante"|| $_SESSION["rol"]=="Administrador" ){
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
            <div class="card-header">
                <form method="post" class="row align-items-end filtros">
                    <div class="col-lg-9 col-md-8 form-group">
                        <label>Estudiante</label>
                        <select class="form-control select2bs4" style="width: 100%;" name="idEstudiante" id="idEstudiante" required>
                            <option value="">Seleccionar</option>
                            <?php
                            foreach(ControladorAsistencia::ctrEstuRepre($_SESSION["id"]) as $value){
                              $elegido = isset($_POST["idEstudiante"]) && $_POST["idEstudiante"] == $value["id"];
                              if($elegido){ $idEstud = $value["id"]; }
                              echo '<option value="'.$value["id"].'"'.($elegido ? ' selected' : '').'>'.htmlspecialchars($value["cedula"].": ".$value["nombre"]).'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 form-group">
                        <button class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Aplicar filtros</button>
                    </div>
                </form>
            </div>
<?php
/*RESUMEN POR ASIGNATURA Y DETALLE CON JUSTIFICACIONES DEL REPRESENTADO ELEGIDO*/
if(isset($idEstud)){
  ControladorAsistencia::tablaResumen(ControladorAsistencia::ctrReportAsisEstud($idEstud), "materia", "Asignatura", true);
  ControladorAsistencia::tablaDetalle(ControladorAsistencia::ctrDetalleEstudiante($idEstud), "materia", "Asignatura");
}
?>

            </div>

        </div>


    </section>
</div>