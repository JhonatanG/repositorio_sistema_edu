<!--
/*=============================================
MODAL FICHA (DOCENTE, ESTUDIANTE O REPRESENTANTE)
El contenido lo arma plantilla.js con los datos de ajax/ficha.ajax.php
=============================================*/ -->
<div class="modal fade" id="modalFicha" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content ficha">
      <div class="ficha-cabecera">
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <img src="<?php echo SERVERURL; ?>VISTA/img/anonymous.png" class="ficha-foto" id="fichaFoto" alt="Foto">
        <div>
          <span class="badge badge-light" id="fichaTipo"></span>
          <h3 class="mb-0" id="fichaNombre"></h3>
          <div class="ficha-sub" id="fichaSub"></div>
        </div>
      </div>
      <div class="modal-body" id="fichaCuerpo">
        <div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" id="imprimirFicha"><i class="fas fa-print"></i> Imprimir</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
