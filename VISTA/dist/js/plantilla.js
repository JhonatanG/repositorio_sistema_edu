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
