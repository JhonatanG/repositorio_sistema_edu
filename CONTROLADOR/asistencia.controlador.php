<?php

class ControladorAsistencia{
     /*=============================================
LISTA GRADOS DOCENTES
	=============================================*/
  static public function ctrListaGradosDocente($item,$valor){
    //  $item="id_docente";
     $respuesta=ModeloAsistencia::mdlListaGradosDocente($item,$valor);
    // var_dump($respuesta);
     return $respuesta;
  }
 /* static public function ctrListaMateriasDocente($item,$item2,$valor,$valor2){
      $respuesta=ModeloAsistencia::mdlListaMateriasDocente($item,$item2,$valor,$valor2);
   
      return $respuesta;     
  }*/
  static public function ctrListaMDoc($item,$valor,$item2,$valor2){
     $respuesta=ModeloAsistencia::mdlListaMDocente($item,$valor,$item2,$valor2);
    //var_dump($respuesta);
     return $respuesta;
  }
      /*=============================================
LISTAR CABECERA
	=============================================*/
  static public function ctrListaCabecera(){
   if(isset($_POST["idGrado"]) && isset($_POST["idMateria"])){
      $datos=array(
        "idGParalelo"=>$_POST["idGrado"],
        "idDocente"=>$_POST["idDocenteG"],
        "idMateria"=>$_POST["idMateria"]
      );
      $respuesta = ModeloAsistencia::mdlCabeceraAsistencia($datos);
      //var_dump($respuesta);
       return $respuesta;
      
    }
  
 }
       /*=============================================
LISTAR ESTUDIANTE
	=============================================*/
   static public function ctrListaEstudiante(){
      if(!isset($_POST["idGrado"]) || !isset($_POST["idMateria"]) || $_POST["idMateria"]==""){
         return null;
      }
      $datos=array(
        "idGParalelo"=>$_POST["idGrado"],
        "idDocente"=>$_POST["idDocenteG"],
        "idMateria"=>$_POST["idMateria"]
      );
      $lista = ModeloAsistencia::mdlListaEstudiantes($datos);
      if(empty($lista)){
         return array("estudiantes"=>array(), "aviso"=>"No hay estudiantes matriculados en esta clase.", "tipo"=>"warning", "idClase"=>0);
      }
      $idClase = $lista[0]["gdoc"];
      /*SOLO SE TOMA LISTA EL DÍA QUE LA CLASE ESTÁ EN EL HORARIO*/
      list($permitido, $mensaje) = ControladorHorario::puedeTomarAsistencia($idClase);
      if(!$permitido){
         return array("estudiantes"=>array(), "aviso"=>$mensaje, "tipo"=>"warning", "idClase"=>$idClase);
      }
      $existente = ModeloAsistencia::mdlAsistenciaExistente(array("fecha"=>date("Y-m-d"), "idGdocente"=>$idClase));
      if(!empty($existente)){
         return array("estudiantes"=>array(), "aviso"=>"La asistencia de hoy de esta clase ya fue registrada.", "tipo"=>"success", "idClase"=>$idClase);
      }
      return array("estudiantes"=>$lista, "aviso"=>"", "tipo"=>"", "idClase"=>$idClase);
    }
    /*=============================================
GUARDAR INFORMACION
=============================================*/
static public function ctrCrearAsistencia(){
//var_dump("asfsafasfasfasfdwerSSFAS");
   if(isset($_POST["idGdoc"])){
     if(!Seguridad::esClaseDelDocente($_POST["idGdoc"])){
       echo Seguridad::alerta("error", "¡No autorizado!", "Solo puede registrar datos de sus propias clases.", "asistencia/");
       return;
     }
     /*EL SERVIDOR VUELVE A VERIFICAR: HORARIO DE HOY, QUE NO ESTÉ YA REGISTRADA Y VALORES PERMITIDOS*/
     list($permitido, $mensaje) = ControladorHorario::puedeTomarAsistencia($_POST["idGdoc"]);
     if(!$permitido){
       echo Seguridad::alerta("warning", "¡Hoy no puede tomar lista!", $mensaje, "asistencia/");
       return;
     }
     if(!empty(ModeloAsistencia::mdlAsistenciaExistente(array("fecha"=>date("Y-m-d"), "idGdocente"=>$_POST["idGdoc"])))){
       echo Seguridad::alerta("info", "¡Ya registrada!", "La asistencia de hoy de esta clase ya fue registrada.", "asistencia/");
       return;
     }
     foreach((array)(isset($_POST["nuevaAsistencia"]) ? $_POST["nuevaAsistencia"] : array()) as $valorAsistencia){
       if(!in_array($valorAsistencia, array("P","FI","FG"))){ /*EL DOCENTE NO JUSTIFICA: ESO LO HACE SECRETARÍA*/
         echo Seguridad::alerta("error", "¡Datos no válidos!", "Valor de asistencia no permitido.", "asistencia/");
         return;
       }
     }
       if(isset($_POST["idEstudiante"])){
       $tabla="edu_tab_asistencias";  
       $idGdoc=$_POST["idGdoc"];
       $idEstudiante=$_POST["idEstudiante"];
       $fecha=date("Y-m-d");
       $asistencia=$_POST["nuevaAsistencia"];
    /*  $datos=array("idGdocente"=>$_POST["idGdoc"] ,"idEstudiante"=>$_POST["idEstudiante"],
      "fecha"=>$_POST["nuevafecha"],"asistencia"=>$_POST["nuevaAsistencia"]);
       
       $respuesta=ModeloAsistencia::mdlCrearAsistencia($tabla,$datos);*/

         for ($i = 0; $i<count($idEstudiante);$i++) {
           $estu = $idEstudiante[$i];
           $asis = $asistencia[$i];
           $datos=array("idGdocente" => $_POST["idGdoc"]
           ,"idEstudiante"=>$estu,"fecha"=>date("Y-m-d"),"asistencia"=>$asis);
          // var_dump($datos);
           $respuesta=ModeloAsistencia::mdlCrearAsistencia($tabla,$datos);
         //  var_dump($respuesta);
           
               
             }
          
       
       if($respuesta=="ok"){
           echo'<script>
           Swal.fire({
             icon: "success",
             title: "¡Se ha guardado correctamente!",
             showConfirmButton: true,
             confirmButtonText: "Cerrar",
             closeOnConfirm:false
           }).then(function(result){
               if (result.value) {
               window.location = "'.SERVERURL.'asistencia/";
               }
           });
           
           </script>';
       }else{
         echo'<script>
         Swal.fire({
           icon: "error",
           title: "¡No se pudo guardar!",
           showConfirmButton: true,
           confirmButtonText: "Cerrar",
           closeOnConfirm:false
         }).then(function(result){
             if (result.value) {
             window.location = "'.SERVERURL.'asistencia/";
             }
         });
         
         </script>';
       }

   }else{
       echo'<script>
       Swal.fire({
         icon: "error",
         title: "¡Debe seleccionar los campos!",
         showConfirmButton: true,
         confirmButtonText: "Cerrar",
         closeOnConfirm:false
       }).then(function(result){
           if (result.value) {
           window.location = "'.SERVERURL.'asistencia/";
           }
       });
       
       </script>';
   }
   }

}
/*=============================================
DEVUELVE EL ID DEL DOCENTE
=============================================*/
static public function ctrIdDocente(){
  if(isset($_POST["idDocenteA"])){
    $tabla="edu_tab_docentes";
    $datos=array("idDocente"=>$_POST["idDocenteA"]);
    $respuesta=ModeloAsistencia::mdlIdDocente($tabla,$datos);
    return $respuesta;
  }

}
/*=============================================
LISTA ESTUDIANTES POR FECHA
=============================================*/
static public function ctrListaEstuFec(){
  if(isset($_POST["idGrado"]) && isset($_POST["idMateria"])){
      $fecha=$_POST["fecha"];
      $fechaFor = date("Y-m-d",strtotime($fecha));
     // var_dump($fechaFor);
     $datos=array(
       "idGParalelo"=>$_POST["idGrado"],
       "idDocente"=>$_POST["idDocenteG"],
       "idMateria"=>$_POST["idMateria"],
       "fecha"=> $fechaFor
     );
   //  var_dump($datos);
   $respuestaLista = ModeloAsistencia::mdlListaEstuFec($datos);
   if(empty($respuestaLista)){
    echo'<script>
    Swal.fire({
      icon: "error",
      title: "¡El docente aún no registra la asistencia!",
      showConfirmButton: true,
      confirmButtonText: "Cerrar",
      closeOnConfirm:false
    }).then(function(result){
        if (result.value) {
        window.location = "'.SERVERURL.'editarAsistencia/";
        }
    });
    
    </script>';

   }else{

    return $respuestaLista;
   }  
   }
 
}
/*=============================================
MOSTRAR TABLA ASISTENCIA
=============================================*/
static public function ctrMostrarAsistencia($item,$valor){
  $tabla="edu_tab_asistencias";
  $respuesta=ModeloAsistencia::mdlMostrarAsistencia($tabla,$item,$valor);
  return $respuesta;
}
/*=============================================
EDITAR
=============================================*/
static public function ctrEditarAsistencia(){
  if(isset($_POST["editarAsistencia"])){
    /*DESDE AQUÍ NO SE JUSTIFICA: LAS JUSTIFICACIONES LAS HACE SECRETARÍA CON SU MOTIVO*/
    if(!in_array($_POST["editarAsistencia"], array("P","FI","FG"))){
      echo Seguridad::alerta("error", "¡Valor no permitido!", "Para justificar una falta use Asistencia → Justificar faltas.", "editarAsistencia/");
      return;
    }
    $tabla="edu_tab_asistencias" ;
    $datos=array("idAsistencia"=>$_POST["idAsistencia"],"asistencia"=>$_POST["editarAsistencia"]);
    $respuesta=ModeloAsistencia::mdlEditarAsistencia($tabla,$datos);
    if($respuesta=="ok"){
      echo'<script>
      Swal.fire({
        icon: "success",
        title: "¡Se ha guardado correctamente!",
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        closeOnConfirm:false
      })
      
      </script>';
    }

  }


}
 /*=============================================
REPORTE ASISTENCIA
	=============================================*/
 static public function ctrReporteAsistencia(){
  if(isset($_POST["idGrado"]) && isset($_POST["idMateria"])){
   $datos=array(
     "idGParalelo"=>$_POST["idGrado"],
     "idDocente"=>$_POST["idDocenteG"],
     "idMateria"=>$_POST["idMateria"]
   );
 //  var_dump($datos);
 $respuesta = ModeloAsistencia::mdlReporteAsistencia($datos);
 return $respuesta;

}

 }
  /*=============================================
REPORTE ASISTENCIA PARA CADA ESTUDIANTE
	=============================================*/
  static public function ctrReportAsisEstud($valor){
   //  var_dump($datos);
   $respuesta = ModeloAsistencia::mdlReportAsisEstud($valor);
   return $respuesta;  
}

