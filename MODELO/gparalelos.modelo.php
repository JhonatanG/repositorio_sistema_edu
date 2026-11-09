<?php
require_once "conexion.php";

class ModeloGparalelos{

    static public function mdlMostrarGparalelos($tabla,$item,$valor){
        
        if ($item!=null){
            $stmt=Conexion::conectar()->prepare("SELECT *FROM $tabla WHERE $item=:$item");
            $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt -> fetch();
            $stmt->close();
            $stmt=null;        
        }else{
            $stmt=Conexion::conectar()->prepare("SELECT gp.id as id, g.nombre as grado,p.nombre as paralelo,
            gp.cupos,gp.observacion,gp.estado,etp.nombre as periodo
            FROM
            edu_tab_grado_paralelo gp
            INNER JOIN
            edu_tab_grado g
            on gp.id_grado=g.id
            INNER join edu_tab_paralelos p
            ON gp.id_paralelo=p.id
            INNER JOIN edu_tab_periodo etp
            ON
            (gp.id_periodo = etp.id)
            where etp.estado = '1'
            order by p.nombre asc");
            $stmt->execute();
            return $stmt->fetchAll();
            $stmt->close();
            $stmt=null;
        }
    }
 /*=============================================
GUARDAR INFORMACION
=============================================*/
    static public function mdlCrearGparalelos($tabla,$datos){
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_grado,id_paralelo,cupos,observacion,estado,id_periodo)
        VALUES (:idGrado,:idParalelo,:cupos,:observacion,:estado,:id_periodo)");
        $stmt->bindParam(":idGrado",$datos["idGrado"],PDO::PARAM_INT);
        $stmt->bindParam(":idParalelo",$datos["idParalelo"],PDO::PARAM_INT);
        $stmt->bindParam(":cupos",$datos["cupos"],PDO::PARAM_INT);
        $stmt->bindParam(":observacion",$datos["observacion"],PDO::PARAM_STR);
        $stmt->bindParam(":estado",$datos["estado"],PDO::PARAM_INT);
        $stmt->bindParam(":id_periodo",$datos["idPeriodo"],PDO::PARAM_INT);


        if(Conexion::ejecutar($stmt))
        {
            return "ok";

        }else{
            return "error";
        }
        $stmt->close();
        $stmt=null; 
    }

    static public function mdlEliminarGparalelos($tabla,$datos){
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
REGISTROS REPETIDOS
=============================================*/
    static public function mdlMostrarRepetidosG($tabla,$datos){
        if ($datos!=null){
            $stmt=Conexion::conectar()->prepare("SELECT id from $tabla WHERE id_grado=:id_grado AND
             id_paralelo=:id_paralelo AND id_periodo=:id_periodo");
             $stmt->bindParam(":id_grado",$datos["idGrado"],PDO::PARAM_INT);
            $stmt->bindParam(":id_paralelo",$datos["idParalelo"],PDO::PARAM_INT);
            $stmt->bindParam(":id_periodo",$datos["idPeriodo"],PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
            var_dump($stmt);
      
        $stmt->close();
        $stmt=null;      
        }
    }

    /*=============================================
ACTUALIZAR INFORMACION
=============================================*/
public static function mdlActualizarGparalelos($tabla, $item1, $valor1, $item2, $valor2){
    $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

    $stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
    $stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

    if(Conexion::ejecutar($stmt)){

        return "ok";
    
    }else{

        return "error";	

    }

    $stmt -> close();

    $stmt = null;

}
/*=============================================
EDITAR INFORMACION
=============================================*/
static public function mdlEditarGparalelos($tabla,$datos){
    $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET cupos = :cupos, observacion = :observacion WHERE id =:id ");
    $stmt->bindParam(":id",$datos["id"],PDO::PARAM_INT);
    $stmt->bindParam(":cupos",$datos["cupos"],PDO::PARAM_STR);
    $stmt->bindParam(":observacion",$datos["observacion"],PDO::PARAM_STR);
    if(Conexion::ejecutar($stmt)){
        return "ok";
    }else{
        return "eror";
    }
    $stmt->close();
    $stmt=null;

}



}
