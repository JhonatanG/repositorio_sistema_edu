/*=============================================
=          SUBIENDO LA FOTO DEL USUARIO       =
=============================================*/
$(".nuevaFoto").change(function () {
  var imagen = this.files[0];
  if (imagen["type"] != "image/jpg"
    && imagen["type"] != "image/png"
    && imagen["type"] != "image/jpeg") {
    $(".nuevaFoto").val("");
    Swal.fire({
      icon: "error",
      title: "¡La imagen debe ser de tipo JPG o PNG!",
      showConfirmButton: true,
      confirmButtonText: "Cerrar",
      closeOnConfirm: false
    });
  } else if (imagen["size"] > 2000000) {
    $(".nuevaFoto").val("");
    Swal.fire({
      icon: "error",
      title: "¡La imagen no debe pesar más de 2MB!",
      showConfirmButton: true,
      confirmButtonText: "Cerrar",
      closeOnConfirm: false
    });
  } else {
    var datosImagen = new FileReader;
    datosImagen.readAsDataURL(imagen);

    $(datosImagen).on("load", function (event) {
      var rutaImagen = event.target.result;

      $(".previsualizarC").attr("src", rutaImagen);
     
    });
  }

});
/*=============================================
=          SUBIENDO LA FOTO DEL USUARIO   EDITAR    =
=============================================*/
$(".editarFoto").change(function () {
  var imagen = this.files[0];
  if (imagen["type"] != "image/jpg"
    && imagen["type"] != "image/png"
    && imagen["type"] != "image/jpeg") {
    $(".editarFoto").val("");
    Swal.fire({
      icon: "error",
      title: "¡La imagen debe ser de tipo JPG o PNG!",
      showConfirmButton: true,
      confirmButtonText: "Cerrar",
      closeOnConfirm: false
    });
  } else if (imagen["size"] > 2000000) {
    $(".editarFoto").val("");
    Swal.fire({
      icon: "error",
      title: "¡La imagen no debe pesar más de 2MB!",
      showConfirmButton: true,
      confirmButtonText: "Cerrar",
      closeOnConfirm: false
    });
  } else {
    var datosImagen = new FileReader;
    datosImagen.readAsDataURL(imagen);

    $(datosImagen).on("load", function (event) {
      var rutaImagen = event.target.result;

      $(".previsualizar").attr("src", rutaImagen);
     
    });
  }

});
/*=============================================
=         EDITAR INFORMACION USUARIO       =
=============================================*/
$(document).on("click", ".btnEditarUsuario", function () {
  var idUsuario = $(this).attr("idUsuario");
  var datos = new FormData();
  datos.append("idUsuario", idUsuario); //variable post
  // AJAX CONECTAR A BASE DE DATOS
  $.ajax({
    url: SERVERURL + "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarNombre").val(respuesta["nombre"]);
      $("#editarUsuario").val(respuesta["usuario"]);
      $("#editarRol").html(respuesta["rol"]);
      $("#editarRol").val(respuesta["rol"]);

      if (respuesta["foto"] != "") {

        $(".previsualizar").attr("src",SERVERURL+respuesta["foto"]);
      }
    }

  });



});
/*=============================================
=             ACTIVAR USUARIO                  =
=============================================*/
$(document).on("click", ".btnActivar", function () {
  var idUsuario = $(this).attr("idUsuario");
  var estadoUsuario = $(this).attr("estadoUsuario");

  var datos = new FormData();
  datos.append("activarId", idUsuario);
  datos.append("activarUsuario", estadoUsuario);
  $.ajax({
    url: SERVERURL + "ajax/usuarios.ajax.php",
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

            window.location = "usuarios/";

          }

        });


      }

    }

  })
  if (estadoUsuario == 0) {
    $(this).removeClass('btn-success');
    $(this).addClass('btn-danger');
    $(this).html('Inactivo');
    $(this).attr('estadoUsuario', 1);
  } else {
    $(this).addClass('btn-success');
    $(this).removeClass('btn-danger');
    $(this).html('Activo');
    $(this).attr('estadoUsuario', 0);
  }


});
/*=============================================
         REVISAR USUARIO REPETIDO           
=============================================*/

$("#nuevoUsuario").change(function () {
  $(".alert").remove();
  var usuario = $(this).val();
  var datos = new FormData();
  datos.append("validarUsuario", usuario);
  $.ajax({
    url: SERVERURL + "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#nuevoUsuario").parent().after('<div class="alert alert-warning">Este nombre de usuario ya existe, intenta con otro. </div>');

        $("#nuevoUsuario").val("");
      }
    }
  });
});
/*=============================================
  ELIMINAR USUARIO          
=============================================*/

$(".btnEliminarUsuario").click(function () {

  var idUsuario = $(this).attr("idUsuario");
  var fotoUsuario = $(this).attr("fotoUsuario");
  var usuario = $(this).attr("usuario");

  Swal.fire({
    title: '¿Está seguro de borrar el usuario?',
    text: "¡Si no lo está puede cancelar la accíón!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Si, borrar usuario!'
  }).then(function (result) {

    if (result.value) {

      enviarAccion("usuarios", { idUsuario: idUsuario });

    }

  })

});
///MENU SELECCIONADO ACTUAL

$(document).ready(function () {
  var links = $('.sidebar ul li a');
  $.each(links, function (key, va) {
    if (va.href == document.URL) {
      $(this).addClass('active');
    }
  });
});

