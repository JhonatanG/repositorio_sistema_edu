
/*=============================================
=         VISUALIZAR INFORMACION Estudiante       =
=============================================*/
$(document).on("click", ".btnVisuaEstudiante", function () {
    var idAlumnoV = $(this).attr("idAlumnoV");

    var datos = new FormData();
    datos.append("idAlumnoV", idAlumnoV); //variable post
    
    // AJAX CONECTAR A BASE DE DATOS
    $.ajax({
      url: SERVERURL + "ajax/alumnos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        $("#visuaCedulaE").val(respuesta["cedulaE"]);
        $("#visuaNombreE").val(respuesta["nombresE"]);
        $("#visuaApellidoE").val(respuesta["apellidosE"]);
        $("#visuaGeneroE").val(respuesta["generoE"]);
        $("#visuaEmailE").val(respuesta["emailE"]);
        $("#visuaTelefonoE").val(respuesta["telefonoE"]);
        $("#visuaDireccionE").val(respuesta["direccionE"]);
        $("#visuaFechaE").val(respuesta["fecha_nacimientoE"]);


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
        $edad = calcularEdad(respuesta["fecha_nacimientoE"]);
        $var = " años";
        $edadE = $edad + $var;
  
        $("#edadE").val($edadE);
        $("#visuaCedulaR").val(respuesta["cedulaR"]);
        $("#visuaNombreR").val(respuesta["nombresR"]);
        $("#visuaApellidoR").val(respuesta["apellidosR"]);
        $("#visuaGeneroR").val(respuesta["generoR"]);
        $("#visuaEmailR").val(respuesta["emailR"]);
        $("#visuaTelefonoR").val(respuesta["telefonoR"]);
        $("#visuaDireccionR").val(respuesta["direccionR"]);
  
       // $("#visuaUsuarioD").val(respuesta["usuario"]);
      
  
       /* if (respuesta["foto"] != "") {
  
          $(".previsualizarD").attr("src", respuesta["foto"]);
        }*/
      }
  
    });
  
  });
  /*=============================================
=         EDITAR INFORMACION Estudiante       =
=============================================*/
$(document).on("click", ".btnEditarEstudiante", function () {
  var idAlumno = $(this).attr("idAlumno");
 // alert(idAlumno);
  var datos = new FormData();
  datos.append("idAlumno", idAlumno); //variable post
  // AJAX CONECTAR A BASE DE DATOS
  $.ajax({
    url: SERVERURL + "ajax/alumnos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#editarCedulaE").val(respuesta["cedula"]);
      $("#editarNombreE").val(respuesta["nombres"]);
      $("#editarApellidoE").val(respuesta["apellidos"]);
      $("#editarEmailE").val(respuesta["email"]);
      $("#editarTelefonoE").val(respuesta["telefono"]);
      $("#editarDireccionE").val(respuesta["direccion"]);
      $("#editarRepresentanteE").html(respuesta["id_representante"]);
      $("#editarRepresentanteE").val(respuesta["id_representante"]);
      

    }

  });

});
/*=============================================
         REVISAR CEDULA REPETIDA         
=============================================*/

$("#nuevaCedulaE").change(function () {
  $(".alert").remove();
  var CedulaE = $(this).val();
  var datos = new FormData();
  datos.append("validarAlumno", CedulaE);
  $.ajax({
    url: SERVERURL + "ajax/alumnos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        $("#nuevaCedulaE").parent().after('<div class="alert alert-warning">Este número de cédula ya existe, intente con otro. </div>');

        $("#nuevaCedulaE").val("");
      }
    }
  });
});
/*=============================================
  ELIMINAR USUARIO          
=============================================*/

$(".btnEliminarEstudiante").click(function () {

  var idEstudiante = $(this).attr("idEstudiante");
  var fotoEstudiante = $(this).attr("fotoEstudiante");
  var usuario = $(this).attr("usuario");

  Swal.fire({
    title: '¿Está seguro de borrar el/la Estudiante?',
    text: "¡Si no lo está puede cancelar la accíón!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Si, borrar Estudiante!'
  }).then(function (result) {

    if (result.value) {

      enviarAccion("alumnos", { idEstudiante: idEstudiante });

    }

  })

});
/*=============================================
COLOCAR AUTOMATIXO NUEVO  Y PASSWORD
=============================================*/

var nuevaCedulaE = document.getElementById("nuevaCedulaE");
if (nuevaCedulaE) nuevaCedulaE.onchange = function () {
  var valor = this.value;
  var letra = "E";
  var EstudianteE = letra + valor;
  document.getElementById("nuevoUsuarioE").value = EstudianteE;
  document.getElementById("nuevaPasswordE").value = EstudianteE;
}
