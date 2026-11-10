/*=============================================
DATOS PARA LOS REPORTES (EXCEL, PDF E IMPRESIÓN)
=============================================*/
var ESCUDO_BASE64 = "";
(function () {
  var img = new Image();
  img.onload = function () {
    try {
      var c = document.createElement("canvas");
      c.width = img.naturalWidth;
      c.height = img.naturalHeight;
      c.getContext("2d").drawImage(img, 0, 0);
      ESCUDO_BASE64 = c.toDataURL("image/png");
    } catch (e) {}
  };
  img.src = SERVERURL + "VISTA/dist/img/escudo.png";
})();
function tituloSeccion() {
  return $.trim($(".content-header h1").first().text()) || "Reporte";
}
function tituloReporte() {
  return 'U.E. "Cristóbal Colón" - ' + tituloSeccion();
}
function fechaReporte() {
  return new Date().toLocaleString("es-EC", { dateStyle: "long", timeStyle: "short" });
}

$(document).ready(function() {
    $('#tablas').DataTable({
    responsive: true,
    "language":{
        "processing": "Procesando...",
        "lengthMenu": "Mostrar _MENU_ registros",
        "zeroRecords": "No se encontraron resultados",
        "emptyTable": "Ningún dato disponible en esta tabla",
        "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "infoFiltered": "(filtrado de un total de _MAX_ registros)",
        "search": "Buscar:",
        "infoThousands": ",",
        "loadingRecords": "Cargando...",
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
        },
        "aria": {
            "sortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "buttons": {
            "copy": "Copiar",
            "colvis": "Visibilidad",
            "collection": "Colección",
            "colvisRestore": "Restaurar visibilidad",
            "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
            "copySuccess": {
                "1": "Copiada 1 fila al portapapeles",
                "_": "Copiadas %d fila al portapapeles"
            },
            "copyTitle": "Copiar al portapapeles",
            "csv": "CSV",
            "excel": "Excel",
            "pageLength": {
                "-1": "Mostrar todas las filas",
                "1": "Mostrar 1 fila",
                "_": "Mostrar %d filas"
            },
            "pdf": "PDF",
            "print": "Imprimir"
        },
        "autoFill": {
            "cancel": "Cancelar",
            "fill": "Rellene todas las celdas con <i>%d<\/i>",
            "fillHorizontal": "Rellenar celdas horizontalmente",
            "fillVertical": "Rellenar celdas verticalmentemente"
        },
        "decimal": ",",
        "searchBuilder": {
            "add": "Añadir condición",
            "button": {
                "0": "Constructor de búsqueda",
                "_": "Constructor de búsqueda (%d)"
            },
            "clearAll": "Borrar todo",
            "condition": "Condición",
            "conditions": {
                "date": {
                    "after": "Despues",
                    "before": "Antes",
                    "between": "Entre",
                    "empty": "Vacío",
                    "equals": "Igual a",
                    "notBetween": "No entre",
                    "notEmpty": "No Vacio",
                    "not": "Diferente de"
                },
                "number": {
                    "between": "Entre",
                    "empty": "Vacio",
                    "equals": "Igual a",
                    "gt": "Mayor a",
                    "gte": "Mayor o igual a",
                    "lt": "Menor que",
                    "lte": "Menor o igual que",
                    "notBetween": "No entre",
                    "notEmpty": "No vacío",
                    "not": "Diferente de"
                },
                "string": {
                    "contains": "Contiene",
                    "empty": "Vacío",
                    "endsWith": "Termina en",
                    "equals": "Igual a",
                    "notEmpty": "No Vacio",
                    "startsWith": "Empieza con",
                    "not": "Diferente de"
                },
                "array": {
                    "not": "Diferente de",
                    "equals": "Igual",
                    "empty": "Vacío",
                    "contains": "Contiene",
                    "notEmpty": "No Vacío",
                    "without": "Sin"
                }
            },
            "data": "Data",
            "deleteTitle": "Eliminar regla de filtrado",
            "leftTitle": "Criterios anulados",
            "logicAnd": "Y",
            "logicOr": "O",
            "rightTitle": "Criterios de sangría",
            "title": {
                "0": "Constructor de búsqueda",
                "_": "Constructor de búsqueda (%d)"
            },
            "value": "Valor"
        },
        "searchPanes": {
            "clearMessage": "Borrar todo",
            "collapse": {
                "0": "Paneles de búsqueda",
                "_": "Paneles de búsqueda (%d)"
            },
            "count": "{total}",
            "countFiltered": "{shown} ({total})",
            "emptyPanes": "Sin paneles de búsqueda",
            "loadMessage": "Cargando paneles de búsqueda",
            "title": "Filtros Activos - %d"
        },
        "select": {
            "1": "%d fila seleccionada",
            "_": "%d filas seleccionadas",
            "cells": {
                "1": "1 celda seleccionada",
                "_": "$d celdas seleccionadas"
            },
            "columns": {
                "1": "1 columna seleccionada",
                "_": "%d columnas seleccionadas"
            }
        },
        "thousands": ".",
        "datetime": {
            "previous": "Anterior",
            "next": "Proximo",
            "hours": "Horas",
            "minutes": "Minutos",
            "seconds": "Segundos",
            "unknown": "-",
            "amPm": [
                "am",
                "pm"
            ]
        },
        "editor": {
            "close": "Cerrar",
            "create": {
                "button": "Nuevo",
                "title": "Crear Nuevo Registro",
                "submit": "Crear"
            },
            "edit": {
                "button": "Editar",
                "title": "Editar Registro",
                "submit": "Actualizar"
            },
            "remove": {
                "button": "Eliminar",
                "title": "Eliminar Registro",
                "submit": "Eliminar",
                "confirm": {
                    "_": "¿Está seguro que desea eliminar %d filas?",
                    "1": "¿Está seguro que desea eliminar 1 fila?"
                }
            },
            "error": {
                "system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
            },
            "multi": {
                "title": "Múltiples Valores",
                "info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, hacer click o tap aquí, de lo contrario conservarán sus valores individuales.",
                "restore": "Deshacer Cambios",
                "noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo."
            }
        }
    } 
   });
  
});
$(document).ready(function () {
       //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()
});
$(document).ready(function() {    
    $('#tablaboton, .tabla-exportable').DataTable({        
        language: {
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast":"Último",
                    "sNext":"Siguiente",
                    "sPrevious": "Anterior"
			     },
			     "sProcessing":"Procesando...",
            },
        //para usar los botones   
        responsive: "true",
        dom: 'Bfrtilp',       
        buttons:[ 
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel"></i> Excel',
				titleAttr: 'Exportar a Excel',
				className: 'btn btn-success',
				title:     tituloReporte,
				exportOptions: { columns: ':visible:not(.no-exportar)' }
			},
			{
				extend:    'pdfHtml5',
				text:      '<i class="fas fa-file-pdf"></i> PDF',
				titleAttr: 'Exportar a PDF',
				className: 'btn btn-danger',
				title:     tituloReporte,
				messageTop: function () {
					var filtros = $.trim($("#subtituloReporte").text());
					return (filtros ? filtros + "\n" : "") + "Generado el " + fechaReporte();
				},
				exportOptions: { columns: ':visible:not(.no-exportar)' },
				customize: function (doc) {
					/*HORIZONTAL SI LA PÁGINA LO PIDE; TABLA A TODO EL ANCHO; NÚMERO DE PÁGINA*/
					if ($("#subtituloReporte").data("orientacion") === "horizontal") doc.pageOrientation = "landscape";
					doc.content.forEach(function (bloque) {
						if (bloque.table) bloque.table.widths = bloque.table.body[0].map(function (celda) { return celda.text === "#" ? "auto" : "*"; });
					});
					doc.footer = function (pagina, total) {
						return { text: "Página " + pagina + " de " + total, alignment: "right", fontSize: 8, margin: [0, 0, 40, 0], color: "#6c757d" };
					};
					if (ESCUDO_BASE64) {
						doc.content.splice(0, 0, { image: ESCUDO_BASE64, width: 45, alignment: 'center', margin: [0, 0, 0, 6] });
					}
					doc.defaultStyle.fontSize = 9;
					doc.styles.tableHeader.fillColor = '#14468c';
					doc.styles.title.fontSize = 13;
					doc.styles.title.bold = true;
				}
			},
			{
				extend:    'print',
				text:      '<i class="fa fa-print"></i> Imprimir',
				titleAttr: 'Imprimir',
				className: 'btn btn-info',
				title:     '',
				exportOptions: { columns: ':visible:not(.no-exportar)' },
				messageTop: function () {
					return '<div class="encabezado-reporte">' +
						'<img src="' + SERVERURL + 'VISTA/dist/img/escudo.png" alt="Escudo">' +
						'<div><h2>Unidad Educativa “Cristóbal Colón”</h2>' +
						'<h4>' + $('<div>').text(tituloSeccion()).html() + '</h4>' +
						'<small>Generado el ' + fechaReporte() + '</small></div></div>';
				},
				customize: function (win) {
					$(win.document.head).append('<style>' +
						'.encabezado-reporte{display:flex;align-items:center;gap:16px;border-bottom:3px solid #14468c;padding-bottom:10px;margin-bottom:14px}' +
						'.encabezado-reporte img{width:70px}' +
						'.encabezado-reporte h2{margin:0;font-size:20px;color:#0a2350}' +
						'.encabezado-reporte h4{margin:2px 0;font-size:15px}' +
						'table thead th{background:#14468c!important;color:#fff!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}' +
						'</style>');
				}
			},
		]
    });     
});

