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
              <h1>  <i class="fab fa-adn"></i> Paralelos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Paralelos</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarParalelos">
                    <i class="fas fa-plus-square"></i> Agregar Paralelo
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablas">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Paralelo</th>
                            <th>Observación</th>
                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $item="";
                $valor="";

               $Paralelos = ControladorParalelos::ctrMostrarParalelos($item,$valor);
                $cont=0;
        foreach($Paralelos as $a){
            $cont=$cont+1;
                 echo '<tr>
                         <td>'.$cont.'</td>
                        <td>'.$a["nombre"].'</td>
                        <td>'.$a["observacion"].'</td>
                        <td>
                        <div class="btn-group">
                          <button class="btn btn-warning btnEditarParalelo" idParalelo="'.$a["id"].'" data-toggle="modal"  data-target="#modalEditarParalelos">
                          <i class="fas fa-edit"></i></butoon>
                          <button class="btn btn-danger btnEliminarParalelo" idParalelo="'.$a["id"].'"><i class="fas fa-trash"></i></button>
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
MODAL AGREGAR Paralelos LECTIVO
=============================================*/ -->
<div class="modal fade" id="modalAgregarParalelos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Agregar Paralelo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- ENTRADA Paralelo -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevoParalelo" id="nuevoParalelo"
                                placeholder="Ingrese Paralelo" require>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                            <textarea class="form-control input-lg" name="nuevaObservacion" id="nuevaObservacion"
                                placeholder="Observacion (Opcional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
                    $crearParalelo= new ControladorParalelos();
                    $crearParalelo->ctrCrearParalelos();

                    ?>
 
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 
/*=============================================
MODAL EDITAR Paralelos LECTIVO
=============================================*/ -->
<div class="modal fade" id="modalEditarParalelos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Paralelo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- ENTRADA Paralelo -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <input type="hidden" class="form-control input-lg" id="idParalelo" name="idParalelo">

                            <input type="text" class="form-control input-lg" id="editarParalelo" name="editarParalelo" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                    
                            <span class="input-group-text"><i class="fas fa-archive"></i></i></span>
                            <textarea class="form-control input-lg" name="editarObservacion"
                              id="editarObservacion"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                  <?php
                  $editarParalelo=new ControladorParalelos();
                  $editarParalelo->ctrEditarParalelos();

                  ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$eliminarParalelo = new ControladorParalelos();
$eliminarParalelo->ctrEliminarParalelos();

?>