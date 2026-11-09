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
                    <h1> <i class="fas fa-book"></i> Materias</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Materias</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarMateria">
                    <i class="fas fa-plus-square"></i> Agregar Materias
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablas">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Materias</th>
                            <th>Observación</th>
                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $item="";
                $valor="";

                $Materia = ControladorMaterias::ctrMostrarMaterias($item,$valor);
                $cont=0;
        foreach($Materia as $a){
            $cont=$cont+1;
                 echo '<tr>
                         <td>'.$cont.'</td>
                        <td>'.$a["nombre"].'</td>
                        <td>'.$a["observacion"].'</td>
                        <td>
                        <div class="btn-group">
                          <button class="btn btn-warning btnEditarMateria" idMateria="'.$a["id"].'" data-toggle="modal"  data-target="#modalEditarMateria">
                          <i class="fas fa-edit"></i></butoon>
                          <button class="btn btn-danger btnEliminarMateria" idMateria="'.$a["id"].'"><i class="fas fa-trash"></i></button>
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
MODAL AGREGAR Materia
=============================================*/ -->
<div class="modal fade" id="modalAgregarMateria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Agregar Materias</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- ENTRADA Materias -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevaMateria" id="nuevaMateria"
                                placeholder="Ingrese Materias" require>
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
            $crearMateria = new ControladorMaterias();
            $crearMateria->ctrCrearMaterias();
            ?>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 
/*=============================================
MODAL EDITAR Materia 
=============================================*/ -->
<div class="modal fade" id="modalEditarMateria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Materias</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- ENTRADA Materias -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <input type="hidden" class="form-control input-lg" id="idMateria" name="idMateria">

                            <input type="text" class="form-control input-lg" id="editarMateria" name="editarMateria" readonly>
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
                    $editarMateria = new ControladorMaterias();
                    $editarMateria->ctrEditarMaterias();

                    ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$eliminarMateria = new ControladorMaterias();
$eliminarMateria->ctrEliminarMaterias();
?>