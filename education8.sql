/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.19-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: education8
-- ------------------------------------------------------
-- Server version	10.11.19-MariaDB-ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `edu_tab_asistencias`
--

DROP TABLE IF EXISTS `edu_tab_asistencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_asistencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_grad_docente` int(11) NOT NULL,
  `asistencia` varchar(50) NOT NULL,
  `justificacion` text DEFAULT NULL,
  `justificado_por` int(11) DEFAULT NULL,
  `fecha_justificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_edu_tab_asistencias_edu_tab_estudiantes1_idx` (`id_estudiante`),
  KEY `fk_edu_tab_asistencias_edu_tab_grad_docente1_idx` (`id_grad_docente`),
  CONSTRAINT `fk_edu_tab_asistencias_edu_tab_estudiantes1` FOREIGN KEY (`id_estudiante`) REFERENCES `edu_tab_estudiantes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_tab_asistencias_edu_tab_grad_docente1` FOREIGN KEY (`id_grad_docente`) REFERENCES `edu_tab_grad_docente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9028 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_docentes`
--

DROP TABLE IF EXISTS `edu_tab_docentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_docentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` varchar(15) NOT NULL,
  `nombres` text NOT NULL,
  `apellidos` text NOT NULL,
  `genero` varchar(45) NOT NULL,
  `email` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `usuario` text NOT NULL,
  `password` text NOT NULL,
  `rol` varchar(45) NOT NULL,
  `estado` int(11) DEFAULT NULL,
  `foto` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9005 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_estudiantes`
--

DROP TABLE IF EXISTS `edu_tab_estudiantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_estudiantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` varchar(15) NOT NULL,
  `nombres` text NOT NULL,
  `apellidos` text NOT NULL,
  `genero` varchar(45) NOT NULL,
  `email` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `id_representante` int(11) NOT NULL,
  `usuario` text NOT NULL,
  `password` text NOT NULL,
  `rol` varchar(150) NOT NULL,
  `foto` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_edu_tab_estudiantes_edu_tab_representantes_idx` (`id_representante`),
  CONSTRAINT `fk_edu_tab_estudiantes_edu_tab_representantes` FOREIGN KEY (`id_representante`) REFERENCES `edu_tab_representantes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_fotos`
--

DROP TABLE IF EXISTS `edu_tab_fotos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_fotos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` char(1) NOT NULL COMMENT 'U usuario, D docente, R representante, E estudiante',
  `id_persona` int(11) NOT NULL,
  `datos` mediumblob NOT NULL,
  `mime` varchar(30) NOT NULL,
  `actualizado` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_foto_persona` (`tipo`,`id_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_grad_docente`
--

DROP TABLE IF EXISTS `edu_tab_grad_docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_grad_docente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_grado_docente` int(11) NOT NULL,
  `id_docente` int(11) NOT NULL,
  `observacion` text DEFAULT NULL,
  `estado` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_edu_tab_grad_docente_edu_tab_grado_paralelo1_idx` (`id_grado_docente`),
  KEY `fk_edu_tab_grad_docente_edu_tab_docentes1_idx` (`id_docente`),
  KEY `fk_edu_tab_grad_docente_edu_tab_materias1_idx` (`id_materia`),
  CONSTRAINT `fk_edu_tab_grad_docente_edu_tab_docentes1` FOREIGN KEY (`id_docente`) REFERENCES `edu_tab_docentes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_tab_grad_docente_edu_tab_grado_paralelo1` FOREIGN KEY (`id_grado_docente`) REFERENCES `edu_tab_grado_paralelo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_tab_grad_docente_edu_tab_materias1` FOREIGN KEY (`id_materia`) REFERENCES `edu_tab_materias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_grado`
--

DROP TABLE IF EXISTS `edu_tab_grado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_grado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `orden` int(11) DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9004 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_grado_paralelo`
--

DROP TABLE IF EXISTS `edu_tab_grado_paralelo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_grado_paralelo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_grado` int(11) NOT NULL,
  `id_paralelo` int(11) NOT NULL,
  `cupos` int(11) NOT NULL,
  `observacion` text NOT NULL,
  `estado` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_edu_tab_grado_paralelo_edu_tab_grado1_idx` (`id_grado`),
  KEY `fk_edu_tab_grado_paralelo_edu_tab_paralelos1_idx` (`id_paralelo`),
  KEY `fk_edu_tab_grado_paralelo_edu_tab_periodo1_idx` (`id_periodo`),
  CONSTRAINT `fk_edu_tab_grado_paralelo_edu_tab_grado1` FOREIGN KEY (`id_grado`) REFERENCES `edu_tab_grado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_tab_grado_paralelo_edu_tab_paralelos1` FOREIGN KEY (`id_paralelo`) REFERENCES `edu_tab_paralelos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_tab_grado_paralelo_edu_tab_periodo1` FOREIGN KEY (`id_periodo`) REFERENCES `edu_tab_periodo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9996 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_horario`
--

DROP TABLE IF EXISTS `edu_tab_horario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_horario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_clase` int(11) NOT NULL,
  `dia` tinyint(1) NOT NULL COMMENT '1=lunes ... 7=domingo',
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_horario_clase_idx` (`id_clase`),
  KEY `idx_horario_dia` (`dia`),
  CONSTRAINT `fk_horario_clase` FOREIGN KEY (`id_clase`) REFERENCES `edu_tab_grad_docente` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9063 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_materias`
--

DROP TABLE IF EXISTS `edu_tab_materias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_materias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `observacion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_materias_docentes`
--

