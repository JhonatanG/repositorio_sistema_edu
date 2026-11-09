<?php
/*=============================================
HISTORIAL ACADÉMICO DEL ESTUDIANTE (ruta historial/{idEstudiante}/)
=============================================*/
$idEstudiante = isset($rutas[1]) ? (int)$rutas[1] : 0;
$estudiante = $idEstudiante ? ModeloAlumnos::mdlMostrarAlumnos("edu_tab_estudiantes", "id", $idEstudiante) : null;
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-history"></i> Historial académico</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>inicio/">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>listaMatricula/">Matrículas</a></li>
            <li class="breadcrumb-item active">Historial</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <?php if(!$estudiante){ ?>
        <div class="alert alert-warning">Estudiante no encontrado. Abra el historial desde la lista de matrículas o de estudiantes.</div>
      <?php }else{
        $historial = ControladorHistorial::historial($idEstudiante);
        $aprobados = count(array_filter($historial, function($h){ return $h["estado"]=="APROBADO"; }));
        $reprobados = count(array_filter($historial, function($h){ return $h["estado"]=="REPROBADO"; }));
      ?>
      <div class="card">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <span class="avatar-grande"><?php echo htmlspecialchars(mb_substr($estudiante["apellidos"],0,1)); ?></span>
            <div>
              <h4 class="mb-0"><?php echo htmlspecialchars($estudiante["apellidos"]." ".$estudiante["nombres"]); ?></h4>
              <span class="text-muted">Cédula <?php echo htmlspecialchars($estudiante["cedula"]); ?> · Usuario <?php echo htmlspecialchars($estudiante["usuario"]); ?></span>
            </div>
          </div>
          <div class="contadores mt-2 mt-md-0">
            <span class="contador justificada">Años cursados: <b><?php echo count($historial); ?></b></span>
            <span class="contador presente">Aprobados: <b><?php echo $aprobados; ?></b></span>
            <span class="contador falta">Reprobados: <b><?php echo $reprobados; ?></b></span>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><h3 class="card-title">Años lectivos</h3>
          <a href="<?php echo SERVERURL; ?>matricula/" class="btn btn-sm btn-primary float-right"><i class="fas fa-file-signature"></i> Nueva matrícula</a>
        </div>
        <div class="card-body">
          <?php echo ControladorHistorial::htmlHistorial($historial, true); ?>
          <small class="text-muted d-block mt-3">Un año se aprueba cuando todas las asignaturas tienen nota final de <?php echo ControladorHistorial::NOTA_MINIMA; ?> o más.</small>
        </div>
      </div>
      <?php } ?>
    </div>
  </section>
</div>
