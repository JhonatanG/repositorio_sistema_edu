/*=============================================
ELIMINAR INFORMACION GRADO-MATERIA
=============================================*/
$(document).on("click", ".btnEliminarMdocente", function () {
    var idMdocente = $(this).attr("idMdocente");
    Swal.fire({
      title: '¿Está seguro de borrar la relación docente-materia?',
      text: "¡Si no lo está puede cancelar la acción!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, borrar!'
    }).then(function (result) {
  
      if (result.value) {
  
        enviarAccion("madocente", { idMdocente: idMdocente });
  
      }
  
    })
  
  
  });
  /*=============================================
EDITAR INFORMACION Mdocente 
=============================================*/
$(document).on("click",".btnEditarMdocente", function(){
  var idMdocente = $(this).attr("idMdocente");
  var datos = new FormData();
  datos.append("idMdocente",idMdocente);

  $.ajax({
      url:SERVERURL + "ajax/madocente.ajax.php",
      method:"POST",
      data: datos,
      cache: false,
      contentType:false,
      processData:false,
      dataType: "json",
      success: function(respuesta){
       //alert(respuesta);
          $("#editarMdocente").val(respuesta["id"]);
          $("#editarObservacion").val(respuesta["observacion"]);
      }


  });
});
/*=============================================
=             ACTIVAR MDOCENTE               =
=============================================*/
$(document).on("click", ".btnActivarMdocente", function () {
  var idMdocente = $(this).attr("idMdocente");
  var estadoMdocente = $(this).attr("estadoMdocente");

  var datos = new FormData();
  datos.append("activarId",  idMdocente);
  datos.append("activarMdocente", estadoMdocente);
  $.ajax({
    url: SERVERURL + "ajax/madocente.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    success: function (respuesta) {
      //  alert(respuesta)
      if (window.matchMedia("(max-width:767px)").matches) {

        Swal.fire({
          title: "Ha sido actualizado",
          type: "success",
          confirmButtonText: "¡Cerrar!"
        }).then(function (result) {

          if (result.value) {

            window.location = "'.SERVERURL.'madocente/";

          }

        });


      }

    }

  })
  if (estadoMdocente == 0) {
    $(this).removeClass('btn-success');
    $(this).addClass('btn-danger');
    $(this).html('Inactivo');
    $(this).attr('estadoMdocente', 1);
  } else {
    $(this).addClass('btn-success');
    $(this).removeClass('btn-danger');
    $(this).html('Activo');
    $(this).attr('estadoMdocente', 0);
  }


});