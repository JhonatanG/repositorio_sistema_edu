<?php
require_once 'conexion.php';

class ModeloDocentes{
    static public function mdlMostrarDocentes($tabla,$item,$valor){

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

        static public function mdlCrearDocente($tabla,$datos){
            $stmt=Conexion::conectar()->prepare("INSERT INTO $tabla(cedula, nombres, apellidos, 
            genero, email, telefono, direccion, fecha_nacimiento, usuario, password, rol, foto) 
            values (:cedula, :nombres, :apellidos, :genero
            , :email, :telefono, :direccion, :fecha, :usuario, :password, :rol, :foto)");
            $stmt->bindParam(":cedula",$datos["cedula"],PDO::PARAM_STR);
            $stmt->bindParam(":nombres",$datos["nombres"],PDO::PARAM_STR);
            $stmt->bindParam(":apellidos",$datos["apellidos"],PDO::PARAM_STR);
            $stmt->bindParam(":genero",$datos["genero"],PDO::PARAM_STR);
            $stmt->bindParam(":email",$datos["email"],PDO::PARAM_STR);
            $stmt->bindParam(":telefono",$datos["telefono"],PDO::PARAM_STR);
            $stmt->bindParam(":direccion",$datos["direccion"],PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $datos["fecha"],PDO::PARAM_STR);
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
	EDITAR DOCENTE 
	=============================================*/
        static public function mdlEditarDocente($tabla,$datos)
    {
      $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET email=:email,
      telefono=:telefono,direccion=:direccion,password=:password,
      foto=:foto WHERE usuario = :usuario");
       $stmt->bindParam(":email",$datos["email"],PDO::PARAM_STR);
       $stmt->bindParam(":telefono",$datos["telefono"],PDO::PARAM_STR);
       $stmt->bindParam(":direccion",$datos["direccion"],PDO::PARAM_STR);
       $stmt->bindParam(":password",$datos["password"],PDO::PARAM_STR);
       $stmt->bindParam(":usuario",$datos["usuario"],PDO::PARAM_STR);
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
	ACTUALIZAR DOCENTE ACTIVO E INACTIVO
	=============================================*/

	static public function mdlActualizarDocente($tabla, $item1, $valor1, $item2, $valor2){

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
	BORAR Docente
	=============================================*/
  static public function mdlBorrarDocente($tabla, $datos){

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
	LISTAR DOCENTE
	=============================================*/
  static public function mdlListarDocente($tabla){
    $stmt=Conexion::conectar()->prepare("SELECT id, CONCAT(Nombres, ' ', Apellidos) AS nombre,cedula from $tabla
    where estado='1'");
    $stmt->execute();
    return $stmt->fetchALL();
    $stmt->close();
    $stmt=null;
}

}
