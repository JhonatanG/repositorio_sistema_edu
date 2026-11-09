/*=============================================
ELIMINAR INFORMACION GRADO-PARALELO
=============================================*/
$(document).on("click", ".btnEliminarGparalelo", function () {
    var idGparalelo = $(this).attr("idGparalelo");
    Swal.fire({
      title: '¿Está seguro de borrar el grado y el paralelo?',
      text: "¡Si no lo está puede cancelar la accíón!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, borrar!'
    }).then(function (result) {
  
      if (result.value) {
  
        enviarAccion("gparalelos", { idGparalelo: idGparalelo });
  
      }
  
    })
  
  
  });

  /*=============================================
=             ACTIVAR Gparalelo               =
=============================================*/
$(document).on("click", ".btnActivarGparalelo", function () {
    var idGparalelo = $(this).attr("idGparalelo");
    var estadoGparalelo = $(this).attr("estadoGparalelo");
  
    var datos = new FormData();
    datos.append("activarId",  idGparalelo);
    datos.append("activarGparalelo", estadoGparalelo);
    $.ajax({
      url: SERVERURL + "ajax/gparalelo.ajax.php",
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
  
              window.location = "'.SERVERURL.'gparalelos/";
  
            }
  
          });
  
  
        }
  
      }
  
    })
    if (estadoGparalelo == 0) {
      $(this).removeClass('btn-success');
      $(this).addClass('btn-danger');
      $(this).html('Inactivo');
      $(this).attr('estadoGparalelo', 1);
    } else {
      $(this).addClass('btn-success');
      $(this).removeClass('btn-danger');
      $(this).html('Activo');
      $(this).attr('estadoGparalelo', 0);
    }
  
  
  });

/*=============================================
EDITAR INFORMACION Gparalelo 
=============================================*/
$(document).on("click",".btnEditarGparalelo", function(){
  var idGparalelo = $(this).attr("idGparalelo");
  var datos = new FormData();
  datos.append("idGparalelo",idGparalelo);

  $.ajax({
      url:SERVERURL + "ajax/gparalelo.ajax.php",
      method:"POST",
      data: datos,
      cache: false,
      contentType:false,
      processData:false,
      dataType: "json",
      success: function(respuesta){
       //alert(respuesta);
          $("#editarGparalelo").val(respuesta["id"]);
          $("#editarCupos").val(respuesta["cupos"]);
          $("#editarObservacion").val(respuesta["observacion"]);
      }


  });
});