        /*=============================================
OBTIENE ESTUDIANTES POR REPRESENTANTE
	=============================================*/

  static public function ctrEstuRepre($valor){
    $respuesta = ModeloAsistencia::mdlEstuRepre($valor);
    return $respuesta;
  }

/*=============================================
JUSTIFICAR FALTAS (SOLO SECRETARÍA)
=============================================*/
static public function ctrJustificar(){
  if(!isset($_POST["idAsistenciaJ"])){
    return;
  }
  if(Seguridad::rol()!="Secretaría" || !Seguridad::tokenValido()){
    echo Seguridad::alerta("error", "¡No autorizado!", "Solo Secretaría puede justificar faltas.", "justificarFaltas/");
    return;
  }
  $motivo = trim(isset($_POST["motivo"]) ? $_POST["motivo"] : "");
  if(strlen($motivo) < 5){
    echo Seguridad::alerta("error", "¡Falta el motivo!", "Escriba el motivo de la justificación (por ejemplo: certificado médico).", "justificarFaltas/");
    return;
  }
  $ids = (array)$_POST["idAsistenciaJ"];
  $ok = 0;
  foreach($ids as $id){
    if(ModeloAsistencia::mdlJustificar((int)$id, $motivo, $_SESSION["id"]) == "ok"){
      $ok++;
    }
  }
  if($ok == count($ids)){
    echo Seguridad::alerta("success", "¡Falta justificada!", $ok == 1 ? "La falta quedó justificada." : "Se justificaron ".$ok." faltas.", "justificarFaltas/");
  }else{
    echo Seguridad::alerta("warning", "¡Revise las faltas!", "Se justificaron ".$ok." de ".count($ids).". Las demás ya estaban justificadas o no existen.", "justificarFaltas/");
  }
}

static public function ctrQuitarJustificacion(){
  if(!isset($_POST["idAsistenciaQ"])){
    return;
  }
  if(Seguridad::rol()!="Secretaría" || !Seguridad::tokenValido()){
    echo Seguridad::alerta("error", "¡No autorizado!", "Solo Secretaría puede modificar justificaciones.", "justificarFaltas/");
    return;
  }
  if(ModeloAsistencia::mdlQuitarJustificacion((int)$_POST["idAsistenciaQ"]) == "ok"){
    echo Seguridad::alerta("success", "¡Justificación quitada!", "La falta volvió a quedar como injustificada.", "justificarFaltas/");
  }else{
    echo Seguridad::alerta("error", "¡No se pudo cambiar!", "La falta no existe o no estaba justificada.", "justificarFaltas/");
  }
}
/*=============================================
DETALLE DE FALTAS Y FUGAS CON JUSTIFICACIONES
=============================================*/
static public function ctrDetalleClase(){
  if(isset($_POST["idGrado"], $_POST["idMateria"], $_POST["idDocenteG"])){
    return ModeloAsistencia::mdlDetalleAsistencia(array("clase"=>array($_POST["idGrado"], $_POST["idDocenteG"], $_POST["idMateria"])));
  }
  return array();
}

static public function ctrDetalleEstudiante($idEstudiante){
  return ModeloAsistencia::mdlDetalleAsistencia(array("estudiante"=>$idEstudiante));
}

/*=============================================
TABLA RESUMEN (MISMA PARA LOS TRES REPORTES)
$campo: "estudiante" o "materia"
=============================================*/
static public function tablaResumen($filas, $campo, $titulo, $conPeriodo = false){
  echo '<h5 class="titulo-seccion"><i class="fas fa-chart-pie"></i> Resumen</h5>
  <table class="table table-bordered table-striped dt-responsive tabla-asistencia" id="tablaboton">
  <thead><tr><th style="width: 8px;">#</th>'.($campo=="estudiante" ? '<th>Cédula</th>' : '').'<th>'.$titulo.'</th>
  <th>Presentes</th><th>Faltas injustificadas</th><th>Faltas justificadas</th><th>Fugas</th><th>Total clases</th><th>% asistencia</th>'.
  ($conPeriodo ? '<th>Periodo</th>' : '').'</tr></thead><tbody>';
  $cont = 0;
  foreach((array)$filas as $f){
    $cont++;
    $total = (int)$f["total"];
    $porcentaje = $total > 0 ? round($f["P"] * 100 / $total) : 0;
    $color = $porcentaje >= 90 ? "success" : ($porcentaje >= 75 ? "warning" : "danger");
    echo '<tr><td>'.$cont.'</td>'.($campo=="estudiante" ? '<td>'.htmlspecialchars($f["cedula"]).'</td>' : '').'
      <td><b>'.htmlspecialchars($f[$campo]).'</b></td>
      <td><span class="contador presente">'.(int)$f["P"].'</span></td>
      <td>'.((int)$f["FI"] > 0 ? '<span class="contador falta">'.(int)$f["FI"].'</span>' : '<span class="text-muted">0</span>').'</td>
      <td>'.((int)$f["FJ"] > 0 ? '<span class="contador justificada">'.(int)$f["FJ"].'</span>' : '<span class="text-muted">0</span>').'</td>
      <td>'.((int)$f["FG"] > 0 ? '<span class="contador fuga">'.(int)$f["FG"].'</span>' : '<span class="text-muted">0</span>').'</td>
      <td>'.$total.'</td>
      <td data-order="'.$porcentaje.'"><div class="progress progress-sm mb-1"><div class="progress-bar bg-'.$color.'" style="width: '.$porcentaje.'%"></div></div><b>'.$porcentaje.'%</b></td>'.
      ($conPeriodo ? '<td>'.htmlspecialchars($f["periodo"]).'</td>' : '').'</tr>';
  }
  echo '</tbody></table>
  <small class="text-muted">% asistencia = presentes / total de clases registradas. Las faltas justificadas se muestran aparte.</small>';
}

/*=============================================
TABLA DE DETALLE: CADA FALTA O FUGA CON SU JUSTIFICACIÓN
$campo: "estudiante" (reporte por curso) o "materia" (reporte del estudiante)
=============================================*/
static public function tablaDetalle($filas, $campo, $titulo){
  $estados = array(
    "FI"=>array("Falta injustificada", "falta", "fa-times"),
    "FJ"=>array("Falta justificada", "justificada", "fa-file-medical"),
    "FG"=>array("Fuga", "fuga", "fa-running")
  );
  echo '<h5 class="titulo-seccion mt-4"><i class="fas fa-file-medical"></i> Detalle de faltas, fugas y justificaciones</h5>';
  if(empty($filas)){
    echo '<div class="alert alert-light border"><i class="fas fa-check-circle text-success"></i> No hay faltas ni fugas registradas.</div>';
    return;
  }
  echo '<table class="table table-bordered table-striped dt-responsive tabla-exportable tabla-asistencia" id="tablaDetalle" data-order=\'[[0,"desc"]]\'>
  <thead><tr><th>Fecha</th><th>'.$titulo.'</th><th>Estado</th><th>Justificación</th><th>Justificó</th></tr></thead><tbody>';
  foreach($filas as $f){
    $e = isset($estados[$f["asistencia"]]) ? $estados[$f["asistencia"]] : array($f["asistencia"], "", "fa-question");
    if($f["asistencia"]=="FJ"){
      $justificacion = $f["justificacion"]!="" ? htmlspecialchars($f["justificacion"]) : '<span class="text-muted">Sin motivo registrado</span>';
      $quien = $f["justificado_por"]=="" ? '<span class="text-muted">—</span>' : htmlspecialchars($f["justificado_por"]).($f["fecha_justificacion"] ? '<br><small class="text-muted">'.date("d/m/Y H:i", strtotime($f["fecha_justificacion"])).'</small>' : '');
    }else if($f["asistencia"]=="FI"){
      $justificacion = '<span class="text-danger"><i class="fas fa-hourglass-half"></i> Pendiente de justificar</span>';
      $quien = '<span class="text-muted">—</span>';
    }else{
      $justificacion = '<span class="text-muted">No aplica</span>';
      $quien = '<span class="text-muted">—</span>';
    }
    echo '<tr>
      <td data-order="'.$f["fecha"].'">'.date("d/m/Y", strtotime($f["fecha"])).'</td>
      <td>'.htmlspecialchars($f[$campo]).($campo=="estudiante" ? '<br><small class="text-muted">'.htmlspecialchars($f["cedula"]).'</small>' : '').'</td>
      <td><span class="estado-final '.$e[1].'"><i class="fas '.$e[2].'"></i> '.$e[0].'</span></td>
      <td>'.$justificacion.'</td>
      <td>'.$quien.'</td>
    </tr>';
  }
  echo '</tbody></table>';
}
}
