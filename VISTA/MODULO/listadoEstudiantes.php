<?php
/*=============================================
REPORTE: LISTADO DE ESTUDIANTES POR NIVEL Y PARALELO (EXPORTABLE A PDF)
=============================================*/
$periodos = ModeloMatricula::mdlPeriodos();
$filtros = isset($_SESSION["filtros"]["listadoEstudiantes"]) ? $_SESSION["filtros"]["listadoEstudiantes"]
  : array("periodo"=>(!empty($periodos) ? $periodos[0]["id"] : 0), "nivel"=>0, "curso"=>0);
if(isset($_POST["filtrarListado"])){
  $filtros = array("periodo"=>(int)$_POST["periodo"], "nivel"=>(int)$_POST["nivel"], "curso"=>(int)$_POST["curso"]);
  $_SESSION["filtros"]["listadoEstudiantes"] = $filtros;
}
$cursos = ModeloMatricula::mdlCursosPeriodo($filtros["periodo"]);
/*EL CURSO SOLO VALE SI PERTENECE AL NIVEL ELEGIDO*/
$cursoValido = false;
foreach($cursos as $c){
  if($c["id"] == $filtros["curso"] && (!$filtros["nivel"] || $c["id_grado"] == $filtros["nivel"])){
    $cursoValido = true;
  }
}
if(!$cursoValido){
  $filtros["curso"] = 0;
}
$estudiantes = ModeloMatricula::mdlListadoEstudiantes($filtros["periodo"], $filtros["nivel"], $filtros["curso"]);

/*TEXTO DE LOS FILTROS (TAMBIÉN SALE EN EL PDF)*/
$nombrePeriodo = "";
foreach($periodos as $p){ if($p["id"] == $filtros["periodo"]){ $nombrePeriodo = $p["nombre"]; } }
$nombreNivel = "Todos los niveles";
$nombreCurso = "todos los paralelos";
foreach($cursos as $c){
  if($c["id_grado"] == $filtros["nivel"]){ $nombreNivel = $c["grado"]; }
  if($c["id"] == $filtros["curso"]){ $nombreCurso = "paralelo ".$c["paralelo"]; }
}
$subtitulo = "Año lectivo ".$nombrePeriodo." · ".$nombreNivel.($filtros["nivel"] ? ", ".$nombreCurso : "")." · ".count($estudiantes)." estudiante(s)";

