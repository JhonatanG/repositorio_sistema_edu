<?php
 if($_SESSION["rol"]=="Docente"
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
                    <h1> <i class="fas fa-chalkboard-teacher"></i>
                        Docentes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Docentes</li>
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
               echo' <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarDocente">
                    <i class="fas fa-plus-square"></i> Agregar Docentes
                </button>';}
                ?>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablaboton">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Cédula</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                             <th>Email</th>
                            <th>Telefono</th>
                            <th>Estado</th>
                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $item="";
                $valor="";

           $Docente = ControladorDocentes::ctrMostrarDocentes($item,$valor);
                $cont=0;
        foreach($Docente as $a){
            $cont=$cont+1;
                 echo '<tr>
                         <td>'.$cont.'</td>
                         <td>'.$a["cedula"].'</td>
                        <td>'.$a["nombres"].'</td>
                        <td>'.$a["apellidos"].'</td>
                        <td>'.$a["email"].'</td>
                        <td>'.$a["telefono"].'</td>';
                        if($a["estado"]!=0){
                            echo' <td><button class="btn btn-success btn-xs  btnActivarD" idDocente="'.$a["id"].'"
                          estadoDocente="0">Activo</button></td>';
                          }else{
                            echo' <td><button class="btn btn-danger btn-xs  btnActivarD" idDocente="'.$a["id"].'"
                          estadoDocente="1">Inactivo</button></td>';
                          };
                    echo'<td>
                        <div class="btn-group">
                        <button class="btn btn-primary btnFicha" data-tipo="D" data-id="'.$a["id"].'" title="Ver ficha"><i class="fas fa-eye"></i></button>
                          <button class="btn btn-warning btnEditarDocente" idDocente="'.$a["id"].'" data-toggle="modal"  data-target="#modalEditarDocente" title="Editar">
                          <i class="fas fa-edit"></i></button>';
                        /*EDITAR: ADMINISTRADOR Y SECRETARÍA. BORRAR: SOLO EL ADMINISTRADOR*/
                        if($_SESSION["rol"]=="Administrador"){
                            echo '<button class="btn btn-danger btnEliminarDocente" idDocente="'.$a["id"].'" usuario="'.$a["usuario"].'" title="Borrar"><i class="fas fa-trash"></i></button>';
                        }
                        echo '</div>
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
MODAL AGREGAR Docente
=============================================*/ -->
<div class="modal fade" id="modalAgregarDocente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Agregar Docentes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- CEDULA DOCENTE -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevaCedulaD" id="nuevaCedulaD"
                                placeholder="Ingrese Cédula de Identidad" pattern="[0-9]{10}"
                                title="Debe tener 10 números" required>
                        </div>
                    </div>
                    <!-- NOMBRES -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevoNombreD" id="nuevoNombreD"
                                placeholder="Ingrese Nombres" required>
                        </div>
                    </div>
                    <!-- APELLIDOS -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevoApellidoD" id="nuevoApellidoD"
                                placeholder="Ingrese Apellidos" required>
                        </div>
                    </div>
                    <!-- GENERO -->
                    <div class="form-group">
                        <span class=""><strong>Genero: </strong></span>
                        <div class="input-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="nuevoGeneroD" id="nuevoGeneroD"
                                    value="M" required>
                                <label class="form-check-label" for="nuevoGenero">Masculino</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="nuevoGeneroD" id="nuevoGeneroD"
                                    value="F">
                                <label class="form-check-label" for="nuevoGenero">Femenino</label>
                            </div>
                        </div>
                    </div>
                    <!-- EMAIL -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="email" class="form-control input-lg" name="nuevoEmailD" id="nuevoEmailD"
                                placeholder="Ingrese correo electrónico" required>
                        </div>
                    </div>
                    <!-- TELEFONO -->
                    <div class="form-group">
                        <div class="input-group">

                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control" data-inputmask='"mask": "999 999-9999"' data-mask
                                placeholder="Ingrese número telefónico" name="nuevoTelefonoD" id="nuevoTelefonoD"
                                required>
                        </div>
                        <!-- DIRECCION-->
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control input-lg" name="nuevaDireccionD" id="nuevaDireccionD"
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
                                data-inputmask-inputformat="yyyy/mm/dd" data-mask name="nuevaFechaD" id="nuevaFechaD"
                                placeholder="Ingrese fecha de nacimiento" required>
                        </div>

                    </div>

                    <!-- ENTRADA PARA EL USUARIO -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control input-lg" name="nuevoUsuarioD" id="nuevoUsuarioD"
                                placeholder="Usuario" readonly>
                        </div>
                    </div>
                    <!-- ENTRADA PARA LA CONTRASEÑA -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control input-lg" name="nuevaPasswordD"
                                placeholder="Ingresar la contraseña" id="nuevaPasswordD" minlength="8"
                                autocomplete="off" required>
                        </div>
                        <div id="strengthMessage"></div>
                    </div>
                    <!-- ENTRADA PARA EL ROL OCULTO-->
                    <div class="form-group">
                        <div class="input-group">

                            <input type="text" class="form-control input-lg" name="nuevoRolD" id="nuevoRolD"
                                value="Docente" hidden>
                        </div>
                    </div>
                    <!-- ENTRADA PARA LA FOTO-->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                            <div class="custom-file">
                                <input type="file" name="nuevaFotoD" id="nuevaFotoD"
                                    class="custom-file-input nuevaFotoD" required>
                                <label class="custom-file-label" for="imagen">Seleccionar Imagen</label>
                            </div>
                        </div>
                        <p class="help-block"><small> Peso máximo de la foto 2MB</small> </p>
                        <img src="<?php echo SERVERURL;  ?>VISTA/img/anonymous.png" class="img-thumbnail previsualizarCD" width="100px">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
                  $crear=new ControladorDocentes();
                  if($_SESSION["rol"]=="Administrador"){ $crear->ctrCrearDocentes(); }


?>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- 
/*=============================================
MODAL EDITAR Docente 
=============================================*/ -->
<div class="modal fade" id="modalEditarDocente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Docente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- CEDULA DOCENTE -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control input-lg" name="editarCedulaD" id="editarCedulaD"
                                readonly>
                        </div>
                    </div>
                    <!-- NOMBRES -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" class="form-control input-lg" name="editarNombreD" id="editarNombreD"
                                required maxlength="60" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{2,60}" title="Solo letras">
                        </div>
                    </div>
                    <!-- APELLIDOS -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" class="form-control input-lg" name="editarApellidoD" id="editarApellidoD"
                               required maxlength="60" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{2,60}" title="Solo letras">
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
                            <input type="email" class="form-control input-lg" name="editarEmailD" id="editarEmailD"
                                placeholder="Ingrese correo electrónico" required>
                        </div>
                    </div>
                    <!-- TELEFONO -->
                    <div class="form-group">
                        <div class="input-group">

                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control" data-inputmask='"mask": "999 999-9999"' data-mask
                                placeholder="Ingrese número telefónico" name="editarTelefonoD" id="editarTelefonoD"
                                required>
                        </div>
                      
                    </div>
                      <!-- DIRECCION-->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control input-lg" name="editarDireccionD" id="editarDireccionD"
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

                    <!-- ENTRADA PARA EL USUARIO -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control input-lg" name="editarUsuarioD" id="editarUsuarioD"
                                placeholder="Usuario" readonly>
                        </div>
                    </div>
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
             
                    <!-- ENTRADA PARA LA FOTO-->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                            <div class="custom-file">
                                <input type="file" name="editarFotoD" id="editarFotoD"
                                    class="custom-file-input editarFotoD">
                                <label class="custom-file-label" for="imagen">Seleccionar Imagen</label>
                            </div>
                        </div>
                        <p class="help-block"><small> Peso máximo de la foto 2MB</small> </p>
                        <img src="<?php echo SERVERURL;  ?>VISTA/img/anonymous.png" class="img-thumbnail previsualizarD" width="100px">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
    
                </div>
                <?php
$editar=new ControladorDocentes();
$editar->ctrEditarDocente();

?>
            </form>
        </div>
    </div>
</div>
<?php
$borrarDocente= new ControladorDocentes();
$borrarDocente->ctrBorrarDocente();

?>
<?php include __DIR__."/ficha.php"; ?>
