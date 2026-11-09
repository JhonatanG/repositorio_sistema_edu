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
                    <h1><i class="fas fa-book-reader"></i> Grados</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Grados</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarGrado">
                    <i class="fas fa-plus-square"></i> Agregar Grado
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablas">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Grados</th>
                            <th>Orden</th>
      
                            <th>Observación</th>
                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $item="";
                $valor="";
                $Grado = ControladorGrados::ctrMostrarGrados($item,$valor);
                $cont=0;
        foreach($Grado as $a){
            $cont=$cont+1;
                 echo '<tr>
                         <td>'.$cont.'</td>
                        <td>'.$a["nombre"].'</td>
                        <td>'.($a["orden"]!==null ? $a["orden"] : '<span class="text-warning">Sin orden</span>').'</td>';
                        $item="id";
  
                echo   '
                        <td>'.$a["observacion"].'</td>
                        <td>
                        <div class="btn-group">
                          <button class="btn btn-warning btnEditarGrado" idGrado="'.$a["id"].'" data-toggle="modal"  data-target="#modalEditarGrado">
                          <i class="fas fa-edit"></i></butoon>
                          <button class="btn btn-danger btnEliminarGrado" idGrado="'.$a["id"].'"><i class="fas fa-trash"></i></button>
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
MODAL AGREGAR Grado
=============================================*/ -->
<div class="modal fade" id="modalAgregarGrado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Agregar Grados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- ENTRADA Grados -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-book-open"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevoGrado" id="nuevoGrado"
                                placeholder="Ingrese Grado" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                            <input type="number" min="1" class="form-control input-lg" name="nuevoOrden" id="nuevoOrden" placeholder="Orden del nivel (1 = primer nivel)">
                        </div>
                        <small class="text-muted">Define cuál es el nivel siguiente al aprobar (Primero = 1, Segundo = 2...).</small>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                            <textarea class="form-control input-lg" name="nuevaObservacion" id="nuevaObservacion"
                                placeholder="Ingrese observación (Opcional)"></textarea>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <select name="nuevoAnio" id="nuevoAnio" class="form-control input-lg" required>
                                <option value="">Seleccionar Año Escolar</option>
                                <?php
                               
                        $periodo=ControladorAnio::ctrListarAnio();
                foreach($periodo as $key=>$value){
                            
                  echo'
                            <option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                }
                            ?>

                            </select>
                        </div>
                    </div> -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
$guardarCursos= new ControladorGrados();
$guardarCursos->ctrCrearGrados();
?>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- 
/*=============================================
MODAL EDITAR Grado 
=============================================*/ -->
<div class="modal fade" id="modalEditarGrado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Grados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">
                    <!-- ENTRADA Grados -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-book-open"></i></span>
                            <input type="hidden" class="form-control input-lg" id="idGrado" name="idGrado">
                            <input type="text" class="form-control input-lg" name="editarGrado" id="editarGrado" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                            <input type="number" min="1" class="form-control input-lg" name="editarOrden" id="editarOrden" placeholder="Orden del nivel (1 = primer nivel)">
                        </div>
                        <small class="text-muted">Define cuál es el nivel siguiente al aprobar (Primero = 1, Segundo = 2...).</small>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                            <textarea class="form-control input-lg" name="editarObservacion"
                                id="editarObservacion"></textarea>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <select name="editarAnio" id="id" class="form-control input-lg" readonly>
                                <option id="editarAnio"></option>
                            </select>
                        </div>
                    </div> -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
<?php
$editar= new ControladorGrados();
$editar->ctrEditarGrados();
?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$eliminarGrados = new ControladorGrados();
$eliminarGrados->ctrEliminarGrados();

?>