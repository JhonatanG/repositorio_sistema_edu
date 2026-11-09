/*=============================================
EDITAR INFORMACION Grados LECTIVO
=============================================*/
$(document).on("click", ".btnEditarGrado", function () {
  var idGrados = $(this).attr("idGrado");
  var datos = new FormData();
  datos.append("idGrados", idGrados);

  $.ajax({
    url: SERVERURL + "ajax/grados.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
  
      var datosAnio = new FormData();
      datosAnio.append("idAnio", respuesta["id_periodo"]);
      $.ajax({
        url: "ajax/anio.ajax.php",
        method: "POST",
        data: datosAnio,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
          $("#editarAnio").val(respuesta["id"]);
          $("#editarAnio").html(respuesta["nombre"]);
          console.log("respuesta",respuesta);
        }

        });

      $("#idGrado").val(respuesta["id"]);
      $("#editarGrado").val(respuesta["nombre"]);
      $("#editarObservacion").val(respuesta["observacion"]);
      $("#editarOrden").val(respuesta["orden"]);

    }


  });
});
/*=============================================
ELIMINAR INFORMACION Grados LECTIVO
=============================================*/
$(document).on("click", ".btnEliminarGrado", function () {
  var idGrado = $(this).attr("idGrado");
  Swal.fire({
    title: '¿Está seguro de borrar el grado?',
    text: "¡Si no lo está puede cancelar la accíón!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Si, borrar el grado!'
  }).then(function (result) {

    if (result.value) {

      enviarAccion("grados", { idGrado: idGrado });

    }

  })


});
/*=============================================
VERIFICAR SI EL Grados LECTIVO ESTA REPETIDO
=============================================*/
$("#nuevoGrado").change(function () {
  $(".alert").remove();
  var Grados = $(this).val();
  var datos = new FormData();
  datos.append("validarGrados", Grados);
  $.ajax({
    url: "ajax/grados.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#nuevoGrado").parent().after('<div class="alert alert-warning">Este Grado ya existe, intenta con otro. </div>');

        $("#nuevoGrado").val("");
      }
    }
  });
});