/*TOTALES*/
$porGenero = array("M"=>0, "F"=>0);
$porCurso = array();
foreach($estudiantes as $e){
  if(isset($porGenero[$e["genero"]])){ $porGenero[$e["genero"]]++; }
  $porCurso[$e["curso"]] = (isset($porCurso[$e["curso"]]) ? $porCurso[$e["curso"]] : 0) + 1;
}
$niveles = array();
foreach($cursos as $c){ $niveles[$c["id_grado"]] = $c["grado"]; }
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-list-ol"></i> Listado de estudiantes</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Listado de estudiantes</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <form method="post" class="row align-items-end" id="filtrosListado">
            <input type="hidden" name="filtrarListado" value="1">
            <div class="col-md-3 form-group">
              <label>Año lectivo</label>
              <select class="form-control" name="periodo" onchange="this.form.nivel.value='0'; this.form.curso.value='0'; this.form.submit()">
                <?php
                foreach($periodos as $p){
                  echo '<option value="'.$p["id"].'"'.($p["id"]==$filtros["periodo"] ? ' selected' : '').'>'.htmlspecialchars($p["nombre"]).($p["estado"]==1 ? ' (activo)' : '').'</option>';
                }
                ?>
              </select>
            </div>
            <div class="col-md-3 form-group">
              <label>Nivel</label>
              <select class="form-control" name="nivel" id="filtroNivel">
                <option value="0">Todos los niveles</option>
                <?php
                foreach($niveles as $id=>$n){
                  echo '<option value="'.$id.'"'.($id==$filtros["nivel"] ? ' selected' : '').'>'.htmlspecialchars($n).'</option>';
                }
                ?>
              </select>
            </div>
            <div class="col-md-3 form-group">
              <label>Paralelo</label>
              <select class="form-control" name="curso" id="filtroCurso">
                <option value="0">Todos los paralelos</option>
                <?php
                foreach($cursos as $c){
                  echo '<option value="'.$c["id"].'" data-nivel="'.$c["id_grado"].'"'.($c["id"]==$filtros["curso"] ? ' selected' : '').'>Paralelo '.htmlspecialchars($c["paralelo"]).'</option>';
                }
                ?>
              </select>
            </div>
            <div class="col-md-3 form-group">
              <button class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Aplicar filtros</button>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
            <div>
              <h5 class="mb-1" id="subtituloReporte" data-orientacion="horizontal"><?php echo htmlspecialchars($subtitulo); ?></h5>
              <div class="contadores">
                <span class="contador justificada"><i class="fas fa-users"></i> Total: <b><?php echo count($estudiantes); ?></b></span>
                <span class="contador presente"><i class="fas fa-male"></i> Hombres: <b><?php echo $porGenero["M"]; ?></b></span>
                <span class="contador falta"><i class="fas fa-female"></i> Mujeres: <b><?php echo $porGenero["F"]; ?></b></span>
                <?php if(count($porCurso) > 1){ foreach($porCurso as $curso=>$n){ echo '<span class="contador">'.htmlspecialchars($curso).': <b>'.$n.'</b></span>'; } } ?>
              </div>
            </div>
            <div class="mt-2 mt-md-0">
              <button type="button" class="btn btn-danger" id="descargarPdf" <?php echo empty($estudiantes) ? 'disabled' : ''; ?>><i class="fas fa-file-pdf"></i> Descargar PDF</button>
            </div>
          </div>

          <div class="columnas-listado mb-2">
            <small class="text-muted mr-2">Columnas:</small>
            <?php
            /*COLUMNAS OPCIONALES (ÍNDICE EN LA TABLA => NOMBRE, VISIBLE AL INICIO)*/
            $opcionales = array(4=>array("Edad", true), 5=>array("Fecha nac.", false), 7=>array("Representante", true),
              8=>array("Teléfono repr.", true), 9=>array("Correo", false), 10=>array("Dirección", false), 11=>array("Usuario", false));
            foreach($opcionales as $i=>$o){
              echo '<div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input columnaListado" id="col'.$i.'" data-columna="'.$i.'"'.($o[1] ? ' checked' : '').'>
                <label class="custom-control-label" for="col'.$i.'">'.$o[0].'</label></div>';
            }
            ?>
          </div>

          <table class="table table-bordered table-striped dt-responsive" id="tablaboton" data-page-length="100">
            <thead>
              <tr>
                <th style="width: 8px;">#</th>
                <th>Cédula</th>
                <th>Apellidos y nombres</th>
                <th>Género</th>
                <th>Edad</th>
                <th>Fecha nac.</th>
                <th>Curso</th>
                <th>Representante</th>
                <th>Teléfono repr.</th>
                <th>Correo</th>
                <th>Dirección</th>
                <th>Usuario</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($estudiantes as $i=>$e){
                echo '<tr>
                  <td>'.($i+1).'</td>
                  <td>'.htmlspecialchars($e["cedula"]).'</td>
                  <td>'.htmlspecialchars($e["apellidos"]." ".$e["nombres"]).'</td>
                  <td>'.($e["genero"]=="M" ? "Masculino" : ($e["genero"]=="F" ? "Femenino" : htmlspecialchars($e["genero"]))).'</td>
                  <td>'.$e["edad"].'</td>
                  <td data-order="'.$e["fecha_nacimiento"].'">'.date("d/m/Y", strtotime($e["fecha_nacimiento"])).'</td>
                  <td>'.htmlspecialchars($e["curso"]).'</td>
                  <td>'.htmlspecialchars($e["representante"]).'</td>
                  <td>'.htmlspecialchars($e["telefono_representante"]).'</td>
                  <td>'.htmlspecialchars($e["email"]).'</td>
                  <td>'.htmlspecialchars($e["direccion"]).'</td>
                  <td>'.htmlspecialchars($e["usuario"]).'</td>
                </tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
<script>
$(function () {
  /*PARALELOS SEGÚN EL NIVEL ELEGIDO*/
  function filtrarParalelos() {
    var nivel = $("#filtroNivel").val();
    $("#filtroCurso option[data-nivel]").each(function () {
      var mostrar = nivel !== "0" && $(this).data("nivel") == nivel;
      $(this).toggle(mostrar).prop("disabled", !mostrar);
    });
    if ($("#filtroCurso option:selected").prop("disabled")) $("#filtroCurso").val("0");
    $("#filtroCurso").prop("disabled", nivel === "0");
  }
  $("#filtroNivel").on("change", filtrarParalelos);
  filtrarParalelos();
  $("#filtrosListado").on("submit", function () { $("#filtroCurso").prop("disabled", false); });

  /*COLUMNAS OPCIONALES Y BOTÓN PDF (CUANDO LA TABLA YA ESTÁ LISTA)*/
  $("#tablaboton").on("init.dt", function () {
    var tabla = $(this).DataTable();
    $(".columnaListado").each(function () { tabla.column($(this).data("columna")).visible(this.checked); });
    $(".columnaListado").on("change", function () { tabla.column($(this).data("columna")).visible(this.checked); });
    $("#descargarPdf").on("click", function () { tabla.button(".buttons-pdf").trigger(); });
  });
});
</script>
