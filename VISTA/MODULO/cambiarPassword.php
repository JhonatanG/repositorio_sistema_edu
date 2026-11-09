<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-key"></i> Cambiar contraseña</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Cambiar contraseña</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">

          <?php if(!empty($_SESSION["cambiarPassword"])){ ?>
          <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Por seguridad debe cambiar su contraseña inicial antes de usar el sistema.
          </div>
          <?php } ?>

          <div class="card card-primary card-outline">
            <form method="post" autocomplete="off">
              <?php echo Seguridad::campoToken(); ?>
              <div class="card-body">
                <div class="form-group">
                  <label for="passwordAnterior">Contraseña actual</label>
                  <input type="password" class="form-control" id="passwordAnterior" name="passwordAnterior" required>
                </div>
                <div class="form-group">
                  <label for="nuevaPasswordPropia">Contraseña nueva</label>
                  <input type="password" class="form-control" id="nuevaPasswordPropia" name="nuevaPasswordPropia"
                    minlength="8" pattern="(?=.*[A-Za-z])(?=.*[0-9]).{8,}" required>
                  <small class="form-text text-muted">Mínimo 8 caracteres, con letras y números, distinta a su usuario.</small>
                </div>
                <div class="form-group">
                  <label for="confirmarPasswordPropia">Repita la contraseña nueva</label>
                  <input type="password" class="form-control" id="confirmarPasswordPropia" name="confirmarPasswordPropia" minlength="8" required>
                </div>
              </div>
              <div class="card-footer text-right">
                <a href="<?php echo SERVERURL; ?>salir/" class="btn btn-default">Salir</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
              </div>
              <?php ControladorUsuarios::ctrCambiarPassword(); ?>
            </form>
          </div>

        </div>
      </div>
    </div>
  </section>
</div>
