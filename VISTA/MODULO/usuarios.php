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
          <h1><i class="fas fa-users"></i> Administrar Usuarios</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Administrar Usuarios</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>


  <section class="content">
    <div class="card">
      <div class="card-header">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarUsuario">
          <i class="fas fa-user-plus"></i> Agregar Usuario</button>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped dt-responsive" id="tablas">
          <thead>
            <tr>
              <th style="width: 8px;">#</th>
              <th>Nombre</th>
              <th>Usuario</th>
              <th>Foto</th>
              <th>Rol</th>
              <th>Estado</th>
              <th>Último Login</th>
              <th class="no-exportar">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
          $item=null;
          $valor=null;
          $usuarios = ControladorUsuarios::ctrMostrarUsuarios($item,$valor);
          $cont=0;
          foreach ($usuarios as $usu) {
              $cont=$cont+1;
            echo '  <tr>
            <td>'.$cont.'</td>
            <td>'.$usu["nombre"].'</td>
            <td>'.$usu["usuario"].'</td>';
            if($usu["foto"]){
                echo'<td><img src="'.SERVERURL.''.$usu["foto"].'" class="img-thumbnail" alt="usuario" width="40px"></td>';

            }else{
                echo'<td><img src="'.SERVERURL.'VISTA/dist/img/defecto.jpg" class="img-thumbnail" alt="usuario" width="40px"></td>';

            }
            
            echo '<td>'.$usu["rol"].'</td>';
            if($usu["estado"]!=0){
              echo' <td><button class="btn btn-success btn-xs  btnActivar" idUsuario="'.$usu["id"].'"
            estadoUsuario="0">Activo</button></td>';
            }else{
              echo' <td><button class="btn btn-danger btn-xs  btnActivar" idUsuario="'.$usu["id"].'"
            estadoUsuario="1">Inactivo</button></td>';
            }
           echo' <td>'.($usu["ultimo_login"] ? $usu["ultimo_login"] : 'Nunca').'</td>
            <td>
              <div class="btn-group">
                <button class="btn btn-warning btnEditarUsuario" idUsuario="'.$usu["id"].'" data-toggle="modal" data-target="#modalEditarUsuario">
                <i class="fas fa-user-edit"></i></button>
                <button class="btn btn-danger btnEliminarUsuario" idUsuario="'.$usu["id"].'" fotoUsuario="'.$usu["foto"].'" usuario="'.$usu["usuario"].'"><i class="fas fa-user-times"></i></button>
              </div>
            </td>

          </tr>
            ';
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
= MODAL AGREGAR USUARIO =
=============================================*/ -->
<div class="modal fade" id="modalAgregarUsuario" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header" style="background: #008080;">
          <h5 class="modal-title">Agregar Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <!-- ENTRADA PARA EL NOMBRE -->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
              <input type="text" class="form-control input-lg" name="nuevoNombre" placeholder="Ingresar nombre"
                required>
            </div>
          </div>
          <!-- ENTRADA PARA EL USUARIO -->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input type="text" class="form-control input-lg" name="nuevoUsuario" id="nuevoUsuario"
                placeholder="Ingresar usuario" required>
            </div>
          </div>
          <!-- ENTRADA PARA LA CONTRASEÑA -->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" class="form-control input-lg" name="nuevoPassword"
                placeholder="Ingresar la contraseña" id="txtPassword" required>
            </div>
            <div id="strengthMessage"></div>
          </div>
          <!-- ENTRADA PARA EL ROL-->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-users"></i></span>
              <select name="nuevoRol" id="" class="form-control input-lg" required>
                <option value="">Seleccionar Rol</option>
                <option value="Administrador">Administrador</option>
                <option value="Secretaría">Secretaría</option>
              </select>
            </div>
          </div>
          <!-- ENTRADA PARA LA FOTO-->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-image"></i></span>
              <div class="custom-file">
                <input type="file" name="nuevaFoto" class="custom-file-input nuevaFoto" required>
                <label class="custom-file-label" for="imagen">Seleccionar Imagen</label>
              </div>
            </div>
            <p class="help-block"><small> Peso máximo de la foto 2MB</small> </p>
            <img src="<?php echo SERVERURL;  ?>VISTA/img/anonymous.png" class="img-thumbnail previsualizarC" width="100px">
          </div>
          <!-- PIE MODAL -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
          <!-- GUARDAR DATOS -->
          <?php
$crear = new ControladorUsuarios();
$crear->ctrCrearUsuarios();

 ?>

      </form>
    </div>
  </div>
</div>
</div>

<!-- 
/*=============================================
=          MODAL  EDITAR   USUARIO =
=============================================*/ -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #008080;">
        <h5 class="modal-title">Editar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <!-- ENTRADA PARA EL NOMBRE -->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
              <input type="text" class="form-control input-lg" id="editarNombre" name="editarNombre" value="" required>
            </div>
          </div>
          <!-- ENTRADA PARA EL USUARIO -->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input type="text" class="form-control input-lg" id="editarUsuario" name="editarUsuario" value=""
                readonly>
            </div>
          </div>
          <!-- ENTRADA PARA LA CONTRASEÑA -->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" class="form-control input-lg" name="editarPassword"
                placeholder="Ingresar la nueva contraseña">
            </div>
          </div>
          <!-- ENTRADA PARA LA ROL-->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-users"></i></span>
              <select name="editarRol" class="form-control input-lg" required>
                <option value="" id="editarRol"></option>
                <option value="Administrador">Administrador</option>
                <option value="Secretaría">Secretaría</option>
              </select>
            </div>
          </div>
          <!-- ENTRADA PARA LA FOTO-->
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-image"></i></span>
              <div class="custom-file">
                <input type="file" name="editarFoto" class="custom-file-input editarFoto">
                <label class="custom-file-label" for="exampleInputFile">Seleccionar Imagen</label>
              </div>
            </div>
            <p class="help-block"><small> Peso máximo de la foto 2MB</small> </p>
            <img src="" class="img-thumbnail previsualizar" width="100px">
          </div>

          <!-- PIE MODAL -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Modificar Cambios</button>
          </div>
          <!-- MODIFICAR DATOS -->
          <?php
      $editar = new ControladorUsuarios();
      $editar->ctrEditarUsuario();

      ?>

        </form>
      </div>
    </div>
  </div>
</div>´
<?php 
$borrarUsuario = new ControladorUsuarios();
$borrarUsuario->ctrBorrarUsuario();
?>