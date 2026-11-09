<?php
/*=============================================
HORARIO POR CURSO (NIVEL + PARALELO) CON ARRASTRAR Y SOLTAR
La ruta es horarios/{idCurso}/
=============================================*/
$idCurso = isset($rutas[1]) ? (int)$rutas[1] : 0;
$cursos = ModeloHorario::mdlCursosActivos();
$curso = null;
foreach($cursos as $c){
  if($c["id"] == $idCurso){
    $curso = $c;
  }
}
?>
<link rel="stylesheet" href="<?php echo SERVERURL; ?>VISTA/plugins/fullcalendar/main.min.css">
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-clock"></i> Horarios por curso</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Horarios</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <div class="row align-items-end">
            <div class="col-md-5">
              <label>Curso (nivel y paralelo)</label>
              <select class="form-control select2bs4" id="cursoHorario" style="width: 100%;">
                <option value="">Seleccionar el curso</option>
                <?php
                foreach($cursos as $c){
                  echo '<option value="'.$c["id"].'"'.($c["id"]==$idCurso ? ' selected' : '').'>'.htmlspecialchars($c["curso"]).'</option>';
                }
                ?>
              </select>
            </div>
            <div class="col-md-7 text-muted small mt-2 mt-md-0">
              <i class="fas fa-mouse-pointer"></i> <b>Clic o arrastre en un espacio vacío</b> para agregar una clase ·
              <b>arrastre un bloque</b> para cambiar el día o la hora ·
              <b>estire su borde inferior</b> para cambiar la duración ·
              <b>clic en un bloque</b> para cambiarlo o borrarlo.
            </div>
          </div>
        </div>
      </div>

      <?php if(empty($cursos)){ ?>
        <div class="alert alert-warning">No hay cursos en el año lectivo activo. Créelos en <a href="<?php echo SERVERURL; ?>gparalelos/">Cupos</a>.</div>
      <?php }else if($curso){
        $clases = ModeloHorario::mdlClasesCurso($idCurso);
      ?>
      <div class="row">
        <div class="col-xl-9">
          <div class="card">
            <div class="card-body p-2">
              <?php if(empty($clases)){ ?>
                <div class="alert alert-warning m-2">
                  Este curso no tiene asignaturas con docente. Asígnelas primero en
                  <a href="<?php echo SERVERURL; ?>docgrado/">Docente → Clases</a>.
                </div>
              <?php } ?>
              <div id="calendarioHorario"></div>
            </div>
          </div>
        </div>
        <div class="col-xl-3">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Asignaturas de <?php echo htmlspecialchars($curso["curso"]); ?></h3></div>
            <div class="card-body p-2" id="resumenHorario"></div>
          </div>
        </div>
      </div>
      <?php }else{ ?>
        <div class="text-center text-muted py-5"><i class="fas fa-calendar-alt fa-3x mb-3"></i><br>Elija un curso para ver y armar su horario.</div>
      <?php } ?>
    </div>
  </section>
</div>

<?php if($curso){ ?>
<!-- MODAL PARA AGREGAR O CAMBIAR UN BLOQUE -->
<div class="modal fade" id="modalBloque" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="tituloBloque">Agregar clase</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="bloqueId">
        <div class="form-group">
          <label>Asignatura y docente</label>
          <select class="form-control" id="bloqueClase">
            <?php
            foreach($clases as $c){
              echo '<option value="'.$c["id"].'">'.htmlspecialchars($c["materia"]." — ".$c["docente"]).'</option>';
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Día</label>
          <select class="form-control" id="bloqueDia">
            <?php foreach(ControladorHorario::dias() as $n=>$d){ echo '<option value="'.$n.'">'.$d.'</option>'; } ?>
          </select>
        </div>
        <div class="form-row">
          <div class="form-group col-6">
            <label>Hora inicio</label>
            <input type="time" class="form-control" id="bloqueInicio">
          </div>
          <div class="form-group col-6">
            <label>Hora fin</label>
            <input type="time" class="form-control" id="bloqueFin">
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" id="borrarBloque"><i class="fas fa-trash"></i> Borrar</button>
        <div>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="guardarBloque"><i class="fas fa-save"></i> Guardar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo SERVERURL; ?>VISTA/plugins/fullcalendar/main.min.js"></script>
<script src="<?php echo SERVERURL; ?>VISTA/plugins/fullcalendar/locales/es.js"></script>
<script>
var CURSO_HORARIO = <?php echo (int)$idCurso; ?>;
var CLASES_HORARIO = <?php echo json_encode($clases, JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="<?php echo SERVERURL; ?>VISTA/dist/js/horario.js"></script>
<?php } ?>
<script>
$(function () {
  $("#cursoHorario").on("change", function () {
    window.location = SERVERURL + "horarios/" + (this.value ? this.value + "/" : "");
  });
});
</script>
