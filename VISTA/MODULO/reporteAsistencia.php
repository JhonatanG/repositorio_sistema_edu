<?php Seguridad::recordarFiltros("reporteAsistencia", array("idDocenteA","idGrado","idMateria")); ?>
<?php
 if($_SESSION["rol"]=="Docente"
 || $_SESSION["rol"]=="Representante"|| $_SESSION["rol"]=="Estudiante" ){
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
            <div class="card-header">
                <?php $filtroFecha = false; include __DIR__."/filtroClase.php"; ?>
            </div>





            <div class="card-body">
                <?php
  $lista= ControladorAsistencia::ctrListaCabecera();
  if(isset($lista)){
  foreach($lista as $a){
 echo' <div class="card bg-info text-dark"> 
 <div class="card-body">
  <div class="row">   <div class="col-md-3">
    <div class="form-group">
        <label>Docente:</label>
        <input type="text" class="form-control-plaintext" readonly value="'.$a["docente"].'">
    </div>
</div>';
echo'   <div class="col-md-3">
<div class="form-group">
    <label>Grado:</label>
    <input type="text" class="form-control-plaintext" readonly value="'.$a["grado"].'">
</div>
</div>';
echo'   <div class="col-md-3">
<div class="form-group">
    <label>Materia:</label>
    <input type="text" class="form-control-plaintext" readonly value="'.$a["materia"].'">
</div>
</div> 

</div>
</div>
</div>';
  }
  if(empty($lista)){
    echo' <div class="card bg-danger text-dark"> 
    <div class="card-body">
    <h3 align="center">¡No existen estudiantes!</h3>
    </div>
</div>';
  }
  }
?>
        

<?php
/*RESUMEN Y DETALLE CON JUSTIFICACIONES DE LA CLASE ELEGIDA*/
if(isset($_POST["idGrado"], $_POST["idMateria"]) && $_POST["idMateria"]!=""){
  if(empty($_POST["idDocenteG"]) && !empty($id)){
    $_POST["idDocenteG"] = $id;
  }
  ControladorAsistencia::tablaResumen(ControladorAsistencia::ctrReporteAsistencia(), "estudiante", "Estudiante");
  ControladorAsistencia::tablaDetalle(ControladorAsistencia::ctrDetalleClase(), "estudiante", "Estudiante");
}
?>
      
            </div>

        </div>


    </section>
</div>
