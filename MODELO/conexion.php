<?php
class Conexion{
/* Último error de base de datos traducido a un mensaje entendible */
static public $ultimoError = "";

/* Datos de conexión: se toman de variables de entorno (Docker) y, si no existen,
   se usan los valores por defecto de XAMPP */
static public function datos(){
return array(
"host" => getenv("DB_HOST") ?: "localhost",
"port" => getenv("DB_PORT") ?: "3306",
"dbname" => getenv("DB_NAME") ?: "education8",
"user" => getenv("DB_USER") ?: "root",
"pass" => getenv("DB_PASS") !== false ? getenv("DB_PASS") : ""
);
}

static public function conectar(){
//PDO=PERMITE ACCEDER A LA BASE DE DATOS
$db = self::datos();
$link= new PDO("mysql:host=".$db["host"].";port=".$db["port"].";dbname=".$db["dbname"],$db["user"],$db["pass"]);
$link -> exec("set names utf8");
return $link;
}

/*=============================================
EJECUTAR UNA CONSULTA SIN DETENER EL SISTEMA:
SI FALLA GUARDA EL MOTIVO EN $ultimoError
=============================================*/
static public function ejecutar($stmt, $tabla = "", $id = null){
self::$ultimoError = "";
try{
return $stmt -> execute();
}catch(PDOException $e){
self::$ultimoError = self::traducirError($e, $tabla, $id);
/*EN LAS PÁGINAS (NO EN AJAX) SE DEJA EL MOTIVO PARA QUE LO MUESTRE LA ALERTA DE ERROR*/
if(defined("SERVERURL")){
echo "<script>window.ERROR_BD = ".json_encode(self::$ultimoError, JSON_UNESCAPED_UNICODE).";</script>";
}
return false;
}
}

/*=============================================
CUENTA LOS REGISTROS DE OTRAS TABLAS QUE USAN ESTE REGISTRO
=============================================*/
static public function registrosRelacionados($tabla, $id){
$link = self::conectar();
$stmt = $link->prepare("SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME = :tabla AND REFERENCED_COLUMN_NAME = 'id'");
$stmt -> bindParam(":tabla", $tabla, PDO::PARAM_STR);
$stmt -> execute();
$nombres = self::nombresTablas();
$lista = array();
foreach($stmt -> fetchAll(PDO::FETCH_ASSOC) as $fk){
$hija = preg_replace('/[^a-z0-9_]/i', '', $fk["TABLE_NAME"]);
$columna = preg_replace('/[^a-z0-9_]/i', '', $fk["COLUMN_NAME"]);
$conteo = $link->prepare("SELECT COUNT(*) FROM `$hija` WHERE `$columna` = :id");
$conteo -> bindParam(":id", $id, PDO::PARAM_INT);
$conteo -> execute();
$total = (int)$conteo -> fetchColumn();
if($total > 0){
$nombre = isset($nombres[$hija]) ? $nombres[$hija] : $hija;
$lista[$nombre] = (isset($lista[$nombre]) ? $lista[$nombre] : 0) + $total;
}
}
$texto = array();
foreach($lista as $nombre=>$total){
$texto[] = $total." en ".$nombre;
}
return $texto;
}

static public function nombresTablas(){
return array(
"edu_tab_asistencias" => "Asistencias",
"edu_tab_notas" => "Notas",
"edu_tab_matricula" => "Matrículas",
"edu_tab_estudiantes" => "Estudiantes",
"edu_tab_docentes" => "Docentes",
"edu_tab_representantes" => "Representantes",
"edu_tab_materias" => "Materias",
"edu_tab_materias_docentes" => "Materias asignadas a docentes",
"edu_tab_grado" => "Grados",
"edu_tab_grado_paralelo" => "Grados y paralelos",
"edu_tab_grad_docente" => "Grados asignados a docentes",
"edu_tab_paralelos" => "Paralelos",
"edu_tab_periodo" => "Años lectivos",
"edu_tab_horario" => "Horarios",
"edu_tab_usuarios" => "Usuarios"
);
}

static public function traducirError(PDOException $e, $tablaPadre = "", $id = null){
$nombres = self::nombresTablas();
$codigo = isset($e->errorInfo[1]) ? $e->errorInfo[1] : 0;
$mensaje = $e->getMessage();

// 1451: el registro está siendo usado por otra tabla (clave foránea)
if($codigo == 1451){
if($tablaPadre != "" && $id !== null){
$relacionados = self::registrosRelacionados($tablaPadre, $id);
if(!empty($relacionados)){
$ultimo = array_pop($relacionados);
$detalle = empty($relacionados) ? $ultimo : implode(", ", $relacionados)." y ".$ultimo;
return "No se puede borrar porque tiene registros relacionados: ".$detalle.". Primero elimine esos registros o, si solo quiere que deje de usarse, desactívelo.";
}
}
$tabla = preg_match('/fails \(`[^`]+`\.`([^`]+)`/', $mensaje, $m) ? $m[1] : "";
$donde = isset($nombres[$tabla]) ? $nombres[$tabla] : ($tabla != "" ? $tabla : "otras partes del sistema");
return "Este registro está siendo usado en ".$donde.". Primero elimine o cambie esos registros relacionados y luego vuelva a intentarlo.";
}
// 1452: se eligió un dato relacionado que no existe
if($codigo == 1452){
return "Uno de los datos seleccionados ya no existe o no es válido. Recargue la página y vuelva a seleccionarlo.";
}
// 1364 y 1048: falta un dato obligatorio
if($codigo == 1364 || $codigo == 1048){
return "Falta completar un dato obligatorio.";
}
// 1406: texto demasiado largo
if($codigo == 1406){
return "Uno de los textos ingresados es demasiado largo.";
}
// 1062: valor duplicado
if($codigo == 1062){
return "Ya existe un registro con esos datos.";
}
return "Error de base de datos: ".$mensaje;
}

}
