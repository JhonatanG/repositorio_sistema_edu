<?php
/*=============================================
CREAR MATRÍCULA CON HISTORIAL Y PROMOCIÓN DE NIVEL
=============================================*/
ControladorHistorial::ctrRegistrarMatricula();
$periodos = ControladorAnio::ctrListarAnio();
$estudiantes = ModeloMatricula::mdlEstudiantes();
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-file-signature"></i> Crear matrícula</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>inicio/">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>listaMatricula/">Matrículas</a></li>
            <li class="breadcrumb-item active">Crear</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <?php if(empty($periodos)){ ?>
        <div class="alert alert-warning">No hay un año lectivo activo. Actívelo en <a href="<?php echo SERVERURL; ?>anioL/">Parámetros → Año Lectivo</a>.</div>
      <?php }else{ ?>
      <form method="post" id="formMatricula">
        <?php echo Seguridad::campoToken(); ?>
        <div class="row">
          <div class="col-lg-7">
            <div class="card card-primary card-outline">
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>1. Año lectivo</label>
                    <select class="form-control" name="matriculaPeriodo" id="matriculaPeriodo" required>
                      <?php
                      foreach($periodos as $p){
                        echo '<option value="'.$p["id"].'">'.htmlspecialchars($p["nombre"]).'</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group col-md-8">
                    <label>2. Estudiante</label>
                    <select class="form-control select2bs4" name="matriculaEstudiante" id="matriculaEstudiante" style="width: 100%;" required>
                      <option value="">Buscar por apellido, nombre o cédula</option>
                      <?php
                      foreach($estudiantes as $e){
                        echo '<option value="'.$e["id"].'">'.htmlspecialchars($e["nombre"]).' — '.$e["cedula"].'</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <label>3. Curso</label>
                <div id="recomendacionNivel"></div>
                <div id="cursosMatricula" class="cursos-matricula text-muted">Elija el estudiante para ver los cursos que le corresponden.</div>

                <div class="custom-control custom-switch mt-3 d-none" id="cajaCambioNivel">
                  <input type="checkbox" class="custom-control-input" id="cambiarNivel">
                  <label class="custom-control-label" for="cambiarNivel">Cambiar de nivel (caso especial: traslado, pase de año, etc.)</label>
                </div>
                <div class="form-group mt-2 d-none" id="cajaMotivoNivel">
                  <textarea class="form-control" name="motivoCambioNivel" id="motivoCambioNivel" rows="2"
                    placeholder="Motivo del cambio de nivel (obligatorio). Ej.: Traslado desde otra institución con pase a Tercero EGB"></textarea>
                </div>

                <div class="form-group mt-3">
                  <label>Observación (opcional)</label>
                  <textarea class="form-control" name="matriculaObservacion" rows="2"></textarea>
                </div>
              </div>
              <div class="card-footer text-right">
                <a href="<?php echo SERVERURL; ?>listaMatricula/" class="btn btn-default">Cancelar</a>
                <button class="btn btn-primary" id="guardarMatricula" disabled><i class="fas fa-save"></i> Matricular</button>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-history"></i> Historial del estudiante</h3>
                <a href="#" id="verHistorialCompleto" class="ml-auto small d-none" target="_blank">Ver completo <i class="fas fa-external-link-alt"></i></a>
              </div>
              <div class="card-body" id="historialMatricula">
                <span class="text-muted">Elija un estudiante.</span>
              </div>
            </div>
          </div>
        </div>
      </form>
      <?php } ?>
    </div>
  </section>
</div>
