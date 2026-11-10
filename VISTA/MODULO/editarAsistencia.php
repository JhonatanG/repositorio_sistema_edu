<?php Seguridad::recordarFiltros("editarAsistencia", array("idDocenteA","idGrado","idMateria","fecha")); ?>
<?php
 if($_SESSION["rol"]=="Docente"
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
                    <h1><i class="fas fa-calendar-week"></i> Asistencia</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Asistencia</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <?php $filtroFecha = true; include __DIR__."/filtroClase.php"; ?>
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
        

                    <table class="table table-bordered table-striped dt-responsive" id="tablaboton">
                        <thead>
                            <tr>
                                <th style="width: 8px;">#</th>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>Asistencia</th>
                                <th class="no-exportar">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                $listaEstudiante = ControladorAsistencia::ctrListaEstuFec();
                $cont=0;
    if(isset($listaEstudiante)){
        foreach($listaEstudiante as $es){

            $cont=$cont+1;
                 echo '<tr>
                         <td>'.$cont.'</td>
                        <td> '.$es["cedula"].'</td>
                      <input type="hidden" class="form-control-plaintext" readonly name="idEstudiante[]" value="'.$es["id"].'">
                      <input type="hidden" class="form-control-plaintext" readonly name="idGdoc" value="'.$es["gdoc"].'">
            
                      <td>'.$es["estudiante"].'</td>
                        <td>'.$es["fecha"].' </td>';
            if($es["asistencia"]=="P"){
                echo ' <td><span class="badge bg-success">Presente</span> 
                </td>' ;
            }else if($es["asistencia"]=="FG"){
                echo ' <td><span class="badge bg-danger">Fuga</span> 
                </td>' ;
            }else if($es["asistencia"]=="FI"){
                echo ' <td><span class="badge bg-warning">Falta Injustificada</span> 
                </td>' ;
            }else if($es["asistencia"]=="FJ"){
                echo ' <td><span class="badge bg-primary">Falta Justificada</span> 
                </td>' ;
            }
                        
                    echo'   
                      <td>
                      <div class="btn-group">
                      <button class="btn btn-warning btnEditarAsistencia" idAsist="'.$es["idAsis"].'" data-toggle="modal"  data-target="#modalEditarAsistencia">
                      <i class="fas fa-edit"></i></butoon>
                      </div>
                      </td>
                      </tr>
                      ';
                 
        }
    }

?>
                        </tbody>

                    </table>
      
            </div>

        </div>


    </section>
</div>
<!-- 
/*=============================================
MODAL EDITAR AISTENCIA
=============================================*/ -->
<div class="modal fade" id="modalEditarAsistencia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Asistencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- ENTRADA ASISTENCIA -->
     
                    <div class="form-group">
                    <input type="hidden" name="idAsistencia" id="idAsistencia">
                        <div class="input-group">
                        <select name="editarAsistencia" class="form-control input-lg" required>
                        <option id="editarAsistencia"></option>
                        <option value="P">Presente</option>
                        <option value="FI">Falta Injustificada</option>
                        <option value="FG">Fuga</option>
                      </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
                    $editarAsis = new ControladorAsistencia();
                    $editarAsis->ctrEditarAsistencia();

                    ?>
                </div>
            </form>
        </div>
    </div>
</div>
