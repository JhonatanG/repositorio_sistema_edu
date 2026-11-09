/*=============================================
HORARIO POR CURSO CON ARRASTRAR Y SOLTAR (FULLCALENDAR)
Se usa una semana fija de referencia (lunes 1 de enero de 2024):
solo importan el día de la semana y las horas.
=============================================*/
(function () {
  var elemento = document.getElementById("calendarioHorario");
  if (!elemento) return;

  var URL_AJAX = SERVERURL + "ajax/horario.ajax.php";
  var COLORES = ["#14468c", "#c8102e", "#2e8b57", "#c99a2e", "#6f42c1", "#17a2b8", "#e8590c", "#5c7cfa", "#a61e4d", "#495057"];

  /* Un color fijo por asignatura */
  var colorDe = {};
  CLASES_HORARIO.forEach(function (c, i) { colorDe[c.id] = COLORES[i % COLORES.length]; });

  function dos(n) { return (n < 10 ? "0" : "") + n; }
  function hora(fecha) { return dos(fecha.getHours()) + ":" + dos(fecha.getMinutes()); }
  function dia(fecha) { var d = fecha.getDay(); return d === 0 ? 7 : d; }
  function sumarMinutos(hhmm, minutos) {
    var p = hhmm.split(":"), total = parseInt(p[0], 10) * 60 + parseInt(p[1], 10) + minutos;
    return dos(Math.floor(total / 60) % 24) + ":" + dos(total % 60);
  }
  function aviso(mensaje) {
    Swal.fire({ icon: "error", title: "¡No se pudo guardar!", text: mensaje, confirmButtonText: "Cerrar" });
  }

  /* El color se asigna en los datos del bloque, antes de dibujarlo */
  function conColor(evento) {
    evento.backgroundColor = evento.borderColor = colorDe[evento.extendedProps.clase] || COLORES[0];
    return evento;
  }

  /* Guarda en el servidor; el servidor valida choques de docente y de curso */
  function guardar(datos, alTerminar) {
    datos.accion = "guardar";
    datos.curso = CURSO_HORARIO;
    $.post(URL_AJAX, datos, function (r) {
      alTerminar(r);
      if (!r.ok) aviso(r.mensaje);
      actualizarResumen();
    }, "json").fail(function () {
      alTerminar({ ok: false });
      aviso("No hay conexión con el servidor. Recargue la página.");
    });
  }

  var calendario = new FullCalendar.Calendar(elemento, {
    locale: "es",
    initialView: "timeGridWeek",
    initialDate: "2024-01-01",
    headerToolbar: false,
    firstDay: 1,
    hiddenDays: [0],
    allDaySlot: false,
    slotMinTime: "07:00:00",
    slotMaxTime: "15:00:00",
    slotDuration: "00:20:00",
    snapDuration: "00:05:00",
    slotLabelInterval: "01:00",
    slotLabelFormat: { hour: "2-digit", minute: "2-digit", hour12: false },
    eventTimeFormat: { hour: "2-digit", minute: "2-digit", hour12: false },
    dayHeaderFormat: { weekday: "long" },
    height: "auto",
    nowIndicator: false,
    editable: true,
    selectable: true,
    selectMirror: true,
    eventOverlap: true,
    /* Solo se puede seleccionar dentro de un mismo día */
    selectAllow: function (s) { return s.start.toDateString() === new Date(s.end.getTime() - 1).toDateString(); },
    events: function (info, exito, error) {
      $.post(URL_AJAX, { accion: "eventos", curso: CURSO_HORARIO }, function (eventos) {
        exito(eventos.map(conColor));
      }, "json").fail(error);
    },
    eventDidMount: function (info) {
      info.el.title = info.event.title + " — " + info.event.extendedProps.docente;
    },
    eventContent: function (arg) {
      return { html: '<div class="fc-event-time">' + arg.timeText + "</div>" +
        '<div class="fc-event-title"><b>' + $("<div>").text(arg.event.title).html() + "</b></div>" +
        '<div class="horario-docente">' + $("<div>").text(arg.event.extendedProps.docente).html() + "</div>" };
    },
    eventsSet: actualizarResumen,
    /* Clic o arrastre en un espacio vacío: nuevo bloque */
    select: function (info) {
      var inicio = hora(info.start), fin = hora(info.end);
      /* Un clic simple dura 20 min: se propone un periodo de 40 min */
      if ((info.end - info.start) / 60000 <= 20) fin = sumarMinutos(inicio, 40);
      abrirModal(null, dia(info.start), inicio, fin);
      calendario.unselect();
    },
    eventClick: function (info) {
      abrirModal(info.event, dia(info.event.start), hora(info.event.start), hora(info.event.end));
    },
    /* Arrastrar o estirar: se guarda al soltar; si hay choque, vuelve a su lugar */
    eventDrop: moverBloque,
    eventResize: moverBloque
  });

  function moverBloque(info) {
    var e = info.event;
    guardar({ id: e.id, clase: e.extendedProps.clase, dia: dia(e.start), inicio: hora(e.start), fin: hora(e.end) }, function (r) {
      if (!r.ok) info.revert();
    });
  }

  /*=============================================
  FORMULARIO PARA AGREGAR O CAMBIAR UN BLOQUE
  =============================================*/
  var eventoEditado = null;
  function abrirModal(evento, numeroDia, inicio, fin) {
    eventoEditado = evento;
    $("#tituloBloque").text(evento ? "Cambiar clase" : "Agregar clase");
    $("#bloqueId").val(evento ? evento.id : "");
    if (evento) $("#bloqueClase").val(evento.extendedProps.clase);
    $("#bloqueDia").val(numeroDia);
    $("#bloqueInicio").val(inicio);
    $("#bloqueFin").val(fin);
    $("#borrarBloque").toggle(!!evento);
    $("#modalBloque").modal("show");
  }

  $("#guardarBloque").on("click", function () {
    var boton = $(this).prop("disabled", true);
    guardar({
      id: $("#bloqueId").val(),
      clase: $("#bloqueClase").val(),
      dia: $("#bloqueDia").val(),
      inicio: $("#bloqueInicio").val(),
      fin: $("#bloqueFin").val()
    }, function (r) {
      boton.prop("disabled", false);
      if (!r.ok) return;
      if (eventoEditado) eventoEditado.remove();
      calendario.addEvent(conColor(r.evento));
      $("#modalBloque").modal("hide");
    });
  });

  $("#borrarBloque").on("click", function () {
    if (!eventoEditado) return;
    Swal.fire({
      title: "¿Borrar esta clase del horario?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonText: "Cancelar",
      confirmButtonText: "Sí, borrar"
    }).then(function (respuesta) {
      if (!respuesta.value) return;
      $.post(URL_AJAX, { accion: "borrar", curso: CURSO_HORARIO, id: eventoEditado.id }, function (r) {
        if (!r.ok) { aviso(r.mensaje); return; }
        eventoEditado.remove();
        $("#modalBloque").modal("hide");
        actualizarResumen();
      }, "json");
    });
  });

  /*=============================================
  RESUMEN: BLOQUES Y HORAS SEMANALES DE CADA ASIGNATURA
  =============================================*/
  function actualizarResumen() {
    var totales = {};
    calendario.getEvents().forEach(function (e) {
      var c = e.extendedProps.clase;
      totales[c] = totales[c] || { bloques: 0, minutos: 0 };
      totales[c].bloques++;
      totales[c].minutos += (e.end - e.start) / 60000;
    });
    var html = "";
    CLASES_HORARIO.forEach(function (c) {
      var t = totales[c.id] || { bloques: 0, minutos: 0 };
      var horas = Math.floor(t.minutos / 60), minutos = t.minutos % 60;
      html += '<div class="resumen-asignatura" style="border-left-color:' + colorDe[c.id] + '">' +
        "<b>" + $("<div>").text(c.materia).html() + "</b>" +
        '<div class="text-muted small">' + $("<div>").text(c.docente).html() + "</div>" +
        '<div class="small">' + (t.bloques ? t.bloques + " bloque(s) · " + (horas ? horas + " h " : "") + (minutos ? minutos + " min" : "") : '<span class="text-danger">Sin horario</span>') + "</div></div>";
    });
    $("#resumenHorario").html(html || '<p class="text-muted mb-0">Sin asignaturas.</p>');
  }

  calendario.render();
})();
