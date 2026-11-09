<?php
require_once "conexion.php";

class ModeloMdocentes{

    static public function mdlMostrarMdocentes($tabla,$item,$valor){
        
        if ($item!=null){
            $stmt=Conexion::conectar()->prepare("SELECT *FROM $tabla WHERE $item=:$item");
            $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt -> fetch();
            $stmt->close();
            $stmt=null;        
        }else{
            $stmt=Conexion::conectar()->prepare("SELECT etmd.id, CONCAT(etd.nombres,' ',etd.apellidos) as docente, 
            etm.nombre, etmd.observacion,etmd.estado
            from edu_tab_materias_docentes etmd 
            INNER JOIN edu_tab_docentes etd ON (etd.id = etmd.id_docentes) 
            INNER JOIN edu_tab_materias etm ON (etm.id = etmd.id_materias)
            ORDER BY etmd.id desc");
            $stmt->execute();
            return $stmt->fetchAll();
            $stmt->close();
            $stmt=null;
        }
    }
    static public function mdlMostrarRepetidos($tabla,$datos){
        if ($datos!=null){
            $stmt=Conexion::conectar()->prepare("SELECT id from $tabla WHERE id_materias=:id_materias AND
             id_docentes=:id_docentes");
             $stmt->bindParam(":id_materias",$datos["idMateria"],PDO::PARAM_INT);
            $stmt->bindParam(":id_docentes",$datos["idDocenteD"],PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
            var_dump($stmt);
      
        $stmt->close();
        $stmt=null;      
        }
    }

    static public function mdlCrearMdocentes($tabla,$datos){
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_materias,id_docentes,observacion,estado)
        VALUES (:idMateria,:idDocenteD,:observacion,:estado)");
        $stmt->bindParam(":idMateria",$datos["idMateria"],PDO::PARAM_INT);
        $stmt->bindParam(":idDocenteD",$datos["idDocenteD"],PDO::PARAM_INT);
        $stmt->bindParam(":observacion",$datos["observacion"],PDO::PARAM_STR);
        $stmt->bindParam(":estado",$datos["estado"],PDO::PARAM_STR);

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
ELIMINAR INFORMACION
=============================================*/

    static public function mdlEliminarMdocentes($tabla,$datos){
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
ACTUALIZAR INFORMACION
=============================================*/
    public static function mdlActualizarMdocente($tabla, $item1, $valor1, $item2, $valor2){
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
    static public function mdlEditarMdocente($tabla,$datos){
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




}
