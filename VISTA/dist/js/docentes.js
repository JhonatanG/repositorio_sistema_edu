/*=============================================
COLOCAR AUTOMATIXO NUEVO Docente Y PASSWORD
=============================================*/

var nuevaCedulaD = document.getElementById("nuevaCedulaD");
if (nuevaCedulaD) nuevaCedulaD.onchange = function () {
  var valor = this.value;
  var letra = "D";
  var DocenteD = letra + valor;
  document.getElementById("nuevoUsuarioD").value = DocenteD;
  document.getElementById("nuevaPasswordD").value = DocenteD;
}
/*=============================================
=          SUBIENDO LA FOTO DEL DOCENTE      =
=============================================*/
$(".nuevaFotoD").change(function () {
  var imagen = this.files[0];
  if (imagen["type"] != "image/jpeg"
    && imagen["type"] != "image/png") {
    $(".nuevaFotoD").val("");
    Swal.fire({
      icon: "error",
      title: "¡La imagen debe ser de tipo JPG o PNG!",
      showConfirmButton: true,
      confirmButtonText: "Cerrar",
      closeOnConfirm: false
    });
  } else if (imagen["size"] > 2000000) {
    $(".nuevaFotoD").val("");
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

      $(".previsualizarCD").attr("src", rutaImagen);

    });
  }

});
/*=============================================
=        EDITANDO LA FOTO DEL DOCENTE      =
=============================================*/
$(".editarFotoD").change(function () {
  var imagen = this.files[0];
  if (imagen["type"] != "image/jpeg"
    && imagen["type"] != "image/png") {
    $(".editarFotoD").val("");
    Swal.fire({
      icon: "error",
      title: "¡La imagen debe ser de tipo JPG o PNG!",
      showConfirmButton: true,
      confirmButtonText: "Cerrar",
      closeOnConfirm: false
    });
  } else if (imagen["size"] > 2000000) {
    $(".editarFotoD").val("");
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

      $(".previsualizarD").attr("src", rutaImagen);

    });
  }

});
/*=============================================
=             ACTIVAR Docente                  =
=============================================*/
$(document).on("click", ".btnActivarD", function () {
  var idDocente = $(this).attr("idDocente");
  var estadoDocente = $(this).attr("estadoDocente");

  var datos = new FormData();
  datos.append("activarId", idDocente);
  datos.append("activarDocente", estadoDocente);
  $.ajax({
    url: SERVERURL + "ajax/docentes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    success: function (respuesta) {
     //   alert(respuesta);
      if (window.matchMedia("(max-width:767px)").matches) {

        Swal.fire({
          title: "El/La Docente ha sido actualizado",
          type: "success",
          confirmButtonText: "¡Cerrar!"
        }).then(function (result) {

          if (result.value) {

            window.location = "docente";

          }

        });


      }

    }

  });
  if (estadoDocente == 0) {
    $(this).removeClass('btn-success');
    $(this).addClass('btn-danger');
    $(this).html('Inactivo');
    $(this).attr('estadoDocente', 1);
  } else {
    $(this).addClass('btn-success');
    $(this).removeClass('btn-danger');
    $(this).html('Activo');
    $(this).attr('estadoDocente', 0);
  }


});
/*=============================================
=         VISUALIZAR INFORMACION Docente       =
=============================================*/
$(document).on("click", ".btnVisuaDocente", function () {
  var idDocente = $(this).attr("idDocenteV");
  var datos = new FormData();
  datos.append("idDocente", idDocente); //variable post
  // AJAX CONECTAR A BASE DE DATOS
  $.ajax({
    url: SERVERURL + "ajax/docentes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#visuaCedulaD").val(respuesta["cedula"]);
      $("#visuaNombreD").val(respuesta["nombres"]);
      $("#visuaApellidoD").val(respuesta["apellidos"]);
      $("#visuaGeneroD").val(respuesta["genero"]);
      $("#visuaEmailD").val(respuesta["email"]);
      $("#visuaTelefonoD").val(respuesta["telefono"]);
      $("#visuaDireccionD").val(respuesta["direccion"]);
      $("#visuaFechaD").val(respuesta["fecha_nacimiento"]);

      function calcularEdad(fecha) {
        var hoy = new Date();
        var cumpleanos = new Date(fecha);
        var edad = hoy.getFullYear() - cumpleanos.getFullYear();
        var m = hoy.getMonth() - cumpleanos.getMonth();

        if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
          edad--;
        }

        return edad;
      }
      $edad = calcularEdad(respuesta["fecha_nacimiento"]);
      $var = " años";
      $edadD = $edad + $var;

      $("#edadD").val($edadD);

      $("#visuaUsuarioD").val(respuesta["usuario"]);
    

      if (respuesta["foto"] != "") {

        $(".previsualizarD").attr("src", SERVERURL+respuesta["foto"]);
      }
    }

  });

});

/*=============================================
=         EDITAR INFORMACION Docente       =
=============================================*/
$(document).on("click", ".btnEditarDocente", function () {
  var idDocente = $(this).attr("idDocente");
  var datos = new FormData();
  datos.append("idDocente", idDocente); //variable post
  // AJAX CONECTAR A BASE DE DATOS
  $.ajax({
    url: SERVERURL + "ajax/docentes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarCedulaD").val(respuesta["cedula"]);
      $("#editarNombreD").val(respuesta["nombres"]);
      $("#editarApellidoD").val(respuesta["apellidos"]);
      $("#editarGeneroD").val(respuesta["genero"]);
      $("#editarEmailD").val(respuesta["email"]);
      $("#editarTelefonoD").val(respuesta["telefono"]);
      $("#editarDireccionD").val(respuesta["direccion"]);
      $("#editarFechaD").val(respuesta["fecha_nacimiento"]);
      /* CALCULAR ANIOS */
      function calcularEdad(fecha) {
        var hoy = new Date();
        var cumpleanos = new Date(fecha);
        var edad = hoy.getFullYear() - cumpleanos.getFullYear();
        var m = hoy.getMonth() - cumpleanos.getMonth();

        if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
          edad--;
        }

        return edad;
      }
      $edad = calcularEdad(respuesta["fecha_nacimiento"]);
      $var = " años";
      $edadD = $edad + $var;

      $("#edadD").val($edadD);

      $("#editarUsuarioD").val(respuesta["usuario"]);

      if (respuesta["foto"] != "") {

        $(".previsualizarD").attr("src", SERVERURL+respuesta["foto"]);
      }
    }

  });

});
/*=============================================
         REVISAR CEDULA REPETIDA         
=============================================*/

$("#nuevaCedulaD").change(function () {
  $(".alert").remove();
  var CedulaD = $(this).val();
  var datos = new FormData();
  datos.append("validarDocente", CedulaD);
  $.ajax({
    url: SERVERURL + "ajax/docentes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#nuevaCedulaD").parent().after('<div class="alert alert-warning">Este número de cédula ya existe, intente con otro. </div>');

        $("#nuevaCedulaD").val("");
      }
    }
  });
});
/*=============================================
  ELIMINAR USUARIO          
=============================================*/

$(".btnEliminarDocente").click(function () {

  var idDocente = $(this).attr("idDocente");
  var fotoDocente = $(this).attr("fotoDocente");
  var usuario = $(this).attr("usuario");

  Swal.fire({
    title: '¿Está seguro de borrar el/la docente?',
    text: "¡Si no lo está puede cancelar la accíón!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Si, borrar docente!'
  }).then(function (result) {

    if (result.value) {

      enviarAccion("docente", { idDocente: idDocente });

    }

  })

});