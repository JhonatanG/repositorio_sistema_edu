<?php
/*========================================================================
CALIFICACIONES PARAMETRIZABLES SEGÚN EL TIPO DE EVALUACIÓN DEL AÑO LECTIVO
- QUIMESTRAL: 2 quimestres
- TRIMESTRAL: 3 trimestres
Cada periodo tiene 3 parciales y un examen; su promedio es la media de los 4.
La nota final es la media de los promedios de todos los periodos.
==========================================================================*/
class Calificaciones{

static public function tipos(){
  return array(
    "QUIMESTRAL" => array("periodos"=>2, "nombre"=>"Quimestre", "sigla"=>"Q", "etiqueta"=>"Quimestral (2 quimestres)"),
    "TRIMESTRAL" => array("periodos"=>3, "nombre"=>"Trimestre", "sigla"=>"T", "etiqueta"=>"Trimestral (3 trimestres)")
  );
}

static public function tipoValido($tipo){
  return isset(self::tipos()[$tipo]);
}

static public function config($tipo){
  $tipos = self::tipos();
  return isset($tipos[$tipo]) ? $tipos[$tipo] : $tipos["QUIMESTRAL"];
}

/* Campos que se ingresan en cada periodo: [clave del formulario y del resultado, columna en la base, título] */
static public function campos(){
  return array(
    array("P1", "parcial1q", "P1"),
    array("P2", "parcial2q", "P2"),
    array("P3", "parcial3q", "P3"),
    array("EX", "examenq", "Ex")
  );
}

/* Calcula el promedio de cada periodo y la nota final. $notas[$k]["P1"..."EX"] para k = 1..n */
static public function calcular($notas, $periodos){
  $promedios = array();
  for($k=1; $k<=$periodos; $k++){
    $suma = 0;
    foreach(self::campos() as $c){
      $suma += (float)$notas[$k][$c[0]];
    }
    $promedios[$k] = round($suma/count(self::campos()), 2);
  }
  return array("promedios"=>$promedios, "final"=>round(array_sum($promedios)/$periodos, 2));
}

/* Insignia de color: menos de 7 rojo, de 7 a menos de 8.5 amarillo, desde 8.5 verde */
static public function insignia($nota){
  if($nota === null || $nota === ""){
    return '<span class="text-muted">-</span>';
  }
  $color = $nota < 7 ? "bg-danger" : ($nota < 8.5 ? "bg-warning" : "bg-success");
  return '<span class="badge '.$color.'">'.number_format((float)$nota, 2).'</span>';
}

static public function valor($nota){
  return ($nota === null || $nota === "") ? '<span class="text-muted">-</span>' : number_format((float)$nota, 2);
}

/*=============================================
TABLA DE REPORTE (MISMA PARA TODOS LOS REPORTES)
$filas usa los alias P1Q1, P2Q1, P3Q1, EXQ1, Q1 ... NF
=============================================*/
static public function tablaReporte($filas, $tipo, $campoNombre, $tituloNombre){
  $cfg = self::config($tipo);
  echo '<div class="mb-2"><span class="badge badge-info">Evaluación '.$cfg["etiqueta"].'</span></div>';
  echo '<table class="table table-bordered table-striped dt-responsive tabla-notas" id="tablaboton" data-page-length="50">';
  echo '<thead><tr><th style="width: 8px;">#</th><th>'.$tituloNombre.'</th>';
  for($k=1; $k<=$cfg["periodos"]; $k++){
    foreach(self::campos() as $c){
      echo '<th class="periodo-'.$k.'">'.$c[2].$cfg["sigla"].$k.'</th>';
    }
    echo '<th class="periodo-'.$k.' promedio">'.$cfg["sigla"].$k.'</th>';
  }
  echo '<th class="promedio">Promedio final</th></tr></thead><tbody>';
  $cont = 0;
  foreach((array)$filas as $f){
    $cont++;
    echo '<tr><td>'.$cont.'</td><td>'.htmlspecialchars($f[$campoNombre]).'</td>';
    for($k=1; $k<=$cfg["periodos"]; $k++){
      foreach(self::campos() as $c){
        echo '<td>'.self::valor($f[$c[0]."Q".$k]).'</td>';
      }
      echo '<td>'.self::insignia($f["Q".$k]).'</td>';
    }
    echo '<td>'.self::insignia($f["NF"]).'</td></tr>';
  }
  echo '</tbody></table>';
}

}