DROP TABLE IF EXISTS `edu_tab_materias_docentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_materias_docentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(45) DEFAULT NULL,
  `observacion` varchar(45) DEFAULT NULL,
  `id_materias` int(11) NOT NULL,
  `id_docentes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9006 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_materias_grado`
--

DROP TABLE IF EXISTS `edu_tab_materias_grado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_materias_grado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_materia` int(11) NOT NULL,
  `id_grado` int(11) NOT NULL,
  `observacion` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_materias_grado_materia_idx` (`id_materia`),
  KEY `fk_materias_grado_grado_idx` (`id_grado`),
  CONSTRAINT `fk_materias_grado_grado` FOREIGN KEY (`id_grado`) REFERENCES `edu_tab_grado` (`id`),
  CONSTRAINT `fk_materias_grado_materia` FOREIGN KEY (`id_materia`) REFERENCES `edu_tab_materias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_matricula`
--

DROP TABLE IF EXISTS `edu_tab_matricula`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_matricula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` int(11) NOT NULL,
  `observacion` text DEFAULT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_grado_paralelo` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `fecha_matricula` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_edu_tab_matricula_edu_tab_estudiantes1_idx` (`id_estudiante`),
  KEY `fk_edu_tab_matricula_edu_tab_grado_paralelo1_idx` (`id_grado_paralelo`),
  KEY `fk_edu_tab_matricula_edu_tab_periodo1_idx` (`id_periodo`),
  KEY `fk_edu_tab_matricula_edu_tab_usuarios1_idx` (`id_usuario`),
  CONSTRAINT `fk_edu_tab_matricula_edu_tab_estudiantes1` FOREIGN KEY (`id_estudiante`) REFERENCES `edu_tab_estudiantes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_tab_matricula_edu_tab_grado_paralelo1` FOREIGN KEY (`id_grado_paralelo`) REFERENCES `edu_tab_grado_paralelo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_tab_matricula_edu_tab_periodo1` FOREIGN KEY (`id_periodo`) REFERENCES `edu_tab_periodo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_tab_matricula_edu_tab_usuarios1` FOREIGN KEY (`id_usuario`) REFERENCES `edu_tab_usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_notas`
--

DROP TABLE IF EXISTS `edu_tab_notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_notas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parcial1q1` decimal(4,2) NOT NULL,
  `parcial2q1` decimal(4,2) NOT NULL,
  `parcial3q1` decimal(4,2) NOT NULL,
  `examenq1` decimal(4,2) NOT NULL,
  `quimestre1` decimal(4,2) NOT NULL,
  `parcial1q2` decimal(4,2) NOT NULL,
  `parcial2q2` decimal(4,2) NOT NULL,
  `parcial3q2` decimal(4,2) NOT NULL,
  `examenq2` decimal(4,2) NOT NULL,
  `quimestre2` decimal(4,2) NOT NULL,
  `parcial1q3` decimal(4,2) DEFAULT NULL,
  `parcial2q3` decimal(4,2) DEFAULT NULL,
  `parcial3q3` decimal(4,2) DEFAULT NULL,
  `examenq3` decimal(4,2) DEFAULT NULL,
  `quimestre3` decimal(4,2) DEFAULT NULL,
  `nota_final` decimal(4,2) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_clases_doc` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_edu_tab_notas_edu_tab_estudiantes1_idx` (`id_estudiante`),
  KEY `fk_edu_tab_notas_edu_tab_grad_docente1_idx` (`id_clases_doc`),
  CONSTRAINT `fk_edu_tab_notas_edu_tab_estudiantes1` FOREIGN KEY (`id_estudiante`) REFERENCES `edu_tab_estudiantes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edu_tab_notas_edu_tab_grad_docente1` FOREIGN KEY (`id_clases_doc`) REFERENCES `edu_tab_grad_docente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9166 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_paralelos`
--

DROP TABLE IF EXISTS `edu_tab_paralelos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_paralelos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `observacion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_periodo`
--

DROP TABLE IF EXISTS `edu_tab_periodo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_periodo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `observacion` text NOT NULL,
  `tipo_evaluacion` varchar(20) NOT NULL DEFAULT 'QUIMESTRAL',
  `estado` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_representantes`
--

DROP TABLE IF EXISTS `edu_tab_representantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_representantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` varchar(15) NOT NULL,
  `nombres` text NOT NULL,
  `apellidos` text NOT NULL,
  `genero` varchar(45) NOT NULL,
  `email` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `usuario` text NOT NULL,
  `password` text NOT NULL,
  `rol` varchar(150) NOT NULL,
  `foto` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9007 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edu_tab_usuarios`
--

DROP TABLE IF EXISTS `edu_tab_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_tab_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `rol` varchar(255) NOT NULL,
  `foto` text NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0,
  `ultimo_login` datetime DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9002 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.19-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: education8
-- ------------------------------------------------------
-- Server version	10.11.19-MariaDB-ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `edu_tab_usuarios`
--
-- WHERE:  usuario='admin'

LOCK TABLES `edu_tab_usuarios` WRITE;
/*!40000 ALTER TABLE `edu_tab_usuarios` DISABLE KEYS */;
INSERT INTO `edu_tab_usuarios` VALUES
(24,'admin','admin','$2y$10$TNjYYTn4oi2rCwocBjnlqex8.le/H/moi6.AV3iRzvnbOGdjuSMxO','Administrador','',1,'2026-11-09 16:39:52','2026-11-09 21:39:52');
/*!40000 ALTER TABLE `edu_tab_usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
