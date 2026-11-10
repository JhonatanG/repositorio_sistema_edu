<?php
ob_start();//PERMITE RENOVAR LA SESIÓN DESPUÉS DE HABER ESCRITO HTML
require_once "CONTROLADOR/seguridad.php";
require_once "CONTROLADOR/usuarios.controlador.php";
require_once "CONTROLADOR/anio.controlador.php";
require_once "CONTROLADOR/materias.controlador.php";
require_once "CONTROLADOR/grados.controlador.php";
require_once "CONTROLADOR/paralelos.controlador.php";
require_once "CONTROLADOR/gparalelos.controlador.php";
require_once "CONTROLADOR/gmaterias.controlador.php";
require_once "CONTROLADOR/docentes.controlador.php";
require_once "CONTROLADOR/representantes.controlador.php";
require_once "CONTROLADOR/alumno.controlador.php";
require_once "CONTROLADOR/madocente.controlador.php";
require_once "CONTROLADOR/docgrado.controlador.php";
require_once "CONTROLADOR/matricula.controlador.php";
require_once "CONTROLADOR/asistencia.controlador.php";
require_once "CONTROLADOR/notas.controlador.php";
require_once "CONTROLADOR/calificaciones.php";
require_once "CONTROLADOR/horario.controlador.php";
require_once "CONTROLADOR/historial.controlador.php";
require_once "CONTROLADOR/perfil.controlador.php";







require_once "MODELO/usuarios.modelo.php";
require_once "MODELO/anio.modelo.php";
require_once "MODELO/materias.modelo.php";
require_once "MODELO/grados.modelo.php";
require_once "MODELO/paralelos.modelo.php";
require_once "MODELO/gparalelos.modelo.php";
require_once "MODELO/gmaterias.modelo.php";
require_once "MODELO/docentes.modelo.php";
require_once "MODELO/representantes.modelo.php";
require_once "MODELO/alumno.modelo.php";
require_once "MODELO/madocente.modelo.php";
require_once "MODELO/docgrado.modelo.php";
require_once "MODELO/matricula.modelo.php";
require_once "MODELO/asistencia.modelo.php";
require_once "MODELO/notas.modelo.php";
require_once "MODELO/inicio.modelo.php";
require_once "MODELO/horario.modelo.php";
require_once "MODELO/perfil.modelo.php";
require_once "MODELO/fotos.modelo.php";














include 'VISTA/plantilla.php';

