<?php Seguridad::recordarFiltros("reporteNotas", array("idGrado","idMateria")); ?>
<?php
 if($_SESSION["rol"]=="Secretaría" || $_SESSION["rol"]=="Administrador"
 || $_SESSION["rol"]=="Representante"|| $_SESSION["rol"]=="Estudiante" ){
  echo  '<script> 
  window.location="'.SERVERURL.'inicio/";
  </script>
   ';
   return;
 }



?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-notes-medical"></i> Calificaciones</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Calificaciones</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <form method="post">
                    <div class="row">

                        <div class="col-md-3">

                            <div class="form-group">
                                <input type="hidden" value="<?php echo $_SESSION["id"]?>" id="idDocenteG"
                                    name="idDocenteG">
                                <label>Grado:</label>
                                <select required class="form-control select2bs4" style="width: 100%;" name="idGrado" id="idGrado"
                                    data-placeholder="Seleccionar">
                                    <option value="">Seleccionar</option>
                                    <?php
 $id=$_SESSION["id"];
 $item="id_docente";
$grados=ControladorAsistencia::ctrListaGradosDocente($item,$id);

foreach($grados as $key=>$value){

    if(isset($_POST["idGrado"]) && $_POST["idGrado"] == $value["id"]){

        echo'<option class="btnGradoAsistencia" value="'.$value["id"].'" selected >'.$value["grado"].'</option>';
            }else{
        echo'<option class="btnGradoAsistencia" value="'.$value["id"].'"  >'.$value["grado"].'</option>';
        
            }

}
?>

                                </select>




                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Materia:</label>
                                <select class="form-control select2bs4 datos" name="idMateria" id="idMateria"
                                    style="width: 100%;">


<?php
/*AL VOLVER A CARGAR LA PÁGINA SE MUESTRAN LAS MATERIAS DEL GRADO ELEGIDO*/
if(!empty($_POST["idGrado"])){
  $docMat = Seguridad::rol()=="Docente" ? $_SESSION["id"] : (isset($id) ? $id : 0);
  foreach(ControladorAsistencia::ctrListaMDoc("id_docente",$docMat,"id_grado_docente",$_POST["idGrado"]) as $mat){
    echo '<option value="'.$mat["id"].'"'.((isset($_POST["idMateria"]) && $_POST["idMateria"]==$mat["id"]) ? ' selected' : '').'>'.htmlspecialchars($mat["materia"]).'</option>';
  }
}
?>
                                </select>
                            </div>

                        </div>
                        <!-- <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha:</label>
                                <input type="text" class="form-control" name="fecha" value="<?php echo date("Y-m-d") ?>" readonly>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Calificaciones:</label>
                                <button class="form-control btn btn-primary" >Buscar <i
                                        class="fas fa-search"></i></button>
                            </div>
                        </div>

                    </div>

                </form>
            </div>





            <div class="card-body">
                <?php
        
  $lista= ControladorAsistencia::ctrListaCabecera();
  if(isset($lista)){
  foreach($lista as $a){
 echo' <div class="card bg-info text-dark"> 
 <div class="card-body">
  <div class="row">   <div class="col-md-3">
    <div class="form-group">
        <label>Docente:</label>
        <input type="text" class="form-control-plaintext" readonly value="'.$a["docente"].'">
    </div>
</div>';
echo'   <div class="col-md-3">
<div class="form-group">
    <label>Grado:</label>
    <input type="text" class="form-control-plaintext" readonly value="'.$a["grado"].'">
</div>
</div>';
echo'   <div class="col-md-3">
<div class="form-group">
    <label>Materia:</label>
    <input type="text" class="form-control-plaintext" readonly value="'.$a["materia"].'">
</div>
</div> 

</div>
</div>
</div>';
  }
  if(empty($lista)){
    echo' <div class="card bg-danger text-dark"> 
    <div class="card-body">
    <h3 align="center">¡No existen estudiantes!</h3>
    </div>
</div>';
  }
  }
?>
                <form method="POST">
<?php
        $notas = ControladorNotas::ctrListaNotas();
    

 if(!empty($lista)){
     echo'
                    <div class="row">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">

                        </div>

                    </div>';} ?>
<?php
/*TABLA CON LAS COLUMNAS SEGÚN EL TIPO DE EVALUACIÓN DEL AÑO LECTIVO*/
if(!empty($notas)){
  Calificaciones::tablaReporte($notas, ControladorNotas::ctrTipoGrado(), "estudiante", "Estudiante");
}
?>
     
                    </form>
            </div>

        </div>


    </section>
</div>
