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
                    <h1><i class="fas fa-user-graduate"></i>

                        Editar Estudiante</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Editar Estudiante</li>
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
                            <form method="post">

                                <?php
/*CONSULTA PREPARADA: EL ID DE LA URL YA NO SE PEGA DIRECTO EN EL SQL*/
$rutas = explode("/",$_GET["ruta"]);
$id = isset($rutas[1]) ? (int)$rutas[1] : 0;
foreach (ModeloAlumnos::mdlMostrarAlumnoEditar($id) as $a){
?>
                                <!-- CEDULA Estudiante -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="hidden" class="form-control input-lg" name=""
                                            value="<?php echo $a["id"] ?>" readonly>

                                    </div>
                                </div>
                                <!-- CEDULA Estudiante -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control input-lg" name="editarCedulaE"
                                            id="editarCedulaE" value="<?php echo $a["cedula"]?>" readonly>
                                    </div>
                                </div>
                                <!-- NOMBRES -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                        <input type="text" class="form-control input-lg" name="editarNombreE"
                                            id="editarNombreE" value="<?php echo htmlspecialchars($a["nombres"])?>" required maxlength="60" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{2,60}" title="Solo letras">
                                    </div>
                                </div>
                                <!-- APELLIDOS -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                        <input type="text" class="form-control input-lg" name="editarApellidoE"
                                            id="editarApellidoE" value="<?php echo htmlspecialchars($a["apellidos"])?>" required maxlength="60" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{2,60}" title="Solo letras">
                                    </div>
                                </div>
                                <!-- GENERO -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        <input type="text" class="form-control input-lg" name="editarGeneroE"
                                            id="editarGeneroE" value="<?php echo $a["genero"]?>" readonly>
                                    </div>
                                </div>

                                <!-- EMAIL -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                                        <input type="email" class="form-control input-lg" name="editarEmailE"
                                            id="editarEmailE" placeholder="Ingrese correo electrónico"
                                            value="<?php echo $a["email"]?>" required>
                                    </div>
                                </div>
                                <!-- TELEFONO -->
                                <div class="form-group">
                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" class="form-control" data-inputmask='"mask": "999 999-9999"'
                                            data-mask placeholder="Ingrese número telefónico" name="editarTelefonoE"
                                            id="editarTelefonoE" value="<?php echo $a["telefono"]?>" required>
                                    </div>
                                    </div>
                                    <div class="form-group">
                                     <!-- PASSWORD -->
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control input-lg" name="editarPassword"
                                                placeholder="Ingresar nueva contraseña" id="editarPassword"
                                                minlength="8" autocomplete="off">
                                        </div>
                                        <div id="editarMensaje"></div>
                                    </div>
                                    <!-- DIRECCION-->
                             
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <textarea class="form-control input-lg" name="editarDireccionE"
                                            id="editarDireccionE"
                                            placeholder="Ingrese dirección"><?php echo $a["direccion"]?></textarea>
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

                                    <div class="select2-purple">
                                        <div class="input-group">
                                            <select class="select2 input-lg" multiple="" style="width: 100%;"
                                                name="editarRepresentanteE">
                                                <option value="<?php echo $a["id_representante"]?> " selected>
                                                    <?php echo $a["representante"]?></option>
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
                                    <a type="submit" class="btn btn-danger"
                                        href="<?php echo SERVERURL;  ?>alumnos/">Cancelar</a>
                                    <button class="btn btn-primary">Guardar</button>
                                </div>

                        </div>
                        <?php } ?>

                        <?php
                        
                        $editarE = new ControladorAlumnos();
                        $editarE-> ctrEditarEstudiante();

                        ?>

                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->



        </div>
        <!-- /.col (right) -->
</div>

</section>
</div>