<?php
require_once 'conexion.php';

class ModeloRepresentantes{
    static public function mdlMostrarRepresentantes($tabla,$item,$valor){

        if($item!=null){
            $stmt = Conexion::conectar()->prepare("SELECT *FROM $tabla where $item= :$item");
            $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
            $stmt->close();
            $stmt=null;
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT *FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
            $stmt->close();
            $stmt=null;
         }
        }
  /*=============================================
CREAR epresentante 
	=============================================*/
        static public function mdlCrearRepresentante($tabla,$datos){
            $stmt=Conexion::conectar()->prepare("INSERT INTO $tabla(cedula, nombres, apellidos, 
            genero, email, telefono, direccion, usuario, password, rol) 
            values (:cedula, :nombres, :apellidos, :genero
            , :email, :telefono, :direccion,:usuario, :password, :rol)");
            $stmt->bindParam(":cedula",$datos["cedula"],PDO::PARAM_STR);
            $stmt->bindParam(":nombres",$datos["nombres"],PDO::PARAM_STR);
            $stmt->bindParam(":apellidos",$datos["apellidos"],PDO::PARAM_STR);
            $stmt->bindParam(":genero",$datos["genero"],PDO::PARAM_STR);
            $stmt->bindParam(":email",$datos["email"],PDO::PARAM_STR);
            $stmt->bindParam(":telefono",$datos["telefono"],PDO::PARAM_STR);
            $stmt->bindParam(":direccion",$datos["direccion"],PDO::PARAM_STR);
            $stmt->bindParam(":usuario",$datos["usuario"],PDO::PARAM_STR);
            $stmt->bindParam(":password",$datos["password"],PDO::PARAM_STR);
            $stmt->bindParam(":rol",$datos["rol"],PDO::PARAM_STR);
        

            if(Conexion::ejecutar($stmt)){
              return "ok";
            }else{
              return "error";
            }
            $stmt->close();
        
            $stmt=null;
        }
  /*=============================================
	EDITAR Representante 
	=============================================*/
        static public function mdlEditarRepresentante($tabla,$datos)
    {
      $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET email=:email,
      telefono=:telefono,direccion=:direccion,password=:password WHERE cedula = :cedula");
       $stmt->bindParam(":email",$datos["email"],PDO::PARAM_STR);
       $stmt->bindParam(":telefono",$datos["telefono"],PDO::PARAM_STR);
       $stmt->bindParam(":direccion",$datos["direccion"],PDO::PARAM_STR);
       $stmt->bindParam(":cedula",$datos["cedula"],PDO::PARAM_STR);
       $stmt->bindParam(":password",$datos["password"],PDO::PARAM_STR);

     
       if(Conexion::ejecutar($stmt)){
         return "ok";
       }else{
         return "error";
       }
       $stmt->close();

       $stmt=null;

    }
 /*=============================================
	BORAR Representante
	=============================================*/
  static public function mdlBorrarRepresentante($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if(Conexion::ejecutar($stmt, $tabla, $datos)){

			return "ok";
		
		}else{

			return "error";	
		}
		$stmt -> close();

		$stmt = null;
	}
   /*=============================================
	LISTAR REPRESENTANTE
	=============================================*/
  static public function mdlListarRepresentante($tabla){
    $stmt=Conexion::conectar()->prepare("SELECT id, CONCAT(Nombres, ' ', Apellidos) AS nombre,cedula from $tabla");
    $stmt->execute();
    return $stmt->fetchALL();
    $stmt->close();
    $stmt=null;
}
}
