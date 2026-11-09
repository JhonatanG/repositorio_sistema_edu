<?php
 if($_SESSION["rol"]=="Secretaría" || $_SESSION["rol"]=="Docente"
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
                    <h1> <i class="fas fa-building nav-icon"></i>
                        Cupos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo SERVERURL;  ?>inicio/">Inicio</a></li>
                        <li class="breadcrumb-item active">Cupos</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarParalelo">
                    <i class="fas fa-plus-square"></i> Agregar Cupos
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive" id="tablas">
                    <thead>
                        <tr>
                            <th style="width: 8px;">#</th>
                            <th>Grados</th>
                            <th>Paralelos</th>
                            <th>Cupos</th>
                            <th>Estado</th>
                            <th>Periodo</th>
                            <th>Observación</th>
                            <th class="no-exportar">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $item="";
                $valor="";
                $gparalelo =ControladorGparalelos::ctrMostrarGparalelos($item,$valor);
                $cont=0;
        foreach($gparalelo as $a){
            $cont=$cont+1;
                 echo '<tr>
                         <td>'.$cont.'</td>
                        <td>'.$a["grado"].'</td>
                        <td>'.$a["paralelo"].'</td>';
                        if($a["cupos"]>=20){
                         echo ' <td> <button class="btn btn-success">'.$a["cupos"].'</button>   </td>';
                        }else if($a["cupos"]<20 && $a["cupos"]>=10){
                            echo ' <td> <button class="btn btn-warning">'.$a["cupos"].'</button>   </td>';
                        }else if ($a["cupos"]<10 ){
                            echo ' <td> <button class="btn btn-danger">'.$a["cupos"].'</button>   </td>';
                        }
                        
                        if ($a["estado"]!=0){
                            echo' <td><button class="btn btn-success btn-xs  btnActivarGparalelo" idGparalelo="'.$a["id"].'"
                            estadoGparalelo="0">Activo</button></td>';
                            }else{
                              echo' <td><button class="btn btn-danger btn-xs  btnActivarGparalelo" idGparalelo="'.$a["id"].'"
                            estadoGparalelo="1">Inactivo</button></td>';
                            }
    
                
                       echo'<td>'.$a["periodo"].' </td> 
                       <td>'.$a["observacion"].' </td>
                        <td>
                        <div class="btn-group">
                        <button class="btn btn-warning btnEditarGparalelo" idGparalelo="'.$a["id"].'" data-toggle="modal"  data-target="#modalEditarGparalelo">
                          <i class="fas fa-edit"></i></butoon>
                          <button class="btn btn-danger btnEliminarGparalelo" idGparalelo="'.$a["id"].'"><i class="fas fa-trash"></i></button>
                        </div>
                        </td>
                        </tr>';
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
MODAL AGREGAR Paralelo
=============================================*/ -->
<div class="modal fade" id="modalAgregarParalelo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Agregar Grado-Paralelos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- ENTRADA Paralelos -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <select name="idGrado" class="form-control input-lg" required>
                                <option value="">Seleccionar Grado</option>
                                <?php
                               
                        $grados=ControladorGrados::ctrListarGrados();
                foreach($grados as $key=>$value){
                            
                  echo'
                            <option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                }
                            ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <span class=""><strong> Seleccionar Paralelos: </strong></span>
                        <div class="input-group">
                            <?php
                      $paralelos=ControladorParalelos::ctrListarParalelos();
                    foreach ($paralelos as $key => $value) {
                        echo'<div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="idParalelo[]" value="'.$value["id"].'">
                        <label class="form-check-label" for="paralelos">'.$value["nombre"].'</label>
                    </div>';
                     }                     
                        ?>
                        </div>
                    </div>
                    <!-- ENTRADA PARA CUPOS -->

                    <div class="form-group">

                        <div class="input-group">

                            <span class="input-group-text"><i class="fa fa-check"></i></span>

                            <input type="number" class="form-control input-lg" name="nuevoCupos" min="0"
                                placeholder="Ingrese el número de cupo general para cada paralelo." required>

                        </div>

                    </div>
                            <!-- ENTRADA PARA ANIO LECTIVO-->
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                    <select name="nuevoPeriodo" id="" class="form-control input-lg" required>
                                        <option value="">Seleccionar Año Lectivo</option>
                                        <?php

$anio=ControladorAnio::ctrListarAnio();

foreach($anio as $key=>$value){
   
echo'<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
     
}
          ?>
                                    </select>
                                </div>
                            </div>
                    <!-- ENTRADA PARA OBSERVACION-->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                            <textarea class="form-control input-lg" name="nuevaObservacion" id="nuevaObservacion"
                                placeholder="Ingrese observación general para todas los paralelos seleccionados (Opcional)"></textarea>
                        </div>
                    </div>
                    <input type="hidden" value="1" name="nuevoEstado">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
                    $crearGparalelo= new ControladorGparalelos();
                    $crearGparalelo->ctrCrearGparalelos();
                    ?>


                </div>
            </form>
        </div>
    </div>
</div>
<!-- 
/*=============================================
MODAL EDITAR Paralelo 
=============================================*/ -->
<div class="modal fade" id="modalEditarGparalelo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" method="post">
                <div class="modal-header" style="background: #008080;">
                    <h5 class="modal-title">Editar Paralelos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="editarGparalelo" id="editarGparalelo">

                    <!-- ENTRADA PARA CUPOS -->

                    <div class="form-group">

                        <div class="input-group">

                            <span class="input-group-text"><i class="fa fa-check"></i></span>

                            <input type="number" class="form-control input-lg" name="editarCupos" min="0"
                                placeholder="Ingrese el número de cupo para cada paralelo." id="editarCupos" required>

                        </div>

                    </div>
             
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-archive"></i></span>
                            <textarea class="form-control input-lg" name="editarObservacion" id="editarObservacion"
                                placeholder="Ingrese observación (Opcional)"></textarea>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
$editar = new ControladorGparalelos();
$editar->ctrEditarGparalelo();
?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$eliminar= new ControladorGparalelos;
$eliminar->ctrEliminarGparalelos();
?>