<?php
/*=============================================
FILTRO COMÚN DE CLASE: DOCENTE (SOLO ADMINISTRADOR Y SECRETARÍA), CURSO,
ASIGNATURA, FECHA OPCIONAL Y EL BOTÓN "APLICAR FILTROS" SIEMPRE AL FINAL.
Antes de incluirlo: $filtroFecha = true para pedir la fecha.
=============================================*/
$esDocenteFiltro = Seguridad::rol()=="Docente";
$id = $esDocenteFiltro ? $_SESSION["id"] : (isset($_POST["idDocenteA"]) ? (int)$_POST["idDocenteA"] : 0);
$conFecha = !empty($filtroFecha);

/*ANCHO DE CADA CAMPO SEGÚN CUÁNTOS HAY, PARA QUE EL BOTÓN QUEDE AL FINAL DE LA FILA*/
if(!$esDocenteFiltro && $conFecha){
  $anchos = array("docente"=>"col-lg-3", "curso"=>"col-lg-2", "materia"=>"col-lg-3", "fecha"=>"col-lg-2", "boton"=>"col-lg-2");
}else if(!$esDocenteFiltro){
  $anchos = array("docente"=>"col-lg-3", "curso"=>"col-lg-3", "materia"=>"col-lg-3", "boton"=>"col-lg-3");
}else if($conFecha){
  $anchos = array("curso"=>"col-lg-3", "materia"=>"col-lg-4", "fecha"=>"col-lg-2", "boton"=>"col-lg-3");
}else{
  $anchos = array("curso"=>"col-lg-4", "materia"=>"col-lg-5", "boton"=>"col-lg-3");
}
?>
<form method="post" class="row align-items-end filtros">
  <input type="hidden" name="idDocenteG" id="idDocenteG" value="<?php echo (int)$id; ?>">

  <?php if(!$esDocenteFiltro){ ?>
  <div class="<?php echo $anchos["docente"]; ?> col-md-6 form-group">
    <label>Docente</label>
    <select class="form-control select2bs4" style="width: 100%;" name="idDocenteA" id="idDocenteA" required>
      <option value="">Seleccionar</option>
      <?php
      foreach(ControladorDocentes::ctrListarDocentes() as $value){
        echo '<option value="'.$value["id"].'"'.($id==$value["id"] ? ' selected' : '').'>'.htmlspecialchars($value["cedula"].": ".$value["nombre"]).'</option>';
      }
      ?>
    </select>
  </div>
  <?php } ?>

  <div class="<?php echo $anchos["curso"]; ?> col-md-6 form-group">
    <label>Curso</label>
    <select class="form-control select2bs4" style="width: 100%;" name="idGrado" id="idGrado" required>
      <option value="">Seleccionar</option>
      <?php
      if($id){
        foreach(ControladorAsistencia::ctrListaGradosDocente("id_docente", $id) as $value){
          echo '<option value="'.$value["id"].'"'.((isset($_POST["idGrado"]) && $_POST["idGrado"]==$value["id"]) ? ' selected' : '').'>'.htmlspecialchars($value["grado"]).'</option>';
        }
      }
      ?>
    </select>
  </div>

  <div class="<?php echo $anchos["materia"]; ?> col-md-6 form-group">
    <label>Asignatura</label>
    <select class="form-control select2bs4 datos" style="width: 100%;" name="idMateria" id="idMateria" required>
      <?php
      /*AL VOLVER A CARGAR LA PÁGINA SE MUESTRAN LAS MATERIAS DEL CURSO ELEGIDO*/
      if($id && !empty($_POST["idGrado"])){
        foreach(ControladorAsistencia::ctrListaMDoc("id_docente", $id, "id_grado_docente", $_POST["idGrado"]) as $mat){
          echo '<option value="'.$mat["id"].'"'.((isset($_POST["idMateria"]) && $_POST["idMateria"]==$mat["id"]) ? ' selected' : '').'>'.htmlspecialchars($mat["materia"]).'</option>';
        }
      }
      ?>
    </select>
  </div>

  <?php if($conFecha){ ?>
  <div class="<?php echo $anchos["fecha"]; ?> col-md-6 form-group">
    <label>Fecha</label>
    <input type="date" class="form-control" name="fecha" max="<?php echo date("Y-m-d"); ?>"
      value="<?php echo isset($_POST["fecha"]) ? htmlspecialchars($_POST["fecha"]) : ""; ?>" required>
  </div>
  <?php } ?>

  <div class="<?php echo $anchos["boton"]; ?> col-md-6 form-group">
    <button class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Aplicar filtros</button>
  </div>
</form>
