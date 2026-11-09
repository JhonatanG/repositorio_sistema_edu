<?php
//La función require_once() incluye y evalua el fichero especificado durante la ejecución del script.
require_once "conexion.php";

class ModeloUsuarios{

static public function mdlMostrarUsuarios($tabla,$item,$valor){

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
static public function mdlCrearUsuario($tabla,$datos){
    $stmt=Conexion::conectar()->prepare("INSERT INTO $tabla(nombre,usuario,password,rol,foto,estado) 
    values (:nombre,:usuario,:password,:rol,:foto,0)");
    $stmt->bindParam(":nombre",$datos["nombre"],PDO::PARAM_STR);
    $stmt->bindParam(":usuario",$datos["usuario"],PDO::PARAM_STR);
    $stmt->bindParam(":password",$datos["password"],PDO::PARAM_STR);
    $stmt->bindParam(":rol",$datos["rol"],PDO::PARAM_STR);
    $stmt->bindParam(":foto",$datos["foto"],PDO::PARAM_STR);
    if(Conexion::ejecutar($stmt)){
      return "ok";
    }else{
      return "error";
    }
    $stmt->close();

    $stmt=null;
}
static public function mdlEditarUsuario($tabla,$datos)
    {
      $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET nombre=:nombre,
      password=:password, rol=:rol,foto=:foto WHERE usuario = :usuario");
       $stmt->bindParam(":nombre",$datos["nombre"],PDO::PARAM_STR);
       $stmt->bindParam(":usuario",$datos["usuario"],PDO::PARAM_STR);
       $stmt->bindParam(":password",$datos["password"],PDO::PARAM_STR);
       $stmt->bindParam(":rol",$datos["rol"],PDO::PARAM_STR);
       $stmt->bindParam(":foto",$datos["foto"],PDO::PARAM_STR);
       if(Conexion::ejecutar($stmt)){
         return "ok";
       }else{
         return "error";
       }
       $stmt->close();

       $stmt=null;

    }
    /*=============================================
	ACTUALIZAR USUARIO ACTIVO E INACTIVO
	=============================================*/

	static public function mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2){

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
	BORAR USUARIO
	=============================================*/
  static public function mdlBorrarUsuario($tabla, $datos){

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


}
