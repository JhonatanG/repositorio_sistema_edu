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
                    <h1> <i class="fas fa-building nav-icon"></i>
                        Docentes-Materias</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Docente-Materias</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMaD">
                    <i class="fas fa-plus-square"></i> Agregar Docente-Materias
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablas">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Docentes</th>
                            <th>Materias</th>
                            <th>Estado</th>
                            <th>Observacion</th>
                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            $item="";
            $valor="";
            $madocente =ControladorMdocentes::ctrMostrarMdocentes($item,$valor);
            $cont=0;
    foreach($madocente as $a){
        $cont=$cont+1;
             echo '<tr>
                     <td>'.$cont.'</td>
                    <td>'.$a["docente"].'</td>
                    <td>'.$a["nombre"].'</td>';
                    if ($a["estado"]!=0){
                        echo' <td><button class="btn btn-success btn-xs  btnActivarMdocente" idMdocente="'.$a["id"].'"
                        estadoMdocente="0">Activo</button></td>';
                        }else{
                          echo' <td><button class="btn btn-danger btn-xs  btnActivarMdocente" idMdocente="'.$a["id"].'"
                        estadoMdocente="1">Inactivo</button></td>';
                        }

                echo '
                    <td>'.$a["observacion"].'</td>
                    <td>
                    <div class="btn-group">
                    <button class="btn btn-warning btnEditarMdocente" idMdocente="'.$a["id"].'" data-toggle="modal"  data-target="#modalEditarMdocente">
                          <i class="fas fa-edit"></i></butoon>
                      <button class="btn btn-danger btnEliminarMdocente" idMdocente="'.$a["id"].'"><i class="fas fa-trash"></i></button>
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
MODAL AGREGAR Paralelo
=============================================*/ -->
<div class="modal fade" id="modalAgregarMaD" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Agregar Docente-Materias</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- ENTRADA Materias -->
                    <div class="form-group">

                        <span class=""><strong>Seleccionar el Docente: </strong></span>

                        <div class="select2-purple">
                            <div class="input-group">
                                <select class="select2" multiple="multiple" data-placeholder="Seleccionar el Docente"
                                    data-dropdown-css-class="select2-purple" style="width: 100%;" name="idDocenteD"
                                    id="idDocenteD">
                                    <?php
                               
                               $docentes=ControladorDocentes::ctrListarDocentes();
                             
                               foreach($docentes as $key=>$value){
                                   
                                echo'<option value="'.$value["id"].'">'.$value["cedula"].': '.$value["nombre"].'</option>';
                                     
                              }
                                          ?>


                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <span class=""><strong> Seleccionar las Materias:</strong> </span>
                        <div class="input-group">

                            <?php
               $Materias=ControladorMaterias::ctrListarMaterias();
                foreach ($Materias as $key => $value) {
                    echo'<div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="idMateria" name="idMateria[]" value="'.$value["id"].'">
                    <label class="form-check-label" for="Materias">'.$value["nombre"].'</label>
                </div>';
                 }                     
                    ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                            <textarea class="form-control input-lg" name="nuevaObservacion" id="nuevaObservacion"
                                placeholder="Ingrese observación general para todas las materias seleccionadas (Opcional)"></textarea>
                        </div>
                    </div>
                    <input type="hidden" value="1" name="nuevoEstado">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
$crearN = new ControladorMdocentes();
$crearN -> ctrCrearMdocentes();
?>


                </div>
            </form>
        </div>
    </div>
</div>
<!-- 
/*=============================================
MODAL EDITAR Paralelo 
=============================================*/ -->
<div class="modal fade" id="modalEditarMdocente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Observación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">
             
                    <div class="form-group">
                        <div class="input-group">
            
                            <input type="hidden" class="form-control input-lg" name="editarMdocente" id="editarMdocente"
                                readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                            <textarea class="form-control input-lg" name="editarObservacion"
                                id="editarObservacion"></textarea>
                        </div>
                    </div>
      
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
<?php
$modificar = new ControladorMdocentes();
$modificar -> ctrEditarMdocente();
?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$borrar = new ControladorMdocentes();
$borrar->ctrEliminarMdocentes();
?>