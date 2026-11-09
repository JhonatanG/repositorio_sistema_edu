<?php
require_once  "conexion.php";

class ModeloAsistencia{
          /*=============================================
LISTAS GRADOS DOCENTES
	=============================================*/
 static public function mdlListaGradosDocente($item,$valor){

    if($item!=null){
        $stmt = Conexion::conectar()->prepare("SELECT DISTINCT etgp.id,
    CONCAT(etg.nombre, ' ', etp.nombre) AS grado
FROM
    edu_tab_grado_paralelo etgp
        INNER JOIN
    edu_tab_grado etg ON etg.id = etgp.id_grado
        INNER JOIN
    edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
      INNER JOIN
    edu_tab_grad_docente etgd ON etgd.id_grado_docente = etgp.id
      INNER JOIN
    edu_tab_periodo etpe ON etpe.id = etgp.id_periodo AND etpe.estado = '1'
         WHERE
         etgd.$item= :$item AND etgd.estado='1'");
        $stmt->bindParam(":".$item,$valor,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt=null;
    }

 }
     /*=============================================
LISTAS MATERIAS DOCENTES
	=============================================*/
 static public function mdlListaMDocente($item,$valor,$item2,$valor2){

    if($item!=null){
        $stmt = Conexion::conectar()->prepare("SELECT DISTINCT
        etgd.id_materia as id,
        etmat.nombre as materia
    FROM
        edu_tab_grado_paralelo etgp
            INNER JOIN
        edu_tab_grad_docente etgd ON etgd.id_grado_docente = etgp.id
            INNER JOIN
        edu_tab_materias etmat ON etmat.id = etgd.id_materia
    WHERE
    etgd.$item= :$item  AND etgd.$item2= :$item2");
        $stmt->bindParam(":".$item,$valor,PDO::PARAM_INT);
        $stmt->bindParam(":".$item2,$valor2,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt=null;
    }

 }
     /*=============================================
LISTAR CABECERA
	=============================================*/
 static public function mdlCabeceraAsistencia($datos){
    if ($datos!=null){
    $stmt=Conexion::conectar()->prepare("SELECT DISTINCT etgd.id,
    CONCAT(etg.nombre, ' ', etp.nombre) AS grado,
    CONCAT(etd.nombres, ' ', etd.apellidos) AS docente,
    etmate.nombre AS materia
FROM
    edu_tab_grado_paralelo etgp
        INNER JOIN
    edu_tab_matricula etm ON etm.id_grado_paralelo = etgp.id
        INNER JOIN
    edu_tab_grado etg ON etg.id = etgp.id_grado
        INNER JOIN
    edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
        INNER JOIN
    edu_tab_grad_docente etgd ON etgd.id_grado_docente = etgp.id
        AND etm.id_grado_paralelo = etgd.id_grado_docente
        INNER JOIN
    edu_tab_docentes etd ON etd.id = etgd.id_docente
        INNER JOIN
    edu_tab_materias etmate ON etmate.id = etgd.id_materia
WHERE
    etgd.id_grado_docente = :id_grado_docente
        AND etgd.id_docente = :id_docente
        AND etgd.id_materia = :id_materia");
    $stmt->bindParam(":id_grado_docente",$datos["idGParalelo"],PDO::PARAM_INT);
    $stmt->bindParam(":id_docente",$datos["idDocente"],PDO::PARAM_INT);
    $stmt->bindParam(":id_materia",$datos["idMateria"],PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
    $stmt->close();
    $stmt=null;
    }
}

/*=============================================
LISTA ESTUDIANTES
	=============================================*/
    static public function mdlListaEstudiantes($datos){
        if ($datos!=null){
        $stmt=Conexion::conectar()->prepare("SELECT DISTINCT
        etgd.id AS gdoc,
        ete.id,
        ete.cedula,
        CONCAT(ete.apellidos,' ', ete.nombres) AS estudiante
    FROM
        edu_tab_estudiantes ete
    INNER JOIN edu_tab_matricula etm ON
        etm.id_estudiante = ete.id
    INNER JOIN edu_tab_grado_paralelo etgp
       ON etgp.id = etm.id_grado_paralelo
    INNER JOIN edu_tab_grad_docente etgd ON
        etgd.id_grado_docente = etgp.id
    INNER JOIN edu_tab_docentes etd ON
        etd.id = etgd.id_docente
    INNER JOIN edu_tab_materias etmate ON
        etmate.id = etgd.id_materia
    WHERE
          etgd.id_docente=:id_docente and etgd.id_grado_docente=:id_grado_docente
           and etgd.id_materia=:id_materia 
        order by 
            ete.apellidos ASC");
        $stmt->bindParam(":id_grado_docente",$datos["idGParalelo"],PDO::PARAM_INT);
        $stmt->bindParam(":id_docente",$datos["idDocente"],PDO::PARAM_INT);
        $stmt->bindParam(":id_materia",$datos["idMateria"],PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt=null;
        }
    }
        /*=============================================
GUARDAR INFORMACIÓN
=============================================*/
static public function mdlCrearAsistencia($tabla,$datos){
    $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_estudiante,id_grad_docente,fecha,asistencia)
    VALUES (:id_estudiante,:id_grad_docente,:fecha,:asistencia)");
    $stmt->bindParam(":id_estudiante",$datos["idEstudiante"],PDO::PARAM_INT);
    $stmt->bindParam(":id_grad_docente",$datos["idGdocente"],PDO::PARAM_INT);
    $stmt->bindParam(":fecha",$datos["fecha"],PDO::PARAM_STR);
    $stmt->bindParam(":asistencia",$datos["asistencia"],PDO::PARAM_STR);
    if(Conexion::ejecutar($stmt))
    {
        return "ok";

    }
    else{
        return "error";
    }
    $stmt->close();
    $stmt=null; 
}
/*=============================================
DEVUELVE SI YA EXISTE LA ASISTENCIA EN ESE DIA Y MATERIA
=============================================*/
static public function mdlAsistenciaExistente($datos){
    $stmt = Conexion::conectar()->prepare("SELECT
    id
    FROM
    edu_tab_asistencias
    WHERE
    fecha =:fecha AND id_grad_docente =:idGdocente");
    $stmt->bindParam(":fecha",$datos["fecha"],PDO::PARAM_STR);
    $stmt->bindParam(":idGdocente",$datos["idGdocente"],PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
    $stmt->close();
    $stmt=null;
}
/*=============================================
DEVUELVE EL ID DEL DOCENTE
=============================================*/
static public function mdlIdDocente($tabla,$datos){
    $stmt = Conexion::conectar()->prepare("SELECT
    id
    FROM
    $tabla
    WHERE
    id =:idDocente");
    $stmt->bindParam(":idDocente",$datos["idDocente"],PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
    $stmt->close();
    $stmt=null;
}
/*=============================================
LISTA ESTUDIANTES POR FECHA
	=============================================*/
    static public function mdlListaEstuFec($datos){
        if ($datos!=null){
        $stmt=Conexion::conectar()->prepare("SELECT DISTINCT
        eta.id as idAsis,
        etgd.id AS gdoc,
        ete.id,
        ete.cedula,
        CONCAT(ete.apellidos, ' ', ete.nombres) AS estudiante,
        eta.asistencia,
        eta.fecha
    FROM
        edu_tab_estudiantes ete
    INNER JOIN edu_tab_matricula etm ON
        etm.id_estudiante = ete.id
    INNER JOIN edu_tab_grado_paralelo etgp ON
        etgp.id = etm.id_grado_paralelo
    INNER JOIN edu_tab_grad_docente etgd ON
        etgd.id_grado_docente = etgp.id
    INNER JOIN edu_tab_docentes etd ON
        etd.id = etgd.id_docente
    INNER JOIN edu_tab_materias etmate ON
        etmate.id = etgd.id_materia
    INNER JOIN edu_tab_asistencias eta ON
      eta.id_grad_docente = etgd.id 
      AND
      eta.id_estudiante = ete.id
    WHERE
    etgd.id_docente=:id_docente and etgd.id_grado_docente=:id_grado_docente
           and etgd.id_materia=:id_materia 
        and eta.fecha=:fecha 
        order by 
            ete.apellidos ASC");
        $stmt->bindParam(":id_grado_docente",$datos["idGParalelo"],PDO::PARAM_INT);
        $stmt->bindParam(":id_docente",$datos["idDocente"],PDO::PARAM_INT);
        $stmt->bindParam(":id_materia",$datos["idMateria"],PDO::PARAM_INT);
        $stmt->bindParam(":fecha",$datos["fecha"],PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt=null;
        }
    }

/*=============================================
MOSTRAR TABLA ASISTENCIA
	=============================================*/
    static public function mdlMostrarAsistencia($tabla,$item,$valor){
       
        if ($item!=null){
            $stmt=Conexion::conectar()->prepare("SELECT *FROM $tabla WHERE $item=:$item");
            $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt -> fetch();
            $stmt->close();
            $stmt=null;        
        }
    }
    /*=============================================
EDITAR ASISTENCIA
	=============================================*/
    static public function mdlEditarAsistencia($tabla,$datos){
        if ($datos!=null){
            /*AL CAMBIAR EL ESTADO SE BORRA LA JUSTIFICACIÓN (SOLO SECRETARÍA JUSTIFICA)*/
            $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET asistencia=:asistencia, justificacion=NULL, justificado_por=NULL, fecha_justificacion=NULL WHERE id=:id");
            $stmt->bindParam(":asistencia",$datos["asistencia"],PDO::PARAM_STR);
            $stmt->bindParam(":id",$datos["idAsistencia"],PDO::PARAM_INT);
            if(Conexion::ejecutar($stmt)){
             return "ok";
            }else{
             return "error";
            }
            $stmt->close();
            $stmt=null;        
        }
    }
        /*=============================================
REPORTE ASISTENCIA
	=============================================*/
    static public function mdlReporteAsistencia($datos){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM (SELECT DISTINCT
        ete.id,
        ete.cedula,
        ete.apellidos,
        CONCAT(ete.apellidos, ' ', ete.nombres) AS estudiante,
      SUM(CASE WHEN eta.asistencia='FG' THEN 1 ELSE 0 end) as FG,
      SUM(CASE WHEN eta.asistencia='FI' THEN 1 ELSE 0 END) AS FI,
      SUM(CASE WHEN eta.asistencia='FJ' THEN 1 ELSE 0 END) AS FJ,
      SUM(CASE WHEN eta.asistencia='P' THEN 1 ELSE 0 END) AS P,
      COUNT(eta.id) AS total
    FROM
        edu_tab_estudiantes ete
    INNER JOIN edu_tab_matricula etm ON
        etm.id_estudiante = ete.id
    INNER JOIN edu_tab_grado_paralelo etgp ON
        etgp.id = etm.id_grado_paralelo
    INNER JOIN edu_tab_grad_docente etgd ON
        etgd.id_grado_docente = etgp.id
    INNER JOIN edu_tab_docentes etd ON
        etd.id = etgd.id_docente
    INNER JOIN edu_tab_materias etmate ON
        etmate.id = etgd.id_materia
    INNER JOIN edu_tab_asistencias eta ON
        eta.id_grad_docente = etgd.id AND eta.id_estudiante = ete.id
    WHERE
        etgd.id_docente = :id_docente AND etgd.id_grado_docente = :id_grado_docente
         AND etgd.id_materia = :id_materia
    GROUP BY  ete.id,
        ete.cedula) T1
        ORDER BY T1.apellidos");
        $stmt->bindParam("id_docente",$datos["idDocente"],PDO::PARAM_INT);
        $stmt->bindParam("id_grado_docente",$datos["idGParalelo"],PDO::PARAM_INT);
        $stmt->bindParam("id_materia",$datos["idMateria"],PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt=null;

    }
    /*=============================================
REPORTE ASISTENCIA POR CADA ESTUDIANTE
	=============================================*/
    static public function mdlReportAsisEstud($valor){
        $stmt = Conexion::conectar()->prepare("SELECT DISTINCT
        ete.id,
        ete.cedula,
        etm.nombre AS materia,
        etp.nombre AS periodo,
        SUM(
            CASE WHEN eta.asistencia = 'FG' THEN 1 ELSE 0
        END
    ) AS FG,
    SUM(
        CASE WHEN eta.asistencia = 'FI' THEN 1 ELSE 0
    END
    ) AS FI,
    SUM(
        CASE WHEN eta.asistencia = 'FJ' THEN 1 ELSE 0
    END
    ) AS FJ,
    SUM(CASE WHEN eta.asistencia = 'P' THEN 1 ELSE 0 END) AS P,
    COUNT(eta.id) AS total
    FROM
        edu_tab_estudiantes ete
    INNER JOIN edu_tab_asistencias eta ON
        eta.id_estudiante = ete.id
    INNER JOIN edu_tab_grad_docente etgd ON
        etgd.id = eta.id_grad_docente
    INNER JOIN edu_tab_materias etm ON
        etm.id = etgd.id_materia
    INNER JOIN edu_tab_grado_paralelo etgp ON
        etgp.id = etgd.id_grado_docente
    INNER JOIN edu_tab_periodo etp ON
        etp.id = etgp.id_periodo
    WHERE
        ete.id = :id_estudiante AND etp.estado = '1'
    GROUP BY
        ete.id,
        ete.cedula,
        etm.nombre,
        etp.nombre");
        $stmt->bindParam("id_estudiante",$valor,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt=null;

    }
        /*=============================================
OBTIENE ESTUDIANTES POR REPRESENTANTE
	=============================================*/
    static public function mdlEstuRepre($valor){
        $stmt = Conexion::conectar()->prepare("SELECT DISTINCT ete.id,ete.cedula,CONCAT(ete.nombres,' ',ete.apellidos) AS nombre
        FROM
        edu_tab_estudiantes ete
        INNER JOIN
        edu_tab_representantes etr
        ON
        (ete.id_representante=etr.id)
        WHERE
        etr.id=:idRepresentante");
        $stmt->bindParam("idRepresentante",$valor,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt=null;

    }

/*=============================================
DATOS DE UNA CLASE (CURSO, ASIGNATURA, DOCENTE)
=============================================*/
    static public function mdlInfoClase($idClase){
        $stmt = Conexion::conectar()->prepare("SELECT etgd.id, CONCAT(etg.nombre,' ',etp.nombre) AS curso, etm.nombre AS materia,
        CONCAT(etd.nombres,' ',etd.apellidos) AS docente
        FROM edu_tab_grad_docente etgd
        INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etgd.id_grado_docente
        INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
        INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
        INNER JOIN edu_tab_materias etm ON etm.id = etgd.id_materia
        INNER JOIN edu_tab_docentes etd ON etd.id = etgd.id_docente
        WHERE etgd.id = :id");
        $stmt->bindParam(":id",$idClase,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

/*=============================================
ASISTENCIA YA REGISTRADA DE UNA CLASE EN UNA FECHA
=============================================*/
    static public function mdlAsistenciaDelDia($idClase, $fecha){
        $stmt = Conexion::conectar()->prepare("SELECT eta.id, eta.asistencia, eta.justificacion, ete.cedula,
        CONCAT(ete.apellidos,' ',ete.nombres) AS estudiante
        FROM edu_tab_asistencias eta
        INNER JOIN edu_tab_estudiantes ete ON ete.id = eta.id_estudiante
        WHERE eta.id_grad_docente = :clase AND eta.fecha = :fecha
        ORDER BY ete.apellidos, ete.nombres");
        $stmt->bindParam(":clase",$idClase,PDO::PARAM_INT);
        $stmt->bindParam(":fecha",$fecha,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

/*=============================================
FALTAS PARA JUSTIFICAR (SECRETARÍA)
$filtros: desde, hasta, curso (0 = todos), estado (FI pendientes / FJ justificadas)
=============================================*/
    static public function mdlFaltas($filtros){
        $sql = "SELECT eta.id, eta.fecha, eta.asistencia, eta.justificacion, eta.fecha_justificacion,
        ete.cedula, CONCAT(ete.apellidos,' ',ete.nombres) AS estudiante,
        CONCAT(etg.nombre,' ',etp.nombre) AS curso, etm.nombre AS materia,
        CONCAT(etd.nombres,' ',etd.apellidos) AS docente, etu.nombre AS justificado_por
        FROM edu_tab_asistencias eta
        INNER JOIN edu_tab_estudiantes ete ON ete.id = eta.id_estudiante
        INNER JOIN edu_tab_grad_docente etgd ON etgd.id = eta.id_grad_docente
        INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etgd.id_grado_docente
        INNER JOIN edu_tab_periodo etpe ON etpe.id = etgp.id_periodo AND etpe.estado = '1'
        INNER JOIN edu_tab_grado etg ON etg.id = etgp.id_grado
        INNER JOIN edu_tab_paralelos etp ON etp.id = etgp.id_paralelo
        INNER JOIN edu_tab_materias etm ON etm.id = etgd.id_materia
        INNER JOIN edu_tab_docentes etd ON etd.id = etgd.id_docente
        LEFT JOIN edu_tab_usuarios etu ON etu.id = eta.justificado_por
        WHERE eta.asistencia = :estado AND eta.fecha BETWEEN :desde AND :hasta";
        if(!empty($filtros["curso"])){
            $sql .= " AND etgp.id = :curso";
        }
        $stmt = Conexion::conectar()->prepare($sql." ORDER BY eta.fecha DESC, ete.apellidos, etm.nombre");
        $stmt->bindParam(":estado",$filtros["estado"],PDO::PARAM_STR);
        $stmt->bindParam(":desde",$filtros["desde"],PDO::PARAM_STR);
        $stmt->bindParam(":hasta",$filtros["hasta"],PDO::PARAM_STR);
        if(!empty($filtros["curso"])){
            $stmt->bindParam(":curso",$filtros["curso"],PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

/*=============================================
JUSTIFICAR UNA FALTA O QUITAR LA JUSTIFICACIÓN
=============================================*/
    static public function mdlJustificar($id, $motivo, $idUsuario){
        $stmt = Conexion::conectar()->prepare("UPDATE edu_tab_asistencias SET asistencia = 'FJ', justificacion = :motivo,
        justificado_por = :usuario, fecha_justificacion = NOW() WHERE id = :id AND asistencia = 'FI'");
        $stmt->bindParam(":motivo",$motivo,PDO::PARAM_STR);
        $stmt->bindParam(":usuario",$idUsuario,PDO::PARAM_INT);
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        return Conexion::ejecutar($stmt) && $stmt->rowCount() == 1 ? "ok" : "error";
    }

    static public function mdlQuitarJustificacion($id){
        $stmt = Conexion::conectar()->prepare("UPDATE edu_tab_asistencias SET asistencia = 'FI', justificacion = NULL,
        justificado_por = NULL, fecha_justificacion = NULL WHERE id = :id AND asistencia = 'FJ'");
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        return Conexion::ejecutar($stmt) && $stmt->rowCount() == 1 ? "ok" : "error";
    }

/*=============================================
DETALLE DE FALTAS Y FUGAS CON SU JUSTIFICACIÓN (AÑO LECTIVO ACTIVO)
$filtro: array("clase"=>[idGParalelo, idDocente, idMateria]) o array("estudiante"=>id)
=============================================*/
    static public function mdlDetalleAsistencia($filtro){
        $sql = "SELECT eta.fecha, eta.asistencia, eta.justificacion, eta.fecha_justificacion,
        ete.cedula, CONCAT(ete.apellidos,' ',ete.nombres) AS estudiante, etm.nombre AS materia,
        etu.nombre AS justificado_por
        FROM edu_tab_asistencias eta
        INNER JOIN edu_tab_estudiantes ete ON ete.id = eta.id_estudiante
        INNER JOIN edu_tab_grad_docente etgd ON etgd.id = eta.id_grad_docente
        INNER JOIN edu_tab_materias etm ON etm.id = etgd.id_materia
        INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etgd.id_grado_docente
        INNER JOIN edu_tab_periodo etpe ON etpe.id = etgp.id_periodo AND etpe.estado = '1'
        LEFT JOIN edu_tab_usuarios etu ON etu.id = eta.justificado_por
        WHERE eta.asistencia <> 'P'";
        if(isset($filtro["estudiante"])){
            $stmt = Conexion::conectar()->prepare($sql." AND ete.id = :e ORDER BY eta.fecha DESC, etm.nombre");
            $stmt->bindParam(":e",$filtro["estudiante"],PDO::PARAM_INT);
        }else{
            $stmt = Conexion::conectar()->prepare($sql." AND etgd.id_grado_docente = :gp AND etgd.id_docente = :d AND etgd.id_materia = :m
            ORDER BY eta.fecha DESC, ete.apellidos");
            $stmt->bindParam(":gp",$filtro["clase"][0],PDO::PARAM_INT);
            $stmt->bindParam(":d",$filtro["clase"][1],PDO::PARAM_INT);
            $stmt->bindParam(":m",$filtro["clase"][2],PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
