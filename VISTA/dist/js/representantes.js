/*=============================================
COLOCAR AUTOMATIXO NUEVO  Y PASSWORD
=============================================*/

var nuevaCedulaR = document.getElementById("nuevaCedulaR");
if (nuevaCedulaR) nuevaCedulaR.onchange = function () {
  var valor = this.value;
  var letra = "R";
  var RepresentanteR = letra + valor;
  document.getElementById("nuevoUsuarioR").value = RepresentanteR;
  document.getElementById("nuevaPasswordR").value = RepresentanteR;
}
  /*=============================================
  =         EDITAR INFORMACION Representante       =
  =============================================*/
  $(document).on("click", ".btnEditarRepresentante", function () {
    var idRepresentante = $(this).attr("idRepresentante");
    var datos = new FormData();
    datos.append("idRepresentante", idRepresentante); //variable post
    // AJAX CONECTAR A BASE DE DATOS
    $.ajax({
      url: SERVERURL + "ajax/representantes.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        $("#editarCedulaR").val(respuesta["cedula"]);
        $("#editarNombreR").val(respuesta["nombres"]);
        $("#editarApellidoR").val(respuesta["apellidos"]);
        $("#editarGeneroR").val(respuesta["genero"]);
        $("#editarEmailR").val(respuesta["email"]);
        $("#editarTelefonoR").val(respuesta["telefono"]);
        $("#editarDireccionR").val(respuesta["direccion"]);
       
      }
  
    });
  
  });
  /*=============================================
           REVISAR CEDULA REPETIDA         
  =============================================*/
  
  $("#nuevaCedulaR").change(function () {
    $(".alert").remove();
    var CedulaR = $(this).val();
    var datos = new FormData();
    datos.append("validarRepresentante", CedulaR);
    $.ajax({
      url: SERVERURL + "ajax/representantes.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        if (respuesta) {
          $("#nuevaCedulaR").parent().after('<div class="alert alert-warning">Este número de cédula ya existe, intente con otro. </div>');
  
          $("#nuevaCedulaR").val("");
        }
      }
    });
  });
  /*=============================================
    ELIMINAR USUARIO          
  =============================================*/
  
  $(".btnEliminarRepresentante").click(function () {
  
    var idRepresentante = $(this).attr("idRepresentante");
  
    Swal.fire({
      title: '¿Está seguro de borrar representante?',
      text: "¡Si no lo está puede cancelar la accíón!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, borrar representante!'
    }).then(function (result) {
  
      if (result.value) {
  
        enviarAccion("representantes", { idRepresentante: idRepresentante });
  
      }
  
    })
  
  });