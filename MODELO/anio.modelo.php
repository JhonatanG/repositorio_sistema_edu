<?php

require_once "conexion.php";

class ModeloAnio{

    static public function mdlMostrarAnio($tabla,$item,$valor){
        $id="id";
        if ($item!=null){
            $stmt=Conexion::conectar()->prepare("SELECT *FROM $tabla WHERE $item=:$item");
            $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt -> fetch();
            $stmt->close();
            $stmt=null;        
        }        else{
            $stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id desc");
            $stmt->execute();
            return $stmt->fetchAll();
            $stmt->close();
            $stmt=null;
        }
    }
    static public function mdlListarAnio($tabla){
        $id="id";
        if ($tabla!=null){
            $stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE estado='1'");
            $stmt->execute();
            return $stmt->fetchAll();
            $stmt->close();
            $stmt=null;
        }
    }

    static public function mdlCrearAnio($tabla,$datos){
        $stmt=Conexion::conectar()->prepare("INSERT INTO $tabla(nombre,observacion,tipo_evaluacion,estado) VALUES (:nombre,:observacion,:tipo,0)");
        $stmt->bindParam(":tipo",$datos["tipo"],PDO::PARAM_STR);
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
    static public function mdlEditarAnio($tabla,$datos){
        $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, observacion = :observacion, tipo_evaluacion = :tipo WHERE id =:id ");
        $stmt->bindParam(":tipo",$datos["tipo"],PDO::PARAM_STR);
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

    static public function mdlEliminarAnio($tabla,$datos){
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
    public static function mdlActualizarAnio($tabla, $item1, $valor1, $item2, $valor2){
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




}
