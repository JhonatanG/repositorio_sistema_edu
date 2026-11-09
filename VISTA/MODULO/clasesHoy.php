<?php
/*=============================================
TARJETAS DE LAS CLASES DE HOY CON BOTÓN PARA TOMAR LISTA
Necesita $clasesHoy (ModeloHorario::mdlClasesDelDia)
Muestra si la lista de hoy ya fue tomada y resalta la clase abierta.
=============================================*/
echo '<div class="row">';
foreach($clasesHoy as $c){
  $tomada = !empty(ModeloAsistencia::mdlAsistenciaExistente(array("fecha"=>date("Y-m-d"), "idGdocente"=>$c["id_clase"])));
  $abierta = isset($_POST["idGrado"], $_POST["idMateria"]) && $_POST["idGrado"]==$c["id_grado_docente"] && $_POST["idMateria"]==$c["id_materia"];
  echo '<div class="col-xl-3 col-lg-4 col-sm-6">
    <form method="post" action="'.SERVERURL.'asistencia/" class="clase-hoy'.($tomada ? ' tomada' : '').($abierta ? ' abierta' : '').'">
      <input type="hidden" name="idGrado" value="'.$c["id_grado_docente"].'">
      <input type="hidden" name="idMateria" value="'.$c["id_materia"].'">
      <div class="d-flex justify-content-between align-items-center">
        <span class="horario-hora"><i class="far fa-clock"></i> '.$c["inicio"].' - '.$c["fin"].'</span>
        '.($tomada ? '<span class="badge badge-success"><i class="fas fa-check"></i> Tomada</span>' : '<span class="badge badge-warning">Pendiente</span>').'
      </div>
      <b>'.htmlspecialchars($c["materia"]).'</b>
      <div class="mb-2 text-muted">'.htmlspecialchars($c["curso"]).'</div>
      <button class="btn btn-sm btn-block '.($tomada ? 'btn-outline-success' : 'btn-primary').'">
        <i class="fas '.($tomada ? 'fa-eye' : 'fa-clipboard-check').'"></i> '.($tomada ? 'Ver lista' : 'Tomar lista').'
      </button>
    </form>
  </div>';
}
echo '</div>';
