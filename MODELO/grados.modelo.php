<?php

require_once "conexion.php";

class ModeloGrados{

    static public function mdlMostrarGrados($tabla,$item,$valor){
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
    static public function mdlCrearGrados($tabla,$datos){
        $stmt=Conexion::conectar()->prepare("INSERT INTO $tabla(nombre,observacion,orden) VALUES (:nombre,:observacion,:orden)");
        $stmt->bindValue(":orden",$datos["orden"],$datos["orden"]===null ? PDO::PARAM_NULL : PDO::PARAM_INT);
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
    static public function mdlEditarGrados($tabla,$datos){
        $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, observacion = :observacion, orden = :orden WHERE id =:id ");
        $stmt->bindValue(":orden",$datos["orden"],$datos["orden"]===null ? PDO::PARAM_NULL : PDO::PARAM_INT);
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

    static public function mdlEliminarGrados($tabla,$datos){
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

    static public function mdlListarGrados($tabla){
        $stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla ");
        $stmt->execute();
        return $stmt->fetchALL();
        $stmt->close();
        $stmt=null;
    }
}
