/*=============================================
EDITAR INFORMACION Materia LECTIVO
=============================================*/
$(document).on("click",".btnEditarMateria", function(){
    var idMateria = $(this).attr("idMateria");
    var datos = new FormData();
    datos.append("idMateria",idMateria);

    $.ajax({
        url:SERVERURL + "ajax/materias.ajax.php",
        method:"POST",
        data: datos,
        cache: false,
        contentType:false,
        processData:false,
        dataType: "json",
        success: function(respuesta){
            $("#idMateria").val(respuesta["id"]);
            $("#editarMateria").val(respuesta["nombre"]);
            $("#editarObservacion").val(respuesta["observacion"]);
        }
    });
});
/*=============================================
ELIMINAR INFORMACION Materia
=============================================*/
$(document).on("click",".btnEliminarMateria",function(){
    var idMateria = $(this).attr("idMateria");
    Swal.fire({
        title: '¿Está seguro de borrar la materia?',
        text: "¡Si no lo está puede cancelar la accíón!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar materia!'
      }).then(function (result) {
    
        if (result.value) {
    
          enviarAccion("materias", { idMateria: idMateria });
    
        }
    
      })


});
/*=============================================
VERIFICAR SI EL Materia LECTIVO ESTA REPETIDO
=============================================*/
$("#nuevaMateria").change( function(){
$(".alert").remove();
var Materia = $(this).val();
var datos = new FormData();
datos.append("validarMateria",Materia);
$.ajax({
  url: SERVERURL + "ajax/materias.ajax.php",
  method: "POST",
  data: datos,
  cache: false,
  contentType: false,
  processData: false,
  dataType: "json",
  success: function (respuesta) {
    if (respuesta) {
      $("#nuevaMateria").parent().after('<div class="alert alert-warning">Este materia ya existe, intenta con otra. </div>');

      $("#nuevaMateria").val("");
    }
  }
});
});
/*=============================================
ELIMINAR INFORMACION GRADO-MATERIA
=============================================*/
$(document).on("click", ".btnEliminarGmateria", function () {
  var idGmateria = $(this).attr("idGmateria");
  Swal.fire({
    title: '¿Está seguro de borrar el grado y el paralelo?',
    text: "¡Si no lo está puede cancelar la acción!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Si, borrar!'
  }).then(function (result) {

    if (result.value) {

      enviarAccion("gmaterias", { idGmateria: idGmateria });

    }

  })


});