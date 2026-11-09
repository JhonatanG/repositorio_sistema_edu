<?php
require_once 'conexion.php';

class ModeloAlumnos{
  static public function mdlMostrarAlumnos($tabla,$item,$valor){

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
	CREAR ALUMNO
	=============================================*/
    static public function mdlCrearAlumno($tabla,$datos){
        $stmt=Conexion::conectar()->prepare("INSERT INTO $tabla(cedula, nombres, apellidos, 
        genero, email, telefono, direccion, fecha_nacimiento, id_representante, usuario, password, rol) 
        values (:cedula, :nombres, :apellidos, :genero
        , :email, :telefono, :direccion,:fecha,:representante,:usuario, :password, :rol)");
        $stmt->bindParam(":cedula",$datos["cedula"],PDO::PARAM_STR);
        $stmt->bindParam(":nombres",$datos["nombres"],PDO::PARAM_STR);
        $stmt->bindParam(":apellidos",$datos["apellidos"],PDO::PARAM_STR);
        $stmt->bindParam(":genero",$datos["genero"],PDO::PARAM_STR);
        $stmt->bindParam(":email",$datos["email"],PDO::PARAM_STR);
        $stmt->bindParam(":telefono",$datos["telefono"],PDO::PARAM_STR);
        $stmt->bindParam(":direccion",$datos["direccion"],PDO::PARAM_STR);
        $stmt->bindParam(":fecha",$datos["fecha"],PDO::PARAM_STR);
        $stmt->bindParam(":representante",$datos["representante"],PDO::PARAM_STR);
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
    static public function mdlMostrarAlumnoRepresentante($item,$valor){
      if($item!=null){
        $stmt = Conexion::conectar()->prepare("SELECT ate.CEDULA as cedulaE, ate.NOMBRES as nombresE,
        ate.APELLIDOS as apellidosE,ate.GENERO as generoE,
        ate.EMAIL as emailE,ate.TELEFONO as telefonoE, ate.DIRECCION as direccionE, 
        ate.FECHA_NACIMIENTO as fecha_nacimientoE,
        etr.CEDULA as cedulaR, etr.NOMBRES as nombresR,
        etr.APELLIDOS as apellidosR,etr.GENERO as generoR,
        etr.EMAIL as emailR,etr.TELEFONO as telefonoR, etr.DIRECCION as direccionR
        FROM edu_tab_estudiantes ate 
        INNER JOIN
        edu_tab_representantes etr
        ON
        (etr.id = ate.id_representante)
        where ate.id= :$item");
        $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
        $stmt->close();
        $stmt=null;
    }
      }
        /*=============================================
	EDITAR ESTUDIANTE 
	=============================================*/
  static public function mdlEditarEstudiante($tabla,$datos)
  {
    $stmt=Conexion::conectar()->prepare("UPDATE $tabla SET email=:email,
    telefono=:telefono,direccion=:direccion, id_representante=:id_representante, password =:password WHERE cedula = :cedula");
     $stmt->bindParam(":email",$datos["email"],PDO::PARAM_STR);
     $stmt->bindParam(":telefono",$datos["telefono"],PDO::PARAM_STR);
     $stmt->bindParam(":direccion",$datos["direccion"],PDO::PARAM_STR);
     $stmt->bindParam(":id_representante",$datos["id_representante"],PDO::PARAM_STR);
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
	BORAR ESTUDIANTE
	=============================================*/
  static public function mdlBorrarEstudiante($tabla, $datos){

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
	MOSTRAR ESTUDIANTE PARA EDITAR (CON SU REPRESENTANTE)
	=============================================*/
  static public function mdlMostrarAlumnoEditar($id){
    $stmt = Conexion::conectar()->prepare("SELECT ate.id as id, ate.cedula as cedula, ate.nombres as nombres, ate.apellidos as apellidos,
    ate.genero as genero, ate.email as email, ate.telefono as telefono, ate.direccion as direccion,
    ate.fecha_nacimiento as fecha_nacimiento, ate.id_representante as id_representante,
    CONCAT(etr.cedula, ': ', etr.nombres, ' ', etr.apellidos) as representante
    FROM edu_tab_estudiantes ate
    INNER JOIN edu_tab_representantes etr ON (etr.id = ate.id_representante)
    WHERE ate.id = :id");
    $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
    $stmt -> execute();
    return $stmt -> fetchAll(PDO::FETCH_ASSOC);
  }
}
