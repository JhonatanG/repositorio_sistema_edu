<?php
require_once 'conexion.php';

class ModeloNotas{

/* Columnas de notas con los alias que usan las vistas (P1Q1 ... EXQ3, Q1 ... Q3, NF) */
static private function columnasNotas(){
    $columnas = array();
    for($k=1; $k<=3; $k++){
        $columnas[] = "etn.parcial1q$k AS P1Q$k";
        $columnas[] = "etn.parcial2q$k AS P2Q$k";
        $columnas[] = "etn.parcial3q$k AS P3Q$k";
        $columnas[] = "etn.examenq$k AS EXQ$k";
        $columnas[] = "etn.quimestre$k AS Q$k";
    }
    $columnas[] = "etn.nota_final AS NF";
    return implode(",\n        ", $columnas);
}

/*=============================================
TIPO DE EVALUACIÓN (QUIMESTRAL / TRIMESTRAL)
=============================================*/
    /* Del año lectivo al que pertenece un grado-paralelo */
    static public function mdlTipoGradoParalelo($idGradoParalelo){
        $stmt=Conexion::conectar()->prepare("SELECT etp.tipo_evaluacion FROM edu_tab_grado_paralelo etgp
        INNER JOIN edu_tab_periodo etp ON etp.id = etgp.id_periodo WHERE etgp.id = :id");
        $stmt->bindParam(":id",$idGradoParalelo,PDO::PARAM_INT);
        $stmt->execute();
        $tipo = $stmt->fetchColumn();
        return $tipo ? $tipo : "QUIMESTRAL";
    }
    /* Del año lectivo al que pertenece una clase (grado-docente-materia) */
    static public function mdlTipoClase($idClase){
        $stmt=Conexion::conectar()->prepare("SELECT etp.tipo_evaluacion FROM edu_tab_grad_docente etgd
        INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etgd.id_grado_docente
        INNER JOIN edu_tab_periodo etp ON etp.id = etgp.id_periodo WHERE etgd.id = :id");
        $stmt->bindParam(":id",$idClase,PDO::PARAM_INT);
        $stmt->execute();
        $tipo = $stmt->fetchColumn();
        return $tipo ? $tipo : "QUIMESTRAL";
    }
    /* Del año lectivo activo */
    static public function mdlTipoPeriodoActivo(){
        $tipo = Conexion::conectar()->query("SELECT tipo_evaluacion FROM edu_tab_periodo WHERE estado = '1' ORDER BY id DESC LIMIT 1")->fetchColumn();
        return $tipo ? $tipo : "QUIMESTRAL";
    }

/*=============================================
LISTA DE ESTUDIANTES DE UNA CLASE CON SUS NOTAS (VACÍAS SI AÚN NO TIENEN)
=============================================*/
    static public function mdlListaNotas($datos){
        if ($datos!=null){
        $stmt=Conexion::conectar()->prepare("SELECT DISTINCT
        etn.id as idNota,
        etgd.id AS gdoc,
        ete.id,
        ete.cedula,
        CONCAT(ete.apellidos, ' ', ete.nombres) AS estudiante,
        ".self::columnasNotas()."
    FROM
        edu_tab_estudiantes ete
    INNER JOIN edu_tab_matricula etm ON
        etm.id_estudiante = ete.id
    INNER JOIN edu_tab_grado_paralelo etgp ON
        etgp.id = etm.id_grado_paralelo
    INNER JOIN edu_tab_grad_docente etgd ON
        etgd.id_grado_docente = etgp.id
    LEFT JOIN edu_tab_notas etn ON
        etn.id_estudiante = ete.id AND etn.id_clases_doc = etgd.id
    WHERE
          etgd.id_docente=:id_docente and etgd.id_grado_docente=:id_grado_docente
           and etgd.id_materia=:id_materia
        order by
            ete.apellidos ASC");
        $stmt->bindParam(":id_grado_docente",$datos["idGParalelo"],PDO::PARAM_INT);
        $stmt->bindParam(":id_docente",$datos["idDocente"],PDO::PARAM_INT);
        $stmt->bindParam(":id_materia",$datos["idMateria"],PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

/*=============================================
GUARDAR NOTAS DE UN ESTUDIANTE (CREA O ACTUALIZA)
$datos: idClase, idEstudiante, notas[k][P1|P2|P3|EX], promedios[k], final, periodos
=============================================*/
    static public function mdlGuardarNota($datos){
        $link = Conexion::conectar();
        $buscar = $link->prepare("SELECT id FROM edu_tab_notas WHERE id_estudiante = :e AND id_clases_doc = :c LIMIT 1");
        $buscar->bindParam(":e",$datos["idEstudiante"],PDO::PARAM_INT);
        $buscar->bindParam(":c",$datos["idClase"],PDO::PARAM_INT);
        $buscar->execute();
        $idNota = $buscar->fetchColumn();

        /*LOS PERIODOS QUE NO USA EL TIPO DE EVALUACIÓN QUEDAN VACÍOS (EL 2 SIEMPRE EXISTE)*/
        $valores = array();
        for($k=1; $k<=3; $k++){
            $usado = $k <= $datos["periodos"];
            $vacio = $k <= 2 ? 0 : null;
            $valores["parcial1q$k"] = $usado ? $datos["notas"][$k]["P1"] : $vacio;
            $valores["parcial2q$k"] = $usado ? $datos["notas"][$k]["P2"] : $vacio;
            $valores["parcial3q$k"] = $usado ? $datos["notas"][$k]["P3"] : $vacio;
            $valores["examenq$k"]   = $usado ? $datos["notas"][$k]["EX"] : $vacio;
            $valores["quimestre$k"] = $usado ? $datos["promedios"][$k] : $vacio;
        }
        $valores["nota_final"] = $datos["final"];

        if($idNota){
            $sets = array();
            foreach($valores as $col=>$v){ $sets[] = "$col = :$col"; }
            $stmt = $link->prepare("UPDATE edu_tab_notas SET ".implode(", ",$sets)." WHERE id = :idNota");
            $stmt->bindValue(":idNota",$idNota,PDO::PARAM_INT);
        }else{
            $cols = array_keys($valores);
            $stmt = $link->prepare("INSERT INTO edu_tab_notas (".implode(",",$cols).",id_estudiante,id_clases_doc)
            VALUES (:".implode(",:",$cols).",:id_estudiante,:id_clases_doc)");
            $stmt->bindValue(":id_estudiante",$datos["idEstudiante"],PDO::PARAM_INT);
            $stmt->bindValue(":id_clases_doc",$datos["idClase"],PDO::PARAM_INT);
        }
        foreach($valores as $col=>$v){
            $stmt->bindValue(":$col", $v, $v===null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        }
        return Conexion::ejecutar($stmt) ? "ok" : "error";
    }

/*=============================================
REPORTE NOTAS POR CADA ESTUDIANTE (AÑO LECTIVO ACTIVO)
=============================================*/
    static public function mdlReportNotasEstud($valor){
        $stmt = Conexion::conectar()->prepare("SELECT DISTINCT
        ete.id,
        ete.cedula,
        etm.nombre AS materia,
        etp.nombre AS periodo,
        etp.tipo_evaluacion AS tipo,
        ".self::columnasNotas()."
    FROM
        edu_tab_estudiantes ete
    INNER JOIN edu_tab_notas etn ON
        etn.id_estudiante = ete.id
    INNER JOIN edu_tab_grad_docente etgd ON
        etgd.id = etn.id_clases_doc
    INNER JOIN edu_tab_materias etm ON
        etm.id = etgd.id_materia
    INNER JOIN edu_tab_grado_paralelo etgp ON
        etgp.id = etgd.id_grado_docente
    INNER JOIN edu_tab_periodo etp ON
        etp.id = etgp.id_periodo
    WHERE
        ete.id = :id_estudiante AND etp.estado = '1'
    ORDER BY etm.nombre");
        $stmt->bindParam(":id_estudiante",$valor,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

/*=============================================
¿EL AÑO LECTIVO YA TIENE NOTAS REGISTRADAS?
=============================================*/
    static public function mdlPeriodoTieneNotas($idPeriodo){
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) FROM edu_tab_notas etn
        INNER JOIN edu_tab_grad_docente etgd ON etgd.id = etn.id_clases_doc
        INNER JOIN edu_tab_grado_paralelo etgp ON etgp.id = etgd.id_grado_docente
        WHERE etgp.id_periodo = :id");
        $stmt->bindParam(":id",$idPeriodo,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
