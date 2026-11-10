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

                        Estudiantes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Estudiante</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
            <?php
            if($_SESSION["rol"]=="Administrador"){
               echo'
                <a href="'.SERVERURL.'agregarAlumno/" class="btn btn-primary" role="button" aria-pressed="true">
                    <i class="fas fa-plus-square"></i> Agregar Estudiante</a>
';}?>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablaboton">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Cédula</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Género</th>

                            <th>Telefono</th>
                            <th>Usuario</th>
                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$item = "";
$valor = "";

$alumno = ControladorAlumnos::ctrMostrarAlumnos($item, $valor);
$cont = 0;
foreach ($alumno as $a) {
    $cont = $cont + 1;
   
    echo '<tr>
                         <td>' . $cont . '</td>
                         <td>' . $a["cedula"] . '</td>
                        <td>' . $a["nombres"] . '</td>
                        <td>' . $a["apellidos"] . '</td>
                        <td>' . $a["genero"] . '</td>
                        
                        <td>' . $a["telefono"] . '</td>
                        <td>' . $a["usuario"] . '</td>';

    echo '<td>
                        <div class="btn-group">
                        <button class="btn btn-primary btnFicha" data-tipo="E" data-id="'.$a["id"].'" title="Ver ficha"><i class="fas fa-eye"></i></button>';
                        echo '<a class="btn btn-info" href="'.SERVERURL.'historial/'.$a["id"].'/" title="Historial académico"><i class="fas fa-history"></i></a>';
                        /*EDITAR: ADMINISTRADOR Y SECRETARÍA. BORRAR: SOLO EL ADMINISTRADOR*/
                        echo '<a class="btn btn-warning" href="'.SERVERURL.'editarAlumno/'.$a["id"].'" title="Editar"><i class="fas fa-edit"></i></a>';
                        if($_SESSION["rol"]=="Administrador"){
                          echo '<button class="btn btn-danger btnEliminarEstudiante" idEstudiante="' . $a["id"] . '" title="Borrar"><i class="fas fa-trash"></i></button>';
                        }
                 echo'       </div>
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
<?php
$borrarE = new ControladorAlumnos();
$borrarE->ctrBorrarEstudiante();
?>
<!-- 
/*=============================================
MODAL EDITAR Estudiante 
=============================================*/ -->
<div class="modal fade" id="modalEditarEstudiante" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Estudiante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- CEDULA Estudiante -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control input-lg" name="editarCedulaE" id="editarCedulaE"
                                readonly>
                        </div>
                    </div>
                    <!-- NOMBRES -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" class="form-control input-lg" name="editarNombreE" id="editarNombreE"
                                readonly>
                        </div>
                    </div>
                    <!-- APELLIDOS -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" class="form-control input-lg" name="editarApellidoE" id="editarApellidoE"
                                readonly>
                        </div>
                    </div>
                    <!-- GENERO -->
                    <!-- <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                            <input type="text" class="form-control input-lg" name="editarGeneroD" id="editarGeneroD"
                                readonly>
                        </div>
                    </div> -->

                    <!-- EMAIL -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="email" class="form-control input-lg" name="editarEmailE" id="editarEmailE"
                                placeholder="Ingrese correo electrónico" required>
                        </div>
                    </div>
                    <!-- TELEFONO -->
                    <div class="form-group">
                        <div class="input-group">

                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control" data-inputmask='"mask": "999 999-9999"' data-mask
                                placeholder="Ingrese número telefónico" name="editarTelefonoE" id="editarTelefonoE"
                                required>
                        </div>
                        <!-- DIRECCION-->
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control input-lg" name="editarDireccionD" id="editarDireccionE"
                                placeholder="Ingrese dirección"></textarea>
                        </div>
                    </div>
                    <!-- FECHA DE NACIMIENTO -->
                    <!-- <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control input-lg" data-inputmask-alias="datetime"
                                data-inputmask-inputformat="yyyy/mm/dd" data-mask name="editarFechaD" id="editarFechaD"
                                readonly>
                        </div>

                    </div> -->
                    <!-- REPRESENTANTE -->
                    <div class="form-group">

                        <span class=""><strong>Representante: </strong></span>
                        <div class="input-group">
                            <div class="select2-purple">

                                <select class="select2 input-lg" multiple=""
                                    style="width: 100%;"
                                    name="editarRepresentanteE">
                                    <option value=" " id="editarRepresentanteE"></option>
  
                                </select>

                            </div>

                        </div> 
                    </div>

                    <!-- ENTRADA PARA LA ROL-->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                            <select class="form-control input-lg" name="editarRepresentanteE" required>
                                <option value=" " id="editarRepresentanteE"></option>
                                <?php

$representante=ControladorRepresentantes::ctrListarRepresentante();

foreach($representante as $key=>$value){
   
echo'<option value="'.$value["id"].'">'.$value["cedula"].': '.$value["nombre"].'</option>';
     
}
          ?>
                            </select>
                        </div>
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

<?php include __DIR__."/ficha.php"; ?>
