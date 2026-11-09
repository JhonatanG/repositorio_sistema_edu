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
                        <button class="btn btn-primary btnVisuaEstudiante" idAlumnoV="'.$a["id"].'" data-toggle="modal"  data-target="#modalVisualizarEstudiante">
                        <i class="fas fa-eye"></i></button>';
                        echo '<a class="btn btn-info" href="'.SERVERURL.'historial/'.$a["id"].'/" title="Historial académico"><i class="fas fa-history"></i></a>';
                        /*EDITAR Y BORRAR SOLO EL ADMINISTRADOR*/
                        if($_SESSION["rol"]=="Administrador"){
                          echo '<a class="btn btn-warning" href="'.SERVERURL.'editarAlumno/'.$a["id"].'"><i class="fas fa-edit"></i></a>
                          <button class="btn btn-danger btnEliminarEstudiante" idEstudiante="' . $a["id"] . '"><i class="fas fa-trash"></i></button>';
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
MODAL Visualizar Estudiante 
=============================================*/ -->
<div class="modal fade" id="modalVisualizarEstudiante" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Visualizar Estudiante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- 
/*=============================================
Estudiante 
=============================================*/ -->
                        <h3 style="text-align: center;"><strong> Estudiante </strong></h3>
                        <div class="row">
                            <div class="col-md-3 ml-auto">
                                <label class="col-sm-2 col-form-label">Nombres:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaNombreE"
                                    id="visuaNombreE">

                            </div>
                            <div class="col-md-3 ml-auto">
                                <label class="col-sm-2 col-form-label">Apellidos:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaApellidoE"
                                    id="visuaApellidoE">

                            </div>
                            <div class="col-md-3 ml-auto">
                                <label class="col-sm-2 col-form-label">Cédula:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaCedulaE"
                                    id="visuaCedulaE">

                            </div>
                            <div class="col-md-3 ml-auto">
                                <label class="col-sm-2 col-form-label">Genero:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaGeneroE"
                                    id="visuaGeneroE">

                            </div>

                        </div>
                        <div class="row">
                            <!-- <div class="col-md-3 ml-auto">
                                <label class="col-sm-2 col-form-label">Usuario:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaUsuarioD"
                                    id="visuaUsuarioD">

                            </div> -->
                            <div class="col-md-4 ml-auto">
                                <label class="col-sm-2 col-form-label">Teléfono:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaTelefonoE"
                                    id="visuaTelefonoE">

                            </div>
                            <div class="col-md-4 ml-auto">
                                <label class="col-form-label">Fecha de Nacimiento:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaFechaE"
                                    id="visuaFechaE">

                            </div>
                            <div class="col-md-4 ml-auto">
                                <label class="col-sm-2 col-form-label">Edad:</label>
                                <input type="text" readonly class="form-control-plaintext" name="edadE" id="edadE">

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-6 ">
                                <label class="col-sm-3 col-form-label">Email:</label>
                                <input type="text" readonly class="form-control-plaintext input-lg" name="visuaEmailE"
                                    id="visuaEmailE">

                            </div>
                            <div class="col-sm-6">
                                <label class="col-sm-3 col-form-label">Dirección:</label>
                                <textarea type="text" readonly class="form-control-plaintext input-lg"
                                    name="visuaDireccionE" id="visuaDireccionE"></textarea>
                            </div>
                        </div>
                        <!-- 
/*=============================================
Representante
=============================================*/ -->
                        <h3 style="text-align: center;"><strong> Representante </strong></h3>
                        <div class="row">
                            <div class="col-md-3 ml-auto">
                                <label class="col-sm-2 col-form-label">Nombres:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaNombreR"
                                    id="visuaNombreR">

                            </div>
                            <div class="col-md-3 ml-auto">
                                <label class="col-sm-2 col-form-label">Apellidos:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaApellidoR"
                                    id="visuaApellidoR">

                            </div>
                            <div class="col-md-3 ml-auto">
                                <label class="col-sm-2 col-form-label">Cédula:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaCedulaR"
                                    id="visuaCedulaR">

                            </div>
                            <div class="col-md-3 ml-auto">
                                <label class="col-sm-2 col-form-label">Genero:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaGeneroR"
                                    id="visuaGeneroR">

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4 ml-auto">
                                <label class="col-sm-2 col-form-label">Teléfono:</label>
                                <input type="text" readonly class="form-control-plaintext" name="visuaTelefonoR"
                                    id="visuaTelefonoR">

                            </div>
                            <div class="col-sm-4">
                                <label class="col-sm-3 col-form-label">Email:</label>
                                <input type="text" readonly class="form-control-plaintext input-lg" name="visuaEmailR"
                                    id="visuaEmailR">

                            </div>
                            <div class="col-sm-4">
                                <label class="col-sm-3 col-form-label">Dirección:</label>
                                <textarea type="text" readonly class="form-control-plaintext input-lg"
                                    name="visuaDireccionR" id="visuaDireccionR"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
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
