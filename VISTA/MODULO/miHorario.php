<?php
/*=============================================
HORARIO DEL DOCENTE QUE INICIÓ SESIÓN
=============================================*/
$hoy = ControladorHorario::diaHoy();
$clasesHoy = ModeloHorario::mdlClasesDelDia($_SESSION["id"], $hoy);
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-clock"></i> Mi horario</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Mi horario</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header"><h3 class="card-title">Clases de hoy, <?php echo strtolower(ControladorHorario::nombreDia($hoy)); ?></h3></div>
        <div class="card-body">
          <?php
          if(empty($clasesHoy)){
            echo '<p class="text-muted mb-0">Hoy no tiene clases en su horario.</p>';
          }else{
            include __DIR__."/clasesHoy.php";
          }
          ?>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><h3 class="card-title">Semana</h3></div>
        <div class="card-body">
          <?php ControladorHorario::semana(ModeloHorario::mdlHorarioDocente($_SESSION["id"])); ?>
        </div>
      </div>
    </div>
  </section>
</div>
