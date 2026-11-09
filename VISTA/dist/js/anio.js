/*=============================================
EDITAR INFORMACION ANIO LECTIVO
=============================================*/
$(document).on("click",".btnEditarAnio", function(){
    var idAnio = $(this).attr("idAnio");
    var datos = new FormData();
    datos.append("idAnio",idAnio);

    $.ajax({
        url:SERVERURL + "ajax/anio.ajax.php",
        method:"POST",
        data: datos,
        cache: false,
        contentType:false,
        processData:false,
        dataType: "json",
        success: function(respuesta){
            $("#idAnio").val(respuesta["id"]);
            $("#editarAnio").val(respuesta["nombre"]);
            $("#editarObservacion").val(respuesta["observacion"]);
            $("#editarTipo").val(respuesta["tipo_evaluacion"] || "QUIMESTRAL");
        }


    });
});
/*=============================================
ELIMINAR INFORMACION ANIO LECTIVO
=============================================*/
$(document).on("click",".btnEliminarAnio",function(){
    var idAnio = $(this).attr("idAnio");
    Swal.fire({
        title: '¿Está seguro de borrar el Año Lectivo?',
        text: "¡Si no lo está puede cancelar la accíón!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar el Año Lectivo!'
      }).then(function (result) {
    
        if (result.value) {
    
          enviarAccion("anioL", { idAnio: idAnio });
    
        }
    
      })


});
/*=============================================
VERIFICAR SI EL ANIO LECTIVO ESTA REPETIDO
=============================================*/
$("#nuevoAnio").change( function(){
$(".alert").remove();
var anio = $(this).val();
var datos = new FormData();
datos.append("validarAnio",anio);
$.ajax({
  url: SERVERURL + "ajax/anio.ajax.php",
  method: "POST",
  data: datos,
  cache: false,
  contentType: false,
  processData: false,
  dataType: "json",
  success: function (respuesta) {
    if (respuesta) {
      $("#nuevoAnio").parent().after('<div class="alert alert-warning">Este Año Lectivo ya existe, intenta con otro. </div>');

      $("#nuevoAnio").val("");
    }
  }
});
});
/*=============================================
=             ACTIVAR Anio                =
=============================================*/
$(document).on("click", ".btnActivarAnio", function () {
  var idAnio = $(this).attr("idAnio");
  var estadoAnio = $(this).attr("estadoAnio");

  var datos = new FormData();
  datos.append("activarId", idAnio);
  datos.append("activarAnio", estadoAnio);
  $.ajax({
    url: SERVERURL + "ajax/anio.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    success: function (respuesta) {

      if (window.matchMedia("(max-width:767px)").matches) {

        Swal.fire({
          title: "El usuario ha sido actualizado",
          type: "success",
          confirmButtonText: "¡Cerrar!"
        }).then(function (result) {

          if (result.value) {

            window.location = "'.SERVERURL.'anioL/";

          }

        });


      }

    }

  })
  if (estadoAnio == 0) {
    $(this).removeClass('btn-success');
    $(this).addClass('btn-danger');
    $(this).html('Inactivo');
    $(this).attr('estadoAnio', 1);
  } else {
    $(this).addClass('btn-success');
    $(this).removeClass('btn-danger');
    $(this).html('Activo');
    $(this).attr('estadoAnio', 0);
  }


});
