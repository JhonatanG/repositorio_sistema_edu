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
                    <h1><i class="fas fa-user-graduate"></i>

                        Agregar Estudiante</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Agregar Estudiante</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <div class="card card-danger">
                    <form method="post">
                        <div class="card-body">

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control input-lg" name="nuevaCedulaE"
                                        id="nuevaCedulaE" placeholder="Ingrese Cédula de Identidad" pattern="[0-9]{10}"
                                        title="Debe tener 10 números" required>
                                </div>
                            </div>
                            <!-- NOMBRES -->
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                    <input type="text" class="form-control input-lg" name="nuevoNombreE"
                                        id="nuevoNombreE" placeholder="Ingrese Nombres" required>
                                </div>
                            </div>
                            <!-- APELLIDOS -->
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                    <input type="text" class="form-control input-lg" name="nuevoApellidoE"
                                        id="nuevoApellidoE" placeholder="Ingrese Apellidos" required>
                                </div>
                            </div>
                            <!-- GENERO -->
                            <div class="form-group">
                                <span class=""><strong>Genero: </strong></span>
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="nuevoGeneroF" name="nuevoGeneroE" value="F">
                                        <label for="nuevoGeneroF">
                                            Femenino
                                        </label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="nuevoGeneroM" name="nuevoGeneroE" value="M">
                                        <label for="nuevoGeneroM">
                                            Masculino
                                        </label>
                                    </div>

                                </div>

                            </div>
                            <!-- EMAIL -->
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    <input type="email" class="form-control input-lg" name="nuevoEmailE"
                                        id="nuevoEmailE" placeholder="Ingrese correo electrónico" required>
                                </div>
                            </div>
                            <!-- TELEFONO -->
                            <div class="form-group">
                                <div class="input-group">

                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" data-inputmask='"mask": "999 999-9999"'
                                        data-mask placeholder="Ingrese número telefónico" name="nuevoTelefonoE"
                                        id="nuevoTelefonoE" required>
                                </div>
                                <!-- DIRECCION-->
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea class="form-control input-lg" name="nuevaDireccionE" id="nuevaDireccionE"
                                        placeholder="Ingrese dirección"></textarea>
                                </div>
                            </div>
                            <!-- FECHA DE NACIMIENTO -->
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control input-lg" data-inputmask-alias="datetime"
                                        data-inputmask-inputformat="yyyy/mm/dd" data-mask name="nuevaFechaE"
                                        id="nuevaFechaE" placeholder="Ingrese fecha de nacimiento" required>
                                </div>

                            </div>
                              <!-- ENTRADA PARA EL USUARIO -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevoUsuarioE" id="nuevoUsuarioE"
                                placeholder="Usuario" readonly>
                        </div>
                    </div>
                    <!-- ENTRADA PARA LA CONTRASEÑA -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control input-lg" name="nuevaPasswordE"
                                placeholder="Ingresar la contraseña" id="nuevaPasswordE" minlength="8"
                                autocomplete="off" required>
                        </div>
                        <div id="strengthMessage"></div>
                    </div>
                    <!-- ENTRADA PARA EL ROL OCULTO-->
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control input-lg" name="nuevoRolE" id="nuevoRolE"
                                value="Estudiante" hidden>
                        </div>
                    </div>
   <!-- REPRESENTANTE -->
                            <div class="form-group">

                                <span class=""><strong>Representante: </strong></span>

                                <div class="select2-purple">
                                    <div class="input-group">
                                        <select class="select2" multiple="multiple"
                                            data-placeholder="Seleccionar Representante"
                                            data-dropdown-css-class="select2-purple" style="width: 70%;"
                                            name="nuevoRepresentanteE" required>

                                            <?php
                               
                               $representante=ControladorRepresentantes::ctrListarRepresentante();
                             
                               foreach($representante as $key=>$value){
                                   
                                echo'<option value="'.$value["id"].'">'.$value["cedula"].': '.$value["nombre"].'</option>';
                                     
                              }
                                          ?>

                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-success" data-toggle="modal"
                                                data-target="#modalAgregarRepresentante">
                                                <i class="fas fa-plus-square"></i> Agregar Representante
                                            </button>
                                        </div>
                                    </div>

                                </div>


                            </div>



                            <div class="modal-footer">
                                <a type="submit" class="btn btn-danger" href="<?php echo SERVERURL;  ?>alumnos/">Cancelar</a>
                                <button class="btn btn-primary">Guardar</button>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <?php
                        
                        $crearE = new ControladorAlumnos();
                        $crearE-> ctrCrearAlumno();

                        ?>

                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->



        </div>

</div>


</section>
</div>
<!-- /*=============================================
MODAL AGREGAR Representante
=============================================*/ -->
<div class="modal fade" id="modalAgregarRepresentante" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Agregar Representantes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">
                    <!-- CEDULA Representante -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevaCedulaR" id="nuevaCedulaR"
                                placeholder="Ingrese Cédula de Identidad" pattern="[0-9]{10}"
                                title="Debe tener 10 números" required>
                        </div>
                    </div>
                    <!-- NOMBRES -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevoNombreR" id="nuevoNombreR"
                                placeholder="Ingrese Nombres" required>
                        </div>
                    </div>
                    <!-- APELLIDOS -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevoApellidoR" id="nuevoApellidoR"
                                placeholder="Ingrese Apellidos" required>
                        </div>
                    </div>
                    <!-- GENERO -->
                    <div class="form-group">
                        <span class=""><strong>Genero: </strong></span>
                        <div class="input-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="nuevoGeneroR" id="nuevoGeneroR"
                                    value="Masculino" required>
                                <label class="form-check-label" for="nuevoGenero">Masculino</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="nuevoGeneroR" id="nuevoGeneroR"
                                    value="Femenino">
                                <label class="form-check-label" for="nuevoGenero">Femenino</label>
                            </div>
                        </div>
                    </div>
                    <!-- EMAIL -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="email" class="form-control input-lg" name="nuevoEmailR" id="nuevoEmailR"
                                placeholder="Ingrese correo electrónico" required>
                        </div>
                    </div>
                    <!-- TELEFONO -->
                    <div class="form-group">
                        <div class="input-group">

                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control" data-inputmask='"mask": "999 999-9999"' data-mask
                                placeholder="Ingrese número telefónico" name="nuevoTelefonoR" id="nuevoTelefonoR"
                                required>
                        </div>
                        <!-- DIRECCION-->
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control input-lg" name="nuevaDireccionR" id="nuevaDireccionR"
                                placeholder="Ingrese dirección"></textarea>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </div>
                <?php
$crear = new ControladorRepresentantes();
$crear->ctrCrearRepresentante();
?>
            </form>
        </div>
    </div>
</div>