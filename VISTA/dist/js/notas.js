/*=============================================
CALCULAR PROMEDIOS AL INGRESAR NOTAS
Sirve para 2 quimestres o 3 trimestres: el número de periodos viene en data-periodos.
(El servidor vuelve a calcular todo al guardar.)
=============================================*/
function calcularFilaNotas(fila) {
  var periodos = parseInt($(fila).closest("table").data("periodos"), 10) || 2;
  var suma = 0;
  for (var k = 1; k <= periodos; k++) {
    var total = 0, cantidad = 0;
    $(fila).find('.nota-input[data-periodo="' + k + '"]').each(function () {
      total += parseFloat(this.value) || 0;
      cantidad++;
    });
    var promedio = cantidad ? total / cantidad : 0;
    $(fila).find('.promedio-periodo[data-periodo="' + k + '"]').val(promedio.toFixed(2));
    suma += parseFloat(promedio.toFixed(2));
  }
  $(fila).find(".promedio-final").val((suma / periodos).toFixed(2));
}

$(document).on("input change", ".tabla-ingreso-notas .nota-input", function () {
  var valor = parseFloat(this.value);
  $(this).toggleClass("is-invalid", this.value !== "" && (isNaN(valor) || valor < 0 || valor > 10));
  calcularFilaNotas($(this).closest("tr"));
});

$(function () {
  $(".tabla-ingreso-notas tbody tr").each(function () {
    calcularFilaNotas(this);
  });
});