$(document).ready(function () {
    //Initialize Select2 Elements
    $('.select2').select2();

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4',
        placeholder: "Seleccionar"
    });
});


/*=============================================
ENVIAR UNA ACCIÓN (BORRAR) POR POST CON EL TOKEN DE SEGURIDAD
=============================================*/
function enviarAccion(ruta, datos) {
  var form = document.createElement("form");
  form.method = "post";
  form.action = SERVERURL + ruta + "/";
  datos.token = TOKEN;
  for (var clave in datos) {
    var input = document.createElement("input");
    input.type = "hidden";
    input.name = clave;
    input.value = datos[clave];
    form.appendChild(input);
  }
  document.body.appendChild(form);
  form.submit();
}

/*=============================================
MENÚ: RESALTAR LA PÁGINA ACTUAL Y ABRIR SU GRUPO
=============================================*/
$(function () {
  var ruta = window.location.href.replace(SERVERURL, "").split(/[/?#]/)[0] || "inicio";
  $(".nav-sidebar a.nav-link").each(function () {
    if ((this.getAttribute("href") || "#") === "#") return;
    var destino = (this.href || "").replace(SERVERURL, "").split(/[/?#]/)[0];
    if (destino === ruta) {
      $(this).addClass("active");
      $(this).parents(".nav-item.has-treeview, .nav-item").has(".nav-treeview").addClass("menu-open")
        .children("a.nav-link").addClass("active");
    }
  });
});

/*=============================================
FICHA DE DOCENTE, ESTUDIANTE O REPRESENTANTE CON SU FOTO
Botón: <button class="btnFicha" data-tipo="D|E|R" data-id="...">
=============================================*/
(function () {
  var nombresTipo = { D: "Docente", E: "Estudiante", R: "Representante" };
  var fotoDefecto = SERVERURL + "VISTA/img/anonymous.png";

  function esc(texto) {
    return $("<div>").text(texto == null ? "" : String(texto)).html();
  }
  function foto(ruta) {
    return ruta ? SERVERURL + ruta : fotoDefecto;
  }
  function fecha(valor) {
    if (!valor) return "";
    var p = String(valor).substr(0, 10).split("-");
    return p.length === 3 ? p[2] + "/" + p[1] + "/" + p[0] : valor;
  }
  function edad(valor) {
    if (!valor) return "";
    var hoy = new Date(), nac = new Date(String(valor).substr(0, 10) + "T00:00:00");
    var anios = hoy.getFullYear() - nac.getFullYear();
    var m = hoy.getMonth() - nac.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) anios--;
    return isNaN(anios) ? "" : anios + " años";
  }
  function genero(valor) {
    return valor === "M" ? "Masculino" : valor === "F" ? "Femenino" : valor;
  }
  /* Un dato de la ficha; si está vacío se muestra un guion */
  function dato(icono, titulo, valor, ancho) {
    return '<div class="' + (ancho || "col-sm-6") + ' ficha-dato"><i class="fas fa-' + icono + '"></i><div><small>' + titulo +
      '</small><span>' + (valor ? esc(valor) : '<em class="text-muted">—</em>') + "</span></div></div>";
  }
  function seccion(titulo, contenido) {
    return '<h6 class="ficha-seccion">' + titulo + '</h6><div class="row">' + contenido + "</div>";
  }
  /* Persona relacionada (representante o estudiante a cargo) en una línea con su foto */
  function persona(p, detalle) {
    return '<div class="col-sm-6"><div class="ficha-persona"><img src="' + esc(foto(p.foto)) + '" alt="">' +
      "<div><b>" + esc(p.nombres + " " + p.apellidos) + "</b><small>" + esc(detalle) + "</small></div></div></div>";
  }

  function armar(tipo, d) {
    $("#fichaFoto").attr("src", foto(d.foto));
    $("#fichaTipo").text(nombresTipo[tipo]);
    $("#fichaNombre").text(d.nombres + " " + d.apellidos);
    var sub = "C.I. " + d.cedula + " · Usuario " + d.usuario;
    if (tipo === "D") sub += d.estado == 1 ? " · Activo" : " · Inactivo";
    $("#fichaSub").text(sub);

    var html = seccion("Datos personales",
      dato("id-card", "Cédula", d.cedula) +
      dato("venus-mars", "Género", genero(d.genero)) +
      (tipo !== "R" ? dato("birthday-cake", "Fecha de nacimiento", fecha(d.fecha_nacimiento)) + dato("hourglass-half", "Edad", edad(d.fecha_nacimiento)) : "")
    );
    html += seccion("Contacto",
      dato("at", "Correo electrónico", d.email) +
      dato("phone", "Teléfono", d.telefono) +
      dato("map-marker-alt", "Dirección", d.direccion, "col-12")
    );

    var anio = d.periodo ? " (" + esc(d.periodo) + ")" : "";
    if (tipo === "E") {
      html += seccion("Matrícula" + anio, dato("school", "Curso", d.curso || "Sin matrícula en el año activo", "col-12"));
      html += seccion("Representante", d.representante
        ? persona(d.representante, "C.I. " + d.representante.cedula + (d.representante.telefono ? " · " + d.representante.telefono : ""))
        : '<div class="col-12 text-muted">Sin representante asignado</div>');
    }
    if (tipo === "R") {
      var lista = (d.representados || []).map(function (e) {
        return persona(e, "C.I. " + e.cedula + " · " + (e.curso || "Sin matrícula"));
      }).join("");
      html += seccion("Estudiantes a su cargo" + anio, lista || '<div class="col-12 text-muted">No tiene estudiantes registrados</div>');
    }
    if (tipo === "D") {
      var clases = (d.clases || []).map(function (c) {
        return '<span class="ficha-clase"><b>' + esc(c.materia) + "</b> " + esc(c.curso) + "</span>";
      }).join("");
      html += seccion("Distributivo" + anio, '<div class="col-12">' + (clases || '<span class="text-muted">Sin clases asignadas</span>') + "</div>");
    }
    $("#fichaCuerpo").html(html);
  }

  $(document).on("click", ".btnFicha", function () {
    var tipo = $(this).data("tipo"), id = $(this).data("id");
    $("#fichaFoto").attr("src", fotoDefecto);
    $("#fichaTipo, #fichaNombre, #fichaSub").text("");
    $("#fichaCuerpo").html('<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
    $("#modalFicha").modal("show");
    $.post(SERVERURL + "ajax/ficha.ajax.php", { tipo: tipo, id: id }, null, "json")
      .done(function (d) {
        if (d.error) { $("#fichaCuerpo").html('<div class="alert alert-warning mb-0">' + esc(d.error) + "</div>"); return; }
        armar(tipo, d);
      })
      .fail(function () {
        $("#fichaCuerpo").html('<div class="alert alert-danger mb-0">No se pudo cargar la ficha. Recargue la página.</div>');
      });
  });

  /* Imprimir solo la ficha, con los estilos del sistema */
  $(document).on("click", "#imprimirFicha", function () {
    var estilos = $('link[rel="stylesheet"]').map(function () { return this.outerHTML; }).get().join("");
    var ventana = window.open("", "_blank");
    if (!ventana) return;
    ventana.document.write("<!doctype html><html><head><meta charset='utf-8'><title>" + esc($("#fichaNombre").text()) +
      "</title>" + estilos + "<style>body{padding:24px;background:#fff}.ficha .close{display:none}</style></head><body><div class='modal-content ficha'>" +
      $("#modalFicha .ficha-cabecera")[0].outerHTML + "<div class='modal-body'>" + $("#fichaCuerpo").html() + "</div></div></body></html>");
    ventana.document.close();
    ventana.onload = function () { ventana.focus(); ventana.print(); };
  });
})();
