/*=============================================
CREAR MATRÍCULA: HISTORIAL Y NIVEL QUE CORRESPONDE
=============================================*/
var REC_MATRICULA = null;

function textoMatricula(t) { return $("<div>").text(t).html(); }

function pintarCursosMatricula(cursos) {
  var libre = $("#cambiarNivel").is(":checked");
  if (!cursos.length) {
    $("#cursosMatricula").addClass("text-muted").html("Este año lectivo no tiene cursos con cupos. Créelos en Cupos.");
    return;
  }
  var html = "";
  cursos.forEach(function (c) {
    var permitido = (c.corresponde || libre) && c.cupos > 0;
    html += '<label class="curso-matricula' + (c.corresponde ? " corresponde" : "") + (permitido ? "" : " bloqueado") + '">' +
      '<input type="radio" name="matriculaCurso" value="' + c.id + '"' + (permitido ? "" : " disabled") + ">" +
      "<b>" + textoMatricula(c.grado) + " " + textoMatricula(c.paralelo) + "</b>" +
      '<small>' + (c.cupos > 0 ? c.cupos + " cupo(s) libres" : '<span class="text-danger">Sin cupos</span>') + "</small>" +
      (c.corresponde && REC_MATRICULA && !REC_MATRICULA.libre ? '<span class="badge badge-success">Le corresponde</span>' : "") +
      "</label>";
  });
  $("#cursosMatricula").removeClass("text-muted").html(html);
  $("#guardarMatricula").prop("disabled", true);
}

function evaluarMatricula() {
  var estudiante = $("#matriculaEstudiante").val(), periodo = $("#matriculaPeriodo").val();
  $("#cambiarNivel").prop("checked", false);
  $("#cajaMotivoNivel").addClass("d-none");
  if (!estudiante) return;
  $("#historialMatricula").html('<i class="fas fa-spinner fa-spin"></i> Cargando…');
  $.post(SERVERURL + "ajax/historial.ajax.php", { estudiante: estudiante, periodo: periodo }, function (r) {
    if (!r.ok) return;
    REC_MATRICULA = r.recomendacion;
    $("#historialMatricula").html(r.historial);
    $("#verHistorialCompleto").attr("href", SERVERURL + "historial/" + estudiante + "/").removeClass("d-none");
    if (r.yaMatriculado) {
      $("#recomendacionNivel").html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Ya está matriculado en este año lectivo (' + textoMatricula(r.yaMatriculado) + ").</div>");
      $("#cursosMatricula").html("");
      $("#cajaCambioNivel").addClass("d-none");
      $("#guardarMatricula").prop("disabled", true);
      return;
    }
    var color = { NUEVO: "info", PROMOVIDO: "success", REPITE: "danger", SIN_ORDEN: "warning" }[r.recomendacion.tipo] || "warning";
    $("#recomendacionNivel").html('<div class="alert alert-' + color + ' py-2"><i class="fas fa-info-circle"></i> ' + textoMatricula(r.recomendacion.mensaje) + "</div>");
    $("#cajaCambioNivel").toggleClass("d-none", r.recomendacion.libre);
    $("#cursosMatricula").data("cursos", r.cursos);
    pintarCursosMatricula(r.cursos);
  }, "json");
}

$(document).on("change", "#matriculaEstudiante, #matriculaPeriodo", evaluarMatricula);

$(document).on("change", "#cambiarNivel", function () {
  $("#cajaMotivoNivel").toggleClass("d-none", !this.checked);
  $("#motivoCambioNivel").prop("required", this.checked);
  pintarCursosMatricula($("#cursosMatricula").data("cursos") || []);
});

$(document).on("change", 'input[name="matriculaCurso"]', function () {
  $(".curso-matricula").removeClass("elegido");
  $(this).closest(".curso-matricula").addClass("elegido");
  $("#guardarMatricula").prop("disabled", false);
});

/*=============================================
ELIMINAR INFORMACION
=============================================*/
$(document).on("click", ".btnEliminarMatricula", function () {
  var idMatricula = $(this).attr("idMatricula");
  Swal.fire({
    title: '¿Está seguro de borrar la matrícula',
    text: "¡Si no lo está puede cancelar la acción!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Si, borrar!'
  }).then(function (result) {

    if (result.value) {

      enviarAccion("listaMatricula", { idMatricula: idMatricula });

    }

  })


});

/*=============================================
EDITAR INFORMACION
=============================================*/
$(document).on("click",".btnEditarMatricula", function(){
  var idMatricula = $(this).attr("idMatricula");
  var datos = new FormData();
  datos.append("idMatricula",idMatricula);

  $.ajax({
      url:SERVERURL + "ajax/detalleMatricula.ajax.php",
      method:"POST",
      data: datos,
      cache: false,
      contentType:false,
      processData:false,
      dataType: "json",
      success: function(respuesta){
       //alert(respuesta);
          $("#editarMatricula").val(respuesta["id"]);
          $("#editarObservacion").val(respuesta["observacion"]);
      }


  });
});
