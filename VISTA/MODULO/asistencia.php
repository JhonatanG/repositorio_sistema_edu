<?php Seguridad::recordarFiltros("asistencia", array("idGrado","idMateria")); ?>
<?php
/*=============================================
TOMA DE ASISTENCIA (DOCENTE)
El docente marca Presente, Falta o Fuga. Las faltas las justifica Secretaría.
=============================================*/
$hoy = ControladorHorario::diaHoy();
$clasesHoy = ModeloHorario::mdlClasesDelDia($_SESSION["id"], $hoy);
ControladorAsistencia::ctrCrearAsistencia();
$resultado = ControladorAsistencia::ctrListaEstudiante();
$clase = ($resultado && !empty($resultado["idClase"])) ? ModeloAsistencia::mdlInfoClase($resultado["idClase"]) : null;
$estados = array(
  "P"  => array("Presente", "presente", "fa-check"),
  "FI" => array("Falta", "falta", "fa-times"),
  "FG" => array("Fuga", "fuga", "fa-running"),
  "FJ" => array("Falta justificada", "justificada", "fa-file-medical")
);
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-calendar-check"></i> Asistencia</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Asistencia</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">

      <!-- CLASES DE HOY -->
      <h5 class="titulo-seccion"><i class="fas fa-clock"></i> Sus clases de hoy, <?php echo strtolower(ControladorHorario::nombreDia($hoy))." ".date("d/m/Y"); ?></h5>
      <?php
      if(empty($clasesHoy)){
        echo '<div class="alert alert-light border">Hoy no tiene clases en su horario, por eso no puede tomar lista.</div>';
      }else{
        include __DIR__."/clasesHoy.php";
      }
      ?>

      <!-- BUSCAR OTRA CLASE -->
      <details class="buscar-clase mb-3"<?php echo empty($clasesHoy) ? " open" : ""; ?>>
        <summary><i class="fas fa-search"></i> Buscar otra de sus clases</summary>
        <form method="post" class="row mt-2">
          <input type="hidden" value="<?php echo $_SESSION["id"]; ?>" id="idDocenteG" name="idDocenteG">
          <div class="col-md-5 form-group">
            <label>Curso</label>
            <select class="form-control select2bs4" style="width: 100%;" name="idGrado" id="idGrado">
              <option value="">Seleccionar</option>
              <?php
              foreach(ControladorAsistencia::ctrListaGradosDocente("id_docente", $_SESSION["id"]) as $value){
                echo '<option class="btnGradoAsistencia" value="'.$value["id"].'"'.((isset($_POST["idGrado"]) && $_POST["idGrado"]==$value["id"]) ? ' selected' : '').'>'.htmlspecialchars($value["grado"]).'</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-5 form-group">
            <label>Asignatura</label>
            <select class="form-control select2bs4 datos" name="idMateria" id="idMateria" style="width: 100%;">
              <?php
              if(!empty($_POST["idGrado"])){
                foreach(ControladorAsistencia::ctrListaMDoc("id_docente", $_SESSION["id"], "id_grado_docente", $_POST["idGrado"]) as $mat){
                  echo '<option value="'.$mat["id"].'"'.((isset($_POST["idMateria"]) && $_POST["idMateria"]==$mat["id"]) ? ' selected' : '').'>'.htmlspecialchars($mat["materia"]).'</option>';
                }
              }
              ?>
            </select>
          </div>
          <div class="col-md-2 form-group d-flex align-items-end">
            <button class="btn btn-primary btn-block"><i class="fas fa-search"></i> Abrir</button>
          </div>
        </form>
      </details>

      <?php if($resultado){ ?>
      <div class="card tarjeta-lista">
        <?php if($clase){ ?>
        <div class="cabecera-lista">
          <div>
            <h4 class="mb-0"><?php echo htmlspecialchars($clase["materia"]); ?></h4>
            <span><?php echo htmlspecialchars($clase["curso"]); ?></span>
          </div>
          <div class="text-right">
            <div class="fecha-lista"><?php echo date("d/m/Y"); ?></div>
            <small><?php echo ControladorHorario::nombreDia($hoy); ?></small>
          </div>
        </div>
        <?php } ?>
        <div class="card-body">
          <?php
          /*AVISOS: SIN HORARIO HOY, SIN ESTUDIANTES O LISTA YA TOMADA*/
          if($resultado["aviso"]!="" && $resultado["tipo"]!="success"){
            echo '<div class="alert alert-warning mb-0"><i class="fas fa-exclamation-triangle"></i> '.htmlspecialchars($resultado["aviso"]).'</div>';
          }

          /*LISTA YA TOMADA: SE MUESTRA EL RESUMEN*/
          if($resultado["tipo"]=="success" && $clase){
            $registrada = ModeloAsistencia::mdlAsistenciaDelDia($clase["id"], date("Y-m-d"));
            $cuenta = array("P"=>0, "FI"=>0, "FG"=>0, "FJ"=>0);
            foreach($registrada as $r){ $cuenta[$r["asistencia"]]++; }
            echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> La lista de hoy ya fue tomada. Si una falta debe justificarse, Secretaría lo hace con su respaldo.</div>';
            echo '<div class="contadores mb-3">';
            foreach(array("P","FI","FG","FJ") as $e){
              if($e=="FJ" && $cuenta["FJ"]==0){ continue; }
              echo '<span class="contador '.$estados[$e][1].'"><i class="fas '.$estados[$e][2].'"></i> '.$estados[$e][0].': <b>'.$cuenta[$e].'</b></span>';
            }
            echo '</div><div class="lista-estudiantes">';
            foreach($registrada as $i=>$r){
              echo '<div class="fila-estudiante">
                <span class="numero">'.($i+1).'</span>
                <span class="avatar">'.htmlspecialchars(mb_substr($r["estudiante"],0,1)).'</span>
                <span class="datos"><b>'.htmlspecialchars($r["estudiante"]).'</b><small>'.htmlspecialchars($r["cedula"]).'</small></span>
                <span class="estado-final '.$estados[$r["asistencia"]][1].'"><i class="fas '.$estados[$r["asistencia"]][2].'"></i> '.$estados[$r["asistencia"]][0].'</span>
              </div>';
            }
            echo '</div>';
          }

          /*REGISTRO DE ASISTENCIA*/
          if(!empty($resultado["estudiantes"])){
          ?>
          <form method="POST" id="formAsistencia">
            <?php echo Seguridad::campoToken(); ?>
            <input type="hidden" name="idGdoc" value="<?php echo $resultado["idClase"]; ?>">

            <div class="barra-herramientas">
              <div class="contadores">
                <span class="contador presente"><i class="fas fa-check"></i> Presentes: <b id="cuentaP">0</b></span>
                <span class="contador falta"><i class="fas fa-times"></i> Faltas: <b id="cuentaFI">0</b></span>
                <span class="contador fuga"><i class="fas fa-running"></i> Fugas: <b id="cuentaFG">0</b></span>
              </div>
              <div class="d-flex">
                <input type="search" class="form-control form-control-sm mr-2" id="buscarEstudiante" placeholder="Buscar estudiante...">
                <button type="button" class="btn btn-sm btn-outline-success text-nowrap" id="todosPresentes"><i class="fas fa-check-double"></i> Todos presentes</button>
              </div>
            </div>

            <div class="lista-estudiantes">
            <?php
            foreach($resultado["estudiantes"] as $i=>$es){
              echo '<div class="fila-estudiante" data-nombre="'.htmlspecialchars(mb_strtolower($es["estudiante"]." ".$es["cedula"])).'">
                <input type="hidden" name="idEstudiante[]" value="'.$es["id"].'">
                <span class="numero">'.($i+1).'</span>
                <span class="avatar">'.htmlspecialchars(mb_substr($es["estudiante"],0,1)).'</span>
                <span class="datos"><b>'.htmlspecialchars($es["estudiante"]).'</b><small>'.htmlspecialchars($es["cedula"]).'</small></span>
                <div class="selector-asistencia" role="radiogroup" aria-label="Asistencia de '.htmlspecialchars($es["estudiante"]).'">';
              foreach(array("P","FI","FG") as $e){
                echo '<label class="opcion '.$estados[$e][1].'">
                  <input type="radio" name="nuevaAsistencia['.$i.']" value="'.$e.'"'.($e=="P" ? ' checked' : '').'>
                  <span><i class="fas '.$estados[$e][2].'"></i> '.$estados[$e][0].'</span>
                </label>';
              }
              echo '</div></div>';
            }
            ?>
            </div>

            <div class="barra-guardar">
              <span class="text-muted d-none d-sm-inline"><i class="fas fa-info-circle"></i> Todos inician como presentes; marque solo las faltas y fugas.</span>
              <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> Guardar asistencia</button>
            </div>
          </form>
          <?php } ?>
        </div>
      </div>
      <?php } ?>

    </div>
  </section>
</div>
