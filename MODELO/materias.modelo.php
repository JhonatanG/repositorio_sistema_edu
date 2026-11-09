<?php

require_once "conexion.php";

class ModeloMaterias{

    static public function mdlMostrarMaterias($tabla,$item,$valor){
        $id="id";
        if ($item!=null){
            $stmt=Conexion::conectar()->prepare("SELECT *FROM $tabla WHERE $item=:$item");
            $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt -> fetch();
            $stmt->close();
            $stmt=null;        
        }else{
            $stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY $id desc");
            $stmt->execute();
            return $stmt->fetchAll();
            $stmt->close();
            $stmt=null;
        }
    }

    static public function mdlCrearMaterias($tabla,$datos){
        $stmt=Conexion::conectar()->prepare("INSERT INTO $tabla(nombre,observacion) VALUES (:nombre,:observacion)");
        $stmt->bindParam(":nombre",$datos["nombre"],PDO::PARAM_STR);
        $stmt->bindParam(":observacion",$datos["observacion"],PDO::PARAM_STR);
        if(Conexion::ejecutar($stmt)){
            return "ok";
        }else{
            return "error";
        }
        $stmt->close();
        $stmt=null;
    }
    static public function mdlEditarMaterias($tabla,$datos){
        $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, observacion = :observacion WHERE id =:id ");
        $stmt->bindParam(":nombre",$datos["nombre"],PDO::PARAM_STR);
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

    static public function mdlEliminarMaterias($tabla,$datos){
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

    public static function mdlListarMaterias($tabla){
        if($tabla!=null){
        $stmt=Conexion::conectar()->prepare("SELECT *FROM $tabla order by nombre");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();
        $stmt=null;
    }
    }




}
