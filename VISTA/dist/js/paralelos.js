/*=============================================
EDITAR INFORMACION Paralelo LECTIVO
=============================================*/
$(document).on("click",".btnEditarParalelo", function(){
    var idParalelo = $(this).attr("idParalelo");
    var datos = new FormData();
    datos.append("idParalelo",idParalelo);

    $.ajax({
        url:SERVERURL + "ajax/paralelos.ajax.php",
        method:"POST",
        data: datos,
        cache: false,
        contentType:false,
        processData:false,
        dataType: "json",
        success: function(respuesta){
            $("#idParalelo").val(respuesta["id"]);
            $("#editarParalelo").val(respuesta["nombre"]);
            $("#editarObservacion").val(respuesta["observacion"]);
        }
    });
});
/*=============================================
ELIMINAR INFORMACION Paralelo
=============================================*/
$(document).on("click",".btnEliminarParalelo",function(){
    var idParalelo = $(this).attr("idParalelo");
    Swal.fire({
        title: '¿Está seguro de borrar el Paralelo?',
        text: "¡Si no lo está puede cancelar la accíón!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar Paralelo!'
      }).then(function (result) {
    
        if (result.value) {
    
          enviarAccion("paralelos", { idParalelo: idParalelo });
    
        }
    
      })


});
/*=============================================
VERIFICAR SI EL Paralelo ESTA REPETIDO
=============================================*/
$("#nuevoParalelo").change( function(){
$(".alert").remove();
var Paralelo = $(this).val();
var datos = new FormData();
datos.append("validarParalelo",Paralelo);
$.ajax({
  url: SERVERURL + "ajax/paralelos.ajax.php",
  method: "POST",
  data: datos,
  cache: false,
  contentType: false,
  processData: false,
  dataType: "json",
  success: function (respuesta) {
    if (respuesta) {
      $("#nuevoParalelo").parent().after('<div class="alert alert-warning">Este paralelo ya existe, intenta con otro. </div>');

      $("#nuevoParalelo").val("");
    }
  }
});
});