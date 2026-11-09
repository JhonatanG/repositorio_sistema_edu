<?php

require_once "conexion.php";
class ModeloMatricula{
     /*=============================================
MOSTRAR INFORMACION MATRICULA
=============================================*/
   static public function mdlMostrarMatricula($tabla,$item,$valor){
      $id="codigo";
      if ($item!=null){
          $stmt=Conexion::conectar()->prepare("SELECT *FROM $tabla WHERE $item=:$item ");
          $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
          $stmt->execute();
          return $stmt -> fetch();
          $stmt->close();
          $stmt=null;        
      }else{
          $stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla");
          $stmt->execute();
          return $stmt->fetchAll();
          $stmt->close();
          $stmt=null;
      }
  }
       /*=============================================
MOSTRAR INFORMACION DETALLADA MATRICULA
=============================================*/
static public function mdlMostrarMatriculaDet($tabla,$item,$valor){
   $id="codigo";
   if ($item!=null){
       $stmt=Conexion::conectar()->prepare("SELECT *FROM $tabla WHERE $item=:$item ");
       $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
       $stmt->execute();
       return $stmt -> fetch();
       $stmt->close();
       $stmt=null;        
   }else{
       $stmt=Conexion::conectar()->prepare("SELECT etm.id, etm.id_estudiante,
       etm.codigo,
       ete.cedula,
       CONCAT (ete.nombres, ' ', ete.apellidos) AS nombre,
       CONCAT (etg.nombre, ' ', etp.nombre) AS grado,
       etpe.nombre AS periodo,
       etm.observacion
  FROM edu_tab_matricula etm
       INNER JOIN edu_tab_estudiantes ete
          ON (ete.id = etm.id_estudiante)
       INNER JOIN edu_tab_grado_paralelo etgp
          ON (etgp.id = etm.id_grado_paralelo)
       INNER JOIN edu_tab_grado etg
          ON (etg.id = etgp.id_grado)
       INNER JOIN edu_tab_paralelos etp
          ON (etp.id = etgp.id_paralelo)
       INNER JOIN edu_tab_periodo etpe
          ON (etpe.id = etm.id_periodo)
 WHERE etpe.estado = '1'");
       $stmt->execute();
       return $stmt->fetchAll();
       $stmt->close();
       $stmt=null;
   }
}
  /*=============================================
LISTA INFORMACION GRADO PARALELO
=============================================*/
    static public function mdlMostrarGparalelos(){
      $stmt = Conexion::conectar()->prepare("SELECT gp.id AS id, CONCAT (g.nombre, ' ', p.nombre) AS grado, gp.cupos
      FROM edu_tab_grado_paralelo gp
           INNER JOIN edu_tab_grado g
              ON gp.id_grado = g.id
           INNER JOIN edu_tab_paralelos p
              ON gp.id_paralelo = p.id
           INNER JOIN edu_tab_periodo etp
              ON etp.id = gp.id_periodo
     WHERE gp.estado = '1' AND etp.estado = '1'
  ORDER BY p.nombre ASC
      ");
      $stmt->execute();
      return $stmt->fetchAll();
      $stmt->close();
      $stmt=null;

    }
      /*=============================================
LISTA INFORMACION GRADO PARALELO POR ID
=============================================*/
static public function mdlMostrarGparalelosporId($item,$valor){
  $stmt = Conexion::conectar()->prepare("SELECT gp.id AS id, CONCAT (g.nombre, ' ', p.nombre) AS grado, gp.cupos
  FROM edu_tab_grado_paralelo gp
       INNER JOIN edu_tab_grado g
          ON gp.id_grado = g.id
       INNER JOIN edu_tab_paralelos p
          ON gp.id_paralelo = p.id
          WHERE gp.$item=:$item
  ");
 $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
 $stmt->execute();
 return $stmt -> fetch();
 $stmt->close();
 $stmt=null;     

}    
/*=============================================
REGISTROS REPETIDOS
=============================================*/
    static public function mdlMostrarRepetidosM($tabla,$datos){
        if ($datos!=null){
            $stmt=Conexion::conectar()->prepare("SELECT id from
            $tabla
            WHERE id_periodo = :id_periodo
            AND id_estudiante = :id_estudiante");

            $stmt->bindParam(":id_periodo",$datos["idPeriodo"],PDO::PARAM_INT);
            $stmt->bindParam(":id_estudiante",$datos["idEstudiante"],PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
           
        $stmt->close();
        $stmt=null;      
        }
    }

   /*=============================================
	LISTAR ESTUDIANTE
	=============================================*/
  static public function mdlListarEstudiante($tabla){
    $stmt=Conexion::conectar()->prepare("SELECT id, CONCAT(Nombres, ' ', Apellidos) AS nombre,cedula from $tabla");
    $stmt->execute();
    return $stmt->fetchALL();
    $stmt->close();
    $stmt=null;
}

 /*=============================================
GUARDAR INFORMACION
=============================================*/
static public function mdlCrearMatricula($tabla,$datos){
   $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (codigo,observacion,id_estudiante,id_grado_paralelo,id_periodo,id_usuario)
   VALUES (:codigo,:observacion,:id_estudiante,:id_grado_paralelo,:id_periodo,:id_usuario)");
   $stmt->bindParam(":codigo",$datos["codigo"],PDO::PARAM_INT);
   $stmt->bindParam(":observacion",$datos["observacion"],PDO::PARAM_STR);
   $stmt->bindParam(":id_estudiante",$datos["idEstudiante"],PDO::PARAM_INT);
   $stmt->bindParam(":id_grado_paralelo",$datos["idGparalelo"],PDO::PARAM_INT);
   $stmt->bindParam(":id_periodo",$datos["idPeriodo"],PDO::PARAM_INT);
   $stmt->bindParam(":id_usuario",$datos["idUsuario"],PDO::PARAM_INT);

   if(Conexion::ejecutar($stmt))
   {
       return "ok";

   }else{
       return "error";
   }
   $stmt->close();
   $stmt=null; 
}
 /*=============================================
ACTUALIZAR NUMERO DE CUPOS DISMINUIR
=============================================*/
static public function mdlActualizaNum($tabla,$datos){
   $stmt = Conexion::conectar()->prepare("UPDATE edu_tab_grado_paralelo etgp
    Inner JOIN edu_tab_matricula etm ON (etm.id_grado_paralelo=etgp.id) set etgp.cupos = etgp.cupos -1 
    WHERE etm.codigo = :codigo");
   $stmt->bindParam(":codigo",$datos["codigo"],PDO::PARAM_INT);

   if(Conexion::ejecutar($stmt))
   {
       return "ok";

   }else{
       return "error";
   }
   $stmt->close();
   $stmt=null; 
}
/*=============================================
EDITAR INFORMACION
=============================================*/
static public function mdlEditarMatricula($tabla,$datos){
   $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET observacion = :observacion WHERE id =:id ");
   $stmt->bindParam(":id",$datos["id"],PDO::PARAM_INT);
   $stmt->bindParam(":observacion",$datos["observacion"],PDO::PARAM_STR);
   if(Conexion::ejecutar($stmt)){
       return "ok";
   }else{
       return "eror";
   }
   $stmt->close();
   $stmt=null;

}
 /*=============================================
ACTUALIZAR NUMERO DE CUPOS AUMENTAR
=============================================*/
static public function mdlActualizarNumAum($tabla,$datos){
   $stmt = Conexion::conectar()->prepare("UPDATE edu_tab_grado_paralelo etgp
    Inner JOIN edu_tab_matricula etm ON (etm.id_grado_paralelo=etgp.id) set etgp.cupos = etgp.cupos +1
    WHERE etm.id = :id");
   $stmt->bindParam(":id",$datos,PDO::PARAM_INT);

   if(Conexion::ejecutar($stmt))
   {
       return "ok";

   }else{
       return "error";
   }
   $stmt->close();
   $stmt=null; 
}
 /*=============================================
ELIMINAR
=============================================*/
static public function mdlEliminarMatricula($tabla,$datos){
   $stmt=Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id=:id ");
   $stmt->bindParam(":id",$datos,PDO::PARAM_INT);
   if(Conexion::ejecutar($stmt, $tabla, $datos)){
       return "ok";
   }else{
       return "error";
   }
   $stmt->close();
   $stmt=null;

}
/*=============================================
HISTORIAL: MATRÍCULAS DEL ESTUDIANTE (DE LA MÁS RECIENTE A LA MÁS ANTIGUA)
=============================================*/
static public function mdlMatriculasEstudiante($idEstudiante){
   $stmt = Conexion::conectar()->prepare("SELECT etm.id, etm.codigo, etm.observacion, etm.fecha_matricula, etm.id_grado_paralelo,
   etpe.id AS id_periodo, etpe.nombre AS periodo, etpe.estado AS periodo_activo, etpe.tipo_evaluacion,
   etg.id AS id_grado, etg.nombre AS grado, etg.orden, etp.nombre AS paralelo
   FROM edu_tab_matricula etm
   INNER JOIN edu_tab_periodo etpe ON etpe.id = etm.id_periodo
   INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etm.id_grado_paralelo
   INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
   INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
   WHERE etm.id_estudiante = :e
   ORDER BY etpe.nombre DESC, etpe.id DESC");
   $stmt->bindParam(":e",$idEstudiante,PDO::PARAM_INT);
   $stmt->execute();
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* Nota final de cada asignatura del curso (vacía si aún no tiene) */
static public function mdlNotasFinalesCurso($idEstudiante, $idGradoParalelo){
   $stmt = Conexion::conectar()->prepare("SELECT etm.nombre AS materia, etn.nota_final
   FROM edu_tab_grad_docente etgd
   INNER JOIN edu_tab_materias etm ON etm.id = etgd.id_materia
   LEFT JOIN edu_tab_notas etn ON etn.id_clases_doc = etgd.id AND etn.id_estudiante = :e
   WHERE etgd.id_grado_docente = :gp
   ORDER BY etm.nombre");
   $stmt->bindParam(":e",$idEstudiante,PDO::PARAM_INT);
   $stmt->bindParam(":gp",$idGradoParalelo,PDO::PARAM_INT);
   $stmt->execute();
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*=============================================
MATRÍCULA NUEVA: CURSOS DEL AÑO LECTIVO, NIVEL SIGUIENTE, CUPO Y CÓDIGO
=============================================*/
static public function mdlCursosPeriodo($idPeriodo){
   $stmt = Conexion::conectar()->prepare("SELECT etgp.id, etgp.cupos, etg.id AS id_grado, etg.nombre AS grado, etg.orden, etp.nombre AS paralelo
   FROM edu_tab_grado_paralelo etgp
   INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
   INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
   WHERE etgp.id_periodo = :p AND etgp.estado = '1'
   ORDER BY etg.orden IS NULL, etg.orden, etg.nombre, etp.nombre");
   $stmt->bindParam(":p",$idPeriodo,PDO::PARAM_INT);
   $stmt->execute();
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

static public function mdlGradoSiguiente($orden){
   $stmt = Conexion::conectar()->prepare("SELECT id, nombre, orden FROM edu_tab_grado WHERE orden > :o ORDER BY orden LIMIT 1");
   $stmt->bindParam(":o",$orden,PDO::PARAM_INT);
   $stmt->execute();
   return $stmt->fetch(PDO::FETCH_ASSOC);
}

static public function mdlDescontarCupo($idGradoParalelo){
   $stmt = Conexion::conectar()->prepare("UPDATE edu_tab_grado_paralelo SET cupos = cupos - 1 WHERE id = :id AND cupos > 0");
   $stmt->bindParam(":id",$idGradoParalelo,PDO::PARAM_INT);
   return Conexion::ejecutar($stmt) && $stmt->rowCount() == 1;
}

static public function mdlSiguienteCodigo(){
   return (int)Conexion::conectar()->query("SELECT IFNULL(MAX(codigo),0) + 1 FROM edu_tab_matricula")->fetchColumn();
}

static public function mdlEstudiantes(){
   return Conexion::conectar()->query("SELECT id, cedula, CONCAT(apellidos,' ',nombres) AS nombre FROM edu_tab_estudiantes ORDER BY apellidos, nombres")->fetchAll(PDO::FETCH_ASSOC);
}
/*=============================================
REPORTE: LISTADO DE ESTUDIANTES MATRICULADOS
$nivel y $paralelo son opcionales (0 = todos)
=============================================*/
static public function mdlListadoEstudiantes($idPeriodo, $idGrado, $idParalelo){
   $sql = "SELECT etm.codigo, etm.fecha_matricula, ete.id, ete.cedula, ete.apellidos, ete.nombres, ete.genero,
   ete.fecha_nacimiento, TIMESTAMPDIFF(YEAR, ete.fecha_nacimiento, CURDATE()) AS edad, ete.email, ete.telefono, ete.direccion, ete.usuario,
   etg.nombre AS grado, etp.nombre AS paralelo, CONCAT(etg.nombre,' ',etp.nombre) AS curso,
   CONCAT(etr.apellidos,' ',etr.nombres) AS representante, etr.telefono AS telefono_representante
   FROM edu_tab_matricula etm
   INNER JOIN edu_tab_estudiantes ete ON ete.id = etm.id_estudiante
   INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etm.id_grado_paralelo
   INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
   INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
   LEFT JOIN edu_tab_representantes etr ON etr.id = ete.id_representante
   WHERE etm.id_periodo = :periodo";
   if($idGrado){ $sql .= " AND etg.id = :grado"; }
   if($idParalelo){ $sql .= " AND etgp.id = :paralelo"; }
   $stmt = Conexion::conectar()->prepare($sql." ORDER BY etg.orden IS NULL, etg.orden, etg.nombre, etp.nombre, ete.apellidos, ete.nombres");
   $stmt->bindParam(":periodo",$idPeriodo,PDO::PARAM_INT);
   if($idGrado){ $stmt->bindParam(":grado",$idGrado,PDO::PARAM_INT); }
   if($idParalelo){ $stmt->bindParam(":paralelo",$idParalelo,PDO::PARAM_INT); }
   $stmt->execute();
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* Años lectivos (todos) para el filtro del reporte */
static public function mdlPeriodos(){
   return Conexion::conectar()->query("SELECT id, nombre, estado FROM edu_tab_periodo ORDER BY estado DESC, nombre DESC")->fetchAll(PDO::FETCH_ASSOC);
}
}
