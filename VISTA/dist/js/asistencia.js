/*=============================================
FILTRO: AL ELEGIR EL DOCENTE SE CARGAN SUS CURSOS
=============================================*/
$("#idDocenteA").change(function(){
    var idDocente = $(this).val();
    $("#idDocenteG").val(idDocente);
    $("#idGrado").html('<option value="">Seleccionar</option>').trigger("change.select2");
    $("#idMateria").html('').trigger("change.select2");
    if (!idDocente) return;

    var datos = new FormData();
    datos.append("gradosDocente", idDocente);
    $.ajax({
      url: SERVERURL + "ajax/asistencia.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        for (var x of respuesta) {
          $("#idGrado").append($("<option>").val(x.id).text(x.grado));
        }
      }
    });
});

$("#idGrado").change(function(){
    var idDocente = document.getElementById("idDocenteG").value;

    var idGradoD = $(this).val();

    var datos = new FormData();

    datos.append("idDocente", idDocente);
    datos.append("idGradoD", idGradoD);
 // alert(idGradoD);
 //alert(idDocente);

    $.ajax({
      url: SERVERURL + "ajax/asistencia.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
    
          $("#idMateria").html('');
          for(x of respuesta){
            $(".datos").append(
            '<option value="'+x.id+'">'+x.materia+'</option>'
            );
        }
        
      }
    });

});
/*=============================================
EDITAR INFORMACION ANIO LECTIVO
=============================================*/
$(document).on("click",".btnEditarAsistencia", function(){
  var idAsistencia = $(this).attr("idAsist");
  var datos = new FormData();
  datos.append("idAsistencia",idAsistencia);
 // alert(idAsistencia);
  $.ajax({
      url:SERVERURL + "ajax/asistencia.ajax.php",
      method:"POST",
      data: datos,
      cache: false,
      contentType:false,
      processData:false,
      dataType: "json",
      success: function(respuesta){
         asistencia = respuesta["asistencia"];
          $("#idAsistencia").val(respuesta["id"]);
          if(asistencia==="P"){
            $("#editarAsistencia").html("Presente");
            $("#editarAsistencia").val(respuesta["asistencia"]);
          }else if(asistencia==="FG"){
            $("#editarAsistencia").html("Fuga");
            $("#editarAsistencia").val(respuesta["asistencia"]);
          }else if(asistencia==="FJ"){
            $("#editarAsistencia").html("Falta Justificada");
            $("#editarAsistencia").val(respuesta["asistencia"]);
          }else if(asistencia==="FI"){
            $("#editarAsistencia").html("Falta Injustificada");
            $("#editarAsistencia").val(respuesta["asistencia"]);
          }
     

      }


  });
});
/*=============================================
REGISTRO DE ASISTENCIA: CONTADORES, BÚSQUEDA, TODOS PRESENTES Y CONFIRMACIÓN
=============================================*/
function contarAsistencia() {
  ["P", "FI", "FG"].forEach(function (estado) {
    $("#cuenta" + estado).text($('#formAsistencia input[type="radio"][value="' + estado + '"]:checked').length);
  });
  $("#formAsistencia .fila-estudiante").each(function () {
    var marcado = $(this).find('input[type="radio"]:checked').val();
    $(this).toggleClass("marcada-falta", marcado === "FI").toggleClass("marcada-fuga", marcado === "FG");
  });
}

$(document).on("change", '#formAsistencia input[type="radio"]', contarAsistencia);

$(document).on("click", "#todosPresentes", function () {
  $('#formAsistencia input[type="radio"][value="P"]').prop("checked", true);
  contarAsistencia();
});

$(document).on("input", "#buscarEstudiante", function () {
  var texto = this.value.toLowerCase().trim();
  $("#formAsistencia .fila-estudiante").each(function () {
    $(this).toggle(!texto || $(this).data("nombre").indexOf(texto) !== -1);
  });
});

$(document).on("submit", "#formAsistencia", function (e) {
  var formulario = this;
  if (formulario.dataset.confirmado) return;
  e.preventDefault();
  var p = $("#cuentaP").text(), fi = $("#cuentaFI").text(), fg = $("#cuentaFG").text();
  Swal.fire({
    title: "¿Guardar la asistencia de hoy?",
    html: '<div class="contadores justify-content-center">' +
      '<span class="contador presente">Presentes: <b>' + p + "</b></span>" +
      '<span class="contador falta">Faltas: <b>' + fi + "</b></span>" +
      '<span class="contador fuga">Fugas: <b>' + fg + "</b></span></div>" +
      '<small class="text-muted d-block mt-2">Después de guardar no se puede cambiar; las faltas las justifica Secretaría.</small>',
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, guardar",
    cancelButtonText: "Revisar",
    confirmButtonColor: "#28a745"
  }).then(function (r) {
    if (r.value) {
      formulario.dataset.confirmado = "1";
      formulario.submit();
    }
  });
});

$(contarAsistencia);
