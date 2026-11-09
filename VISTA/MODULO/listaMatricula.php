<?php
 if( $_SESSION["rol"]=="Docente"
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
                    <h1><i class="fas fa-user-graduate"></i>

                        Lista Matriculados</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Lista Matriculados</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <a href="<?php echo SERVERURL;  ?>matricula/" class="btn btn-primary" role="button" aria-pressed="true">
                    <i class="fas fa-plus-square"></i> Agregar Matrícula</a>


            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablaboton">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Matricula</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Grado</th>
                            <th>Periodo</th>
                            <th>Observación</th>
                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$item = "";
$valor = "";

$matriculados = ControladorMatricula::ctrMostrarMatriculaDet($item, $valor);
$cont = 0;
foreach ($matriculados as $a) {
    $cont = $cont + 1;
   
    echo '<tr>
                         <td>' . $cont . '</td>
                         <td>' . $a["codigo"] . '</td>
                         <td>' . $a["cedula"] . '</td>
                        <td>' . $a["nombre"] . '</td>
                        <td>' . $a["grado"] . '</td>
                        <td>' . $a["periodo"] . '</td> 
                        <td>' . $a["observacion"] . '</td> 
                        <td>
                        <div class="btn-group">
                        <a class="btn btn-info" href="'.SERVERURL.'historial/'.$a["id_estudiante"].'/" title="Historial académico"><i class="fas fa-history"></i></a>';
        /*EDITAR Y BORRAR SOLO EL ADMINISTRADOR; EL HISTORIAL LO VE TAMBIÉN SECRETARÍA*/
        if($_SESSION["rol"]=="Administrador"){
          echo '<button class="btn btn-warning btnEditarMatricula" idMatricula="'.$a["id"].'" data-toggle="modal" data-target="#modalEditarMatricula"><i class="fas fa-edit"></i></button>
                          <button class="btn btn-danger btnEliminarMatricula" idMatricula="' . $a["id"] . '"><i class="fas fa-trash"></i></button>';
        }
                    echo'</div></td>
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
MODAL EDITAR Paralelo 
=============================================*/ -->
<div class="modal fade" id="modalEditarMatricula" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Matrícula</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="editarMatricula" id="editarMatricula">

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                            <textarea class="form-control input-lg" name="editarObservacion" id="editarObservacion"
                                placeholder="Ingrese observación (Opcional)"></textarea>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
<?php
$editar = new ControladorMatricula();
$editar -> ctrEditarMatricula();
?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$borrar = new ControladorMatricula();
$borrar->ctrEliminarMatricula();
?>