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
                        Grados-Materias</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Grado-Materias</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarParalelo">
                    <i class="fas fa-plus-square"></i> Agregar Grado-Materias
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablas">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Grados</th>
                            <th>Materias</th>

                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            $item="";
            $valor="";
            $gparalelo =ControladorGMaterias::ctrMostrarGMaterias($item,$valor);
            $cont=0;
    foreach($gparalelo as $a){
        $cont=$cont+1;
             echo '<tr>
                     <td>'.$cont.'</td>
                    <td>'.$a["grado"].'</td>
                    <td>'.$a["materia"].'</td>
                    <td>
                    <div class="btn-group">
                      <button class="btn btn-danger btnEliminarGmateria" idGmateria="'.$a["id"].'"><i class="fas fa-trash"></i></button>
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
<div class="modal fade" id="modalAgregarParalelo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Agregar Grado-Materias</h5>
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
                            <select name="idGrado" class="form-control input-lg" required>
                                <option value="">Seleccionar Grado</option>
                                <?php
                           
                    $grados=ControladorGrados::ctrListarGrados();
            foreach($grados as $key=>$value){
                        
              echo'
                        <option value="'.$value["id"].'">'.$value["nombre"].'</option>';
            }
                        ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <span class=""><strong> Seleccionar Materias:</strong> </span>
                        <div class="input-group">

                            <?php
               $Materias=ControladorMaterias::ctrListarMaterias();
                foreach ($Materias as $key => $value) {
                    echo'<div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="idMateria[]" value="'.$value["id"].'">
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


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
                    $crear=new ControladorGmaterias();
                    $crear->ctrCrearGmaterias();

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
<div class="modal fade" id="modalEditarParalelo" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <span class="input-group-text"><i class="fas fa-book-open"></i></span>
                            <input type="hidden" class="form-control input-lg" id="idParalelo" name="idParalelo">
                            <input type="text" class="form-control input-lg" name="editarParalelo" id="editarParalelo"
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
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <select name="editarAnio" id="id" class="form-control input-lg" readonly>
                                <option id="editarAnio"></option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<?php
$eliminar= new ControladorGmaterias();
$eliminar->ctrEliminarGmaterias();
?>