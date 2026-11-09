<?php
require_once "MODELO/conexion.php";

class ModeloGmaterias{

    static public function mdlMostrarGmaterias($tabla,$item,$valor){
        
        if ($item!=null){
            $stmt=Conexion::conectar()->prepare("SELECT *FROM $tabla WHERE $item=:$item");
            $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt -> fetch();
            $stmt->close();
            $stmt=null;        
        }else{
            $stmt=Conexion::conectar()->prepare("SELECT mg.id as id, g.nombre as grado,m.nombre as materia
            FROM
            edu_tab_materias_grado mg
            INNER JOIN
            edu_tab_grado g
            on mg.id_grado=g.id
            INNER join edu_tab_materias m
            ON mg.id_materia=m.id");
            $stmt->execute();
            return $stmt->fetchAll();
            $stmt->close();
            $stmt=null;
        }
    }

    static public function mdlCrearGmaterias($tabla,$datos){
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_materia,id_grado,observacion)
        VALUES (:idMateria,:idGrado,:observacion)");
        $stmt->bindParam(":idMateria",$datos["idMateria"],PDO::PARAM_INT);
        $stmt->bindParam(":idGrado",$datos["idGrado"],PDO::PARAM_INT);
        $stmt->bindParam(":observacion",$datos["observacion"],PDO::PARAM_STR);


        if(Conexion::ejecutar($stmt))
        {
            return "ok";

        }else{
            return "error";
        }
        $stmt->close();
        $stmt=null; 
    }

    static public function mdlEliminarGmaterias($tabla,$datos){
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




}
