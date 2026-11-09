/*=============================================
=             ACTIVAR DOCGRADO               =
=============================================*/
$(document).on("click", ".btnActivarDocgrado", function () {
    var idDocgrado = $(this).attr("idDocgrado");
    var estadoDocgrado = $(this).attr("estadoDocgrado");
  
    var datos = new FormData();
    datos.append("activarId",  idDocgrado);
    datos.append("activarDocgrado", estadoDocgrado);
    $.ajax({
      url: SERVERURL + "ajax/docgrado.ajax.php",
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
    if (estadoDocgrado == 0) {
      $(this).removeClass('btn-success');
      $(this).addClass('btn-danger');
      $(this).html('Inactivo');
      $(this).attr('estadoDocgrado', 1);
    } else {
      $(this).addClass('btn-success');
      $(this).removeClass('btn-danger');
      $(this).html('Activo');
      $(this).attr('estadoDocgrado', 0);
    }
  
  
  });
  /*=============================================
ELIMINAR INFORMACION
=============================================*/
$(document).on("click", ".btnEliminarDocgrado", function () {
    var idDocgrado = $(this).attr("idDocgrado");
    Swal.fire({
      title: '¿Está seguro de borrar la relación docente-grado?',
      text: "¡Si no lo está puede cancelar la acción!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, borrar!'
    }).then(function (result) {
  
      if (result.value) {
  
        enviarAccion("docgrado", { idDocgrado: idDocgrado });
  
      }
  
    })
  
  
  });
  /*=============================================
EDITAR INFORMACION Docgrado 
=============================================*/
$(document).on("click",".btnEditarDocgrado", function(){
  var idDocgrado = $(this).attr("idDocgrado");
  var datos = new FormData();
  datos.append("idDocgrado",idDocgrado);

  $.ajax({
      url:SERVERURL + "ajax/docgrado.ajax.php",
      method:"POST",
      data: datos,
      cache: false,
      contentType:false,
      processData:false,
      dataType: "json",
      success: function(respuesta){
       //alert(respuesta);
          $("#editarDocgrado").val(respuesta["id"]);
          $("#editarObservacion").val(respuesta["observacion"]);
      }


  });
});
/*=============================================
DISTRIBUTIVO EN CASCADA: NIVEL -> PARALELOS -> ASIGNATURAS DEL NIVEL
=============================================*/
if ($("#modalAgregarDocg").length) {
  $("#idDocenteD").select2({ theme: "bootstrap4", dropdownParent: $("#modalAgregarDocg") });
}

function textoSeguro(texto) {
  return $("<div>").text(texto).html();
}

function cargarMateriasDistributivo() {
  var idGrado = $("#idNivelDistributivo").val();
  var caja = $("#materiasDistributivo");
  if (!idGrado) {
    caja.addClass("text-muted").html("Primero elija el nivel.");
    return;
  }
  var datos = { accion: "materias", idGrado: idGrado, "paralelos[]": [] };
  var nombres = {};
  $("#paralelosDistributivo input:checked").each(function () {
    datos["paralelos[]"].push(this.value);
    nombres[this.value] = $(this).data("nombre");
  });
  $.post(SERVERURL + "ajax/distributivo.ajax.php", datos, function (materias) {
    if (!materias.length) {
      caja.addClass("text-muted").html('Este nivel no tiene asignaturas. Agréguelas en ' +
        '<a href="' + SERVERURL + 'gmaterias/">Parámetros → Materias por grado</a>.');
      return;
    }
    var totalParalelos = datos["paralelos[]"].length;
    var html = "";
    materias.forEach(function (m) {
      var ocupada = totalParalelos > 0 && m.asignadas.length === totalParalelos;
      var detalle = m.asignadas.map(function (a) {
        return textoSeguro(nombres[a.gp]) + ": " + textoSeguro(a.docente);
      }).join(" · ");
      html += '<div class="custom-control custom-checkbox mb-1">' +
        '<input type="checkbox" class="custom-control-input" id="mat' + m.id + '" name="idMateria[]" value="' + m.id + '"' + (ocupada ? " disabled" : "") + '>' +
        '<label class="custom-control-label" for="mat' + m.id + '">' + textoSeguro(m.nombre) +
        (detalle ? ' <small class="text-warning">(ya asignada → ' + detalle + ')</small>' : "") + "</label></div>";
    });
    caja.removeClass("text-muted").html(html);
  }, "json");
}

$(document).on("change", "#idNivelDistributivo", function () {
  var idGrado = $(this).val();
  var caja = $("#paralelosDistributivo");
  if (!idGrado) {
    caja.addClass("text-muted").html("Primero elija el nivel.");
    cargarMateriasDistributivo();
    return;
  }
  $.post(SERVERURL + "ajax/distributivo.ajax.php", { accion: "paralelos", idGrado: idGrado }, function (paralelos) {
    if (!paralelos.length) {
      caja.addClass("text-muted").html("Este nivel no tiene paralelos en el año lectivo activo.");
    } else {
      var html = "";
      paralelos.forEach(function (p) {
        html += '<div class="custom-control custom-checkbox custom-control-inline">' +
          '<input type="checkbox" class="custom-control-input" id="gp' + p.id + '" name="idGrados[]" value="' + p.id + '" data-nombre="Paralelo ' + textoSeguro(p.paralelo) + '">' +
          '<label class="custom-control-label" for="gp' + p.id + '">Paralelo ' + textoSeguro(p.paralelo) + "</label></div>";
      });
      caja.removeClass("text-muted").html(html);
    }
    cargarMateriasDistributivo();
  }, "json");
});

$(document).on("change", "#paralelosDistributivo input", cargarMateriasDistributivo);
