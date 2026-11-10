<?php
/*=============================================
JUSTIFICAR FALTAS (SECRETARÍA)
Las faltas que registra el docente quedan como injustificadas;
Secretaría las justifica con un motivo y queda registrado quién y cuándo.
=============================================*/
ControladorAsistencia::ctrJustificar();
ControladorAsistencia::ctrQuitarJustificacion();

/*FILTROS: SE RECUERDAN DURANTE LA SESIÓN*/
date_default_timezone_set('America/Guayaquil');
$filtros = isset($_SESSION["filtros"]["justificarFaltas"]) ? $_SESSION["filtros"]["justificarFaltas"]
  : array("desde"=>date("Y-m-d", strtotime("-30 days")), "hasta"=>date("Y-m-d"), "curso"=>0, "estado"=>"FI");
if(isset($_POST["filtrarFaltas"])){
  $fecha = '/^\d{4}-\d{2}-\d{2}$/';
  $filtros = array(
    "desde"=>preg_match($fecha, $_POST["desde"]) ? $_POST["desde"] : $filtros["desde"],
    "hasta"=>preg_match($fecha, $_POST["hasta"]) ? $_POST["hasta"] : $filtros["hasta"],
    "curso"=>(int)$_POST["curso"],
    "estado"=>$_POST["estado"]=="FJ" ? "FJ" : "FI"
  );
  $_SESSION["filtros"]["justificarFaltas"] = $filtros;
}
$faltas = ModeloAsistencia::mdlFaltas($filtros);
$pendientes = $filtros["estado"]=="FI";
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-file-medical"></i> Justificar faltas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Justificar faltas</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <form method="post" class="row align-items-end">
            <input type="hidden" name="filtrarFaltas" value="1">
            <div class="col-md-3 col-6 form-group">
              <label>Desde</label>
              <input type="date" class="form-control" name="desde" value="<?php echo $filtros["desde"]; ?>">
            </div>
            <div class="col-md-3 col-6 form-group">
              <label>Hasta</label>
              <input type="date" class="form-control" name="hasta" value="<?php echo $filtros["hasta"]; ?>">
            </div>
            <div class="col-md-3 form-group">
              <label>Curso</label>
              <select class="form-control" name="curso">
                <option value="0">Todos los cursos</option>
                <?php
                foreach(ModeloHorario::mdlCursosActivos() as $c){
                  echo '<option value="'.$c["id"].'"'.($c["id"]==$filtros["curso"] ? ' selected' : '').'>'.htmlspecialchars($c["curso"]).'</option>';
                }
                ?>
              </select>
            </div>
            <div class="col-md-3 form-group">
              <button class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Aplicar filtros</button>
            </div>
            <div class="col-12">
              <div class="btn-group btn-group-toggle pestañas-faltas" data-toggle="buttons">
                <label class="btn btn-outline-danger<?php echo $pendientes ? ' active' : ''; ?>">
                  <input type="radio" name="estado" value="FI" onchange="this.form.submit()"<?php echo $pendientes ? ' checked' : ''; ?>> <i class="fas fa-times"></i> Pendientes de justificar
                </label>
                <label class="btn btn-outline-primary<?php echo !$pendientes ? ' active' : ''; ?>">
                  <input type="radio" name="estado" value="FJ" onchange="this.form.submit()"<?php echo !$pendientes ? ' checked' : ''; ?>> <i class="fas fa-file-medical"></i> Justificadas
                </label>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <?php if($pendientes && !empty($faltas)){ ?>
          <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
            <span class="text-muted"><b><?php echo count($faltas); ?></b> falta(s) sin justificar en el periodo elegido.</span>
            <button type="button" class="btn btn-primary" id="justificarSeleccionadas" disabled>
              <i class="fas fa-file-medical"></i> Justificar seleccionadas (<span id="cuentaSeleccionadas">0</span>)
            </button>
          </div>
          <?php } ?>
          <table class="table table-bordered table-striped dt-responsive" id="tablaboton" data-page-length="50">
            <thead>
              <tr>
                <?php if($pendientes){ ?><th class="no-exportar" style="width: 30px;"><input type="checkbox" id="marcarTodas" title="Marcar todas"></th><?php } ?>
                <th>Fecha</th>
                <th>Estudiante</th>
                <th>Curso</th>
                <th>Asignatura</th>
                <th>Docente</th>
                <?php if(!$pendientes){ ?><th>Motivo</th><th>Justificó</th><?php } ?>
                <th class="no-exportar">Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($faltas as $f){
                echo '<tr>';
                if($pendientes){
                  echo '<td><input type="checkbox" class="chkFalta" value="'.$f["id"].'" data-detalle="'.htmlspecialchars($f["estudiante"]." · ".$f["materia"]." · ".date("d/m/Y", strtotime($f["fecha"]))).'"></td>';
                }
                echo '<td data-order="'.$f["fecha"].'">'.date("d/m/Y", strtotime($f["fecha"])).'</td>
                  <td><b>'.htmlspecialchars($f["estudiante"]).'</b><br><small class="text-muted">'.htmlspecialchars($f["cedula"]).'</small></td>
                  <td>'.htmlspecialchars($f["curso"]).'</td>
                  <td>'.htmlspecialchars($f["materia"]).'</td>
                  <td>'.htmlspecialchars($f["docente"]).'</td>';
                if($pendientes){
                  echo '<td><button type="button" class="btn btn-sm btn-primary btnJustificar" data-id="'.$f["id"].'"
                    data-detalle="'.htmlspecialchars($f["estudiante"]." · ".$f["materia"]." · ".date("d/m/Y", strtotime($f["fecha"]))).'">
                    <i class="fas fa-file-medical"></i> Justificar</button></td>';
                }else{
                  echo '<td>'.htmlspecialchars($f["justificacion"]).'</td>
                    <td>'.htmlspecialchars($f["justificado_por"]).'<br><small class="text-muted">'.($f["fecha_justificacion"] ? date("d/m/Y H:i", strtotime($f["fecha_justificacion"])) : '').'</small></td>
                    <td><button type="button" class="btn btn-sm btn-outline-danger btnQuitarJustificacion" data-id="'.$f["id"].'"><i class="fas fa-undo"></i> Quitar</button></td>';
                }
                echo '</tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- MODAL: MOTIVO DE LA JUSTIFICACIÓN -->
<div class="modal fade" id="modalJustificar" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" id="formJustificar">
        <?php echo Seguridad::campoToken(); ?>
        <div class="modal-header bg-primary">
          <h5 class="modal-title"><i class="fas fa-file-medical"></i> Justificar falta</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div id="detalleJustificar" class="detalle-justificar mb-3"></div>
          <div id="idsJustificar"></div>
          <label>Motivo</label>
          <div class="mb-2 motivos-rapidos">
            <button type="button" class="btn btn-xs btn-outline-secondary">Certificado médico</button>
            <button type="button" class="btn btn-xs btn-outline-secondary">Calamidad doméstica</button>
            <button type="button" class="btn btn-xs btn-outline-secondary">Permiso del representante</button>
            <button type="button" class="btn btn-xs btn-outline-secondary">Representación institucional</button>
          </div>
          <textarea class="form-control" name="motivo" id="motivoJustificar" rows="3" minlength="5" required
            placeholder="Ej.: Certificado médico del Centro de Salud, 2 días de reposo"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Justificar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(function () {
  function filasMarcadas() {
    return $.fn.dataTable.isDataTable("#tablaboton") ? $("#tablaboton").DataTable().$("input.chkFalta:checked") : $("input.chkFalta:checked");
  }
  function actualizarSeleccion() {
    var n = filasMarcadas().length;
    $("#cuentaSeleccionadas").text(n);
    $("#justificarSeleccionadas").prop("disabled", n === 0);
  }
  function abrirJustificar(items) {
    $("#idsJustificar").html(items.map(function (i) {
      return '<input type="hidden" name="idAsistenciaJ[]" value="' + i.id + '">';
    }).join(""));
    var lista = items.slice(0, 6).map(function (i) { return "<li>" + $("<div>").text(i.detalle).html() + "</li>"; }).join("");
    $("#detalleJustificar").html("<b>" + items.length + " falta(s):</b><ul class='mb-0'>" + lista +
      (items.length > 6 ? "<li>y " + (items.length - 6) + " más…</li>" : "") + "</ul>");
    $("#motivoJustificar").val("");
    $("#modalJustificar").modal("show");
  }
  $(document).on("change", "input.chkFalta", actualizarSeleccion);
  $(document).on("change", "#marcarTodas", function () {
    var marcar = this.checked;
    ($.fn.dataTable.isDataTable("#tablaboton") ? $("#tablaboton").DataTable().$("input.chkFalta") : $("input.chkFalta")).prop("checked", marcar);
    actualizarSeleccion();
  });
  $(document).on("click", ".btnJustificar", function () {
    abrirJustificar([{ id: $(this).data("id"), detalle: $(this).data("detalle") }]);
  });
  $("#justificarSeleccionadas").on("click", function () {
    abrirJustificar(filasMarcadas().map(function () {
      return { id: this.value, detalle: $(this).data("detalle") };
    }).get());
  });
  $(".motivos-rapidos button").on("click", function () {
    $("#motivoJustificar").val($(this).text()).focus();
  });
  $(document).on("click", ".btnQuitarJustificacion", function () {
    var id = $(this).data("id");
    Swal.fire({
      title: "¿Quitar la justificación?",
      text: "La falta volverá a quedar como injustificada.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonText: "Cancelar",
      confirmButtonText: "Sí, quitar"
    }).then(function (r) {
      if (r.value) enviarAccion("justificarFaltas", { idAsistenciaQ: id });
    });
  });
});
</script>
