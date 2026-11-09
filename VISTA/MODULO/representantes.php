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
                    <h1> <i class="fab fa-black-tie"></i>
                        Representantes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Representantes</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarRepresentante">
                    <i class="fas fa-plus-square"></i> Agregar Representantes
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablaboton">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Cédula</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Usuario</th>
                            <th>Género</th>
                
                            <th>Telefono</th>

                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$item = "";
$valor = "";

$Representante = ControladorRepresentantes::ctrMostrarRepresentantes($item, $valor);
$cont = 0;
foreach ($Representante as $a) {
    $cont = $cont + 1;
    echo '<tr>
                         <td>' . $cont . '</td>
                         <td>' . $a["cedula"] . '</td>
                        <td>' . $a["nombres"] . '</td>
                        <td>' . $a["apellidos"] . '</td>
                        <td>' . $a["usuario"] . '</td>
                        <td>' . $a["genero"] . '</td>
              
                        <td>' . $a["telefono"] . '</td>'
                    ;

    echo '<td>
                        <div class="btn-group">

                          <button class="btn btn-warning btnEditarRepresentante" idRepresentante="' . $a["id"] . '" data-toggle="modal"  data-target="#modalEditarRepresentante">
                          <i class="fas fa-edit"></i></butoon>
                          <button class="btn btn-danger btnEliminarRepresentante" idRepresentante="' . $a["id"] . '" ><i class="fas fa-trash"></i></button>
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
                                    value="M" required>
                                <label class="form-check-label" for="nuevoGenero">Masculino</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="nuevoGeneroR" id="nuevoGeneroR"
                                    value="F">
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
                    <!-- ENTRADA PARA EL USUARIO -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevoUsuarioR" id="nuevoUsuarioR"
                                placeholder="Usuario" readonly>
                        </div>
                    </div>
                    <!-- ENTRADA PARA LA CONTRASEÑA -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control input-lg" name="nuevaPasswordR"
                                placeholder="Ingresar la contraseña" id="nuevaPasswordR" minlength="8"
                                autocomplete="off" required>
                        </div>
                        <div id="strengthMessage"></div>
                    </div>
                    <!-- ENTRADA PARA EL ROL OCULTO-->
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control input-lg" name="nuevoRolR" id="nuevoRolR"
                                value="Representante" hidden>
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

<!--
/*=============================================
MODAL EDITAR Representante
=============================================*/ -->
<div class="modal fade" id="modalEditarRepresentante" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Representantes</h5>
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
                            <input type="text" class="form-control input-lg" name="editarCedulaR" id="editarCedulaR"
                                placeholder="Ingrese Cédula de Identidad" pattern="[0-9]{10}" readonly>
                        </div>
                    </div>
                    <!-- NOMBRES -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" class="form-control input-lg" name="editarNombreR" id="editarNombreR"
                                readonly>
                        </div>
                    </div>
                    <!-- APELLIDOS -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" class="form-control input-lg" name="editarApellidoR" id="editarApellidoR"
                                readonly>
                        </div>
                    </div>
                    <!-- GENERO -->
                    <!-- <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                            <input type="text" class="form-control input-lg" name="editarGeneroR" id="editarGeneroR"
                                readonly>
                        </div>
                    </div> -->
                    <!-- ENTRADA PARA LA CONTRASEÑA -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control input-lg" name="editarPassword"
                                placeholder="Ingresar nueva contraseña" id="editarPassword" minlength="8"
                                autocomplete="off">
                        </div>
                        <div id="editarMensaje"></div>
                    </div>
                    <!-- EMAIL -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="email" class="form-control input-lg" name="editarEmailR" id="editarEmailR"
                                placeholder="Ingrese correo electrónico" required>
                        </div>
                    </div>
                    <!-- TELEFONO -->

                    <div class="form-group">
                        <div class="input-group">

                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control" data-inputmask='"mask": "999 999-9999"' data-mask
                                placeholder="Ingrese número telefónico" name="editarTelefonoR" id="editarTelefonoR"
                                required>
                        </div>
                        <!-- DIRECCION-->
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control input-lg" name="editarDireccionR" id="editarDireccionR"
                                placeholder="Ingrese dirección"></textarea>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </div>
                <?php
$editar = new ControladorRepresentantes();
$editar->ctrEditarRepresentante();
?>
            </form>
        </div>
    </div>
</div>
<?php
$eliminar = new ControladorRepresentantes();
$eliminar->ctrBorrarRepresentante();
?>