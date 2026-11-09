<?php
 if($_SESSION["rol"]=="Docente"
 || $_SESSION["rol"]=="Estudiante"|| $_SESSION["rol"]=="Administrador" ){
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
                    <h1><i class="fas fa-notes-medical"></i>Reporte Notas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Reporte Notas</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <!-- =============================================
FORM BUSCAR DOCENTE
=============================================*/ -->
                    <form method="POST">
                        <div class="row">

                            <div class="col-md-2">


                            </div>
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>Estudiante:</label>
                                    <select class="form-control select2bs4" style="width: 100%;" name="idEstudiante"
                                        id="idEstudiante" data-placeholder="Seleccionar">
                                        <option value="">Seleccionar</option>
                                        <?php
                                $id=$_SESSION["id"];
                               $estudiantes=ControladorAsistencia::ctrEstuRepre($id);
                             
                               foreach($estudiantes as $key=>$value){
                                if(isset($_POST["idEstudiante"]) && $_POST["idEstudiante"] == $value["id"]){

                                echo'<option value="'.$value["id"].'" selected>'.$value["cedula"].': '.$value["nombre"].'</option>';
                                $idEstud=$value["id"];
                                }else{
                                   
                                echo'<option value="'.$value["id"].'">'.$value["cedula"].': '.$value["nombre"].'</option>';
                                  
                            }   
                          
                               
                              }
                            
                                          ?>

                                    </select>

                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Buscar Estudiante:</label>
                                    <button class="form-control btn btn-info">Buscar <i
                                            class="fas fa-search"></i></button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
<?php
/*TABLA CON LAS COLUMNAS SEGÚN EL TIPO DE EVALUACIÓN DEL AÑO LECTIVO ACTIVO*/
if(isset($idEstud)){
  $listaEstudiante = ControladorNotas::ctrReportNotEstud($idEstud);
  $tipo = !empty($listaEstudiante) ? $listaEstudiante[0]["tipo"] : ModeloNotas::mdlTipoPeriodoActivo();
  Calificaciones::tablaReporte($listaEstudiante, $tipo, "materia", "Materia");
}
?>

            </div>

        </div>


    </section>
</div>