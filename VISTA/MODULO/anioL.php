<?php
 if($_SESSION["rol"]=="Secretaría" || $_SESSION["rol"]=="Docente"
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
                    <h1><i class="fas fa-calendar-week"></i> Año Lectivo</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Año Lectivo</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarAnio">
                    <i class="fas fa-plus-square"></i> Agregar Año Lectivo
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablas">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Año Lectivo</th>
                            <th>Observación</th>
                            <th>Evaluación</th>
                            <th>Estado</th>
                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $item="";
                $valor="";

                $anio = ControladorAnio::ctrMostrarAnio($item,$valor);
                $cont=0;
        foreach($anio as $a){
            $cont=$cont+1;
                 echo '<tr>
                         <td>'.$cont.'</td>
                        <td>'.$a["nombre"].'</td>
                        <td>'.$a["observacion"].'</td>
                        <td>'.Calificaciones::config($a["tipo_evaluacion"])["etiqueta"].'</td>';
                    if ($a["estado"]!=0){
                        echo' <td><button class="btn btn-success btn-xs  btnActivarAnio" idAnio="'.$a["id"].'"
                        estadoAnio="0">Activo</button></td>';
                        }else{
                          echo' <td><button class="btn btn-danger btn-xs  btnActivarAnio" idAnio="'.$a["id"].'"
                        estadoAnio="1">Inactivo</button></td>';
                        }
                        echo'
                        <td>
                        <div class="btn-group">
                          <button class="btn btn-warning btnEditarAnio" idAnio="'.$a["id"].'" data-toggle="modal"  data-target="#modalEditarAnio">
                          <i class="fas fa-edit"></i></butoon>
                          <button class="btn btn-danger btnEliminarAnio" idAnio="'.$a["id"].'"><i class="fas fa-trash"></i></button>
                        </div>
                        </td>
                        </tr>';
        }

?>
                    </tbody>

                </table>

            </div>

        </div>


    </section>

</div>
<!-- 
/*=============================================
MODAL AGREGAR ANIO LECTIVO
=============================================*/ -->
<div class="modal fade" id="modalAgregarAnio" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Agregar Año Lectivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- ENTRADA Año Lectivo -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevoAnio" id="nuevoAnio"
                                placeholder="Ingrese Año Lectivo" require>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                            <textarea class="form-control input-lg" name="nuevaObservacion" id="nuevaObservacion"
                                placeholder="Observacion (Opcional)"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="mb-1">Tipo de evaluación</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                            <select class="form-control input-lg" name="nuevoTipo" id="nuevoTipo" required>
                                <option value="QUIMESTRAL">Quimestral (2 quimestres)</option>
                                <option value="TRIMESTRAL">Trimestral (3 trimestres)</option>
                            </select>
                        </div>
                        <small class="text-muted">Define cuántos periodos se califican y cómo se calcula la nota final.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
            $crearAnio = new ControladorAnio();
            $crearAnio->ctrCrearAnio();
            ?>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 
/*=============================================
MODAL EDITAR ANIO LECTIVO
=============================================*/ -->
<div class="modal fade" id="modalEditarAnio" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Año Lectivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- ENTRADA Año Lectivo -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <input type="hidden" class="form-control input-lg" id="idAnio" name="idAnio">

                            <input type="text" class="form-control input-lg" id="editarAnio" name="editarAnio" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                    
                            <span class="input-group-text"><i class="fas fa-archive"></i></i></span>
                            <textarea class="form-control input-lg" name="editarObservacion"
                              id="editarObservacion"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="mb-1">Tipo de evaluación</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                            <select class="form-control input-lg" name="editarTipo" id="editarTipo" required>
                                <option value="QUIMESTRAL">Quimestral (2 quimestres)</option>
                                <option value="TRIMESTRAL">Trimestral (3 trimestres)</option>
                            </select>
                        </div>
                        <small class="text-muted">Define cuántos periodos se califican y cómo se calcula la nota final.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
                    $editarAnio = new ControladorAnio();
                    $editarAnio->ctrEditarAnio();

                    ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$eliminarAnio = new ControladorAnio();
$eliminarAnio->ctrEliminarAnio();
?>