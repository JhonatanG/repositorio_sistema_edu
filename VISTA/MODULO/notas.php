<?php Seguridad::recordarFiltros("notas", array("idGrado","idMateria")); ?>
<?php
 if($_SESSION["rol"]=="Secretaría" || $_SESSION["rol"]=="Administrador"
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
                    <h1><i class="fas fa-notes-medical"></i> Calificaciones</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Calificaciones</li>
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
/*=============================================
TABLA DE NOTAS: COLUMNAS SEGÚN EL TIPO DE EVALUACIÓN DEL AÑO LECTIVO
=============================================*/
/*EL GUARDADO VA FUERA DE LA TABLA: AL GUARDAR NO SE REENVÍAN EL GRADO NI LA MATERIA*/
ControladorNotas::ctrGuardarNotas();
$notas = ControladorNotas::ctrListaNotas();
if(!empty($lista) && !empty($notas)){
  $cfg = Calificaciones::config(ControladorNotas::ctrTipoGrado());
  $periodos = $cfg["periodos"];
?>
                <form method="POST">
                    <?php echo Seguridad::campoToken(); ?>
                    <input type="hidden" name="idClaseNotas" value="<?php echo $notas[0]["gdoc"]; ?>">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div>
                            <span class="badge badge-info p-2">Evaluación <?php echo $cfg["etiqueta"]; ?></span>
                            <small class="text-muted ml-2">
                                Promedio de cada <?php echo strtolower($cfg["nombre"]); ?> = (P1 + P2 + P3 + Examen) / 4 ·
                                Nota final = promedio de los <?php echo $periodos; ?> <?php echo strtolower($cfg["nombre"]); ?>s
                            </small>
                        </div>
                        <button class="btn btn-success mt-2 mt-md-0">Guardar <i class="fas fa-save"></i></button>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered table-sm tabla-notas tabla-ingreso-notas" data-periodos="<?php echo $periodos; ?>">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 8px;">#</th>
                                <th rowspan="2" style="min-width: 200px;">Estudiante</th>
                                <?php for($k=1; $k<=$periodos; $k++){ ?>
                                <th colspan="5" class="text-center periodo-<?php echo $k; ?>"><?php echo $cfg["nombre"]." ".$k; ?></th>
                                <?php } ?>
                                <th rowspan="2" class="promedio">Promedio final</th>
                            </tr>
                            <tr>
                                <?php for($k=1; $k<=$periodos; $k++){
                                  foreach(Calificaciones::campos() as $c){
                                    echo '<th class="periodo-'.$k.'">'.$c[2].'</th>';
                                  }
                                  echo '<th class="periodo-'.$k.' promedio">'.$cfg["sigla"].$k.'</th>';
                                } ?>
                            </tr>
                        </thead>
                        <tbody>
<?php
  $cont=0;
  foreach($notas as $es){
    $cont++;
    echo '<tr data-fila="'.$cont.'">
            <td>'.$cont.'<input type="hidden" name="idEstudiante[]" value="'.$es["id"].'"></td>
            <td>'.htmlspecialchars($es["estudiante"]).'</td>';
    for($k=1; $k<=$periodos; $k++){
      foreach(Calificaciones::campos() as $c){
        $valor = $es[$c[0]."Q".$k] === null ? 0 : $es[$c[0]."Q".$k];
        echo '<td class="periodo-'.$k.'"><input type="number" required class="form-control form-control-sm nota-input"
                data-periodo="'.$k.'" name="'.$c[0].'_'.$k.'[]" value="'.$valor.'" min="0" max="10" step="0.01"></td>';
      }
      $prom = $es["Q".$k] === null ? 0 : $es["Q".$k];
      echo '<td class="periodo-'.$k.' promedio"><input type="number" readonly tabindex="-1" class="form-control form-control-sm promedio-periodo"
              data-periodo="'.$k.'" value="'.$prom.'"></td>';
    }
    $final = $es["NF"] === null ? 0 : $es["NF"];
    echo '<td class="promedio"><input type="number" readonly tabindex="-1" class="form-control form-control-sm promedio-final" value="'.$final.'"></td>
          </tr>';
  }
?>
                        </tbody>
                    </table>
                    </div>
                </form>
<?php
}else if(!empty($lista)){
  echo '<div class="alert alert-warning">No hay estudiantes matriculados en esta clase.</div>';
}
?>
            </div>

        </div>


    </section>
</div>
