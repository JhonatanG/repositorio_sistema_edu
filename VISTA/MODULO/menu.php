<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?php echo SERVERURL;  ?>inicio/" class="brand-link">
    <img src="<?php echo SERVERURL;  ?>VISTA/dist/img/escudo.png" alt="EducationEc Logo"
      class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">U.E.“Cristóbal Colón”</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <a href="<?php echo SERVERURL; ?>perfil/" class="foto-perfil" title="Mi perfil">
        <?php
$fotoMenu = (isset($_SESSION["foto"]) && $_SESSION["foto"] != "") ? $_SESSION["foto"] : "VISTA/img/anonymous.png";
echo '<img src="'.SERVERURL.htmlspecialchars($fotoMenu).'" class="img-circle elevation-2" alt="Foto de perfil">';
?>
        </a>
      </div>
      <div class="info">
        <a href="<?php echo SERVERURL;  ?>inicio/" class="d-block" style="color:white; text-decoration: none;"><?php echo $_SESSION["rol"] ?> </a>
      </div>
    </div>


    <nav class="mt-2 interceptando">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="<?php echo SERVERURL;  ?>inicio/" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>
              Inicio
            </p>
          </a>
        </li>

        <?php
    /*=============================================
USUARIOS
=============================================*/ 
      if ($_SESSION["rol"]=="Administrador"){
        echo'<li class="nav-item">
          <a href="'.SERVERURL.'usuarios/" class="nav-link" id="tab-usuarios">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Usuarios
            </p>
          </a>
        </li>';
      }
        ?>
        <?php
      /*=============================================
PARAMETROS
=============================================*/
    if($_SESSION["rol"]=="Administrador"){
     echo'  <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-grip-horizontal"></i>
            <p>
              Parámetros
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="'.SERVERURL.'anioL/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Año Lectivo</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="'.SERVERURL.'materias/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Materias</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="'.SERVERURL.'grados/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Grados</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="'.SERVERURL.'paralelos/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Paralelos</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="'.SERVERURL.'gmaterias/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Materias por grado</p>
              </a>
            </li>
        
          </ul>
        </li>';
    }
        ?>
        <?php 
      if ($_SESSION["rol"]=="Administrador"){
        /*=============================================
CUPOS
=============================================*/
        echo'<li class="nav-item">
          <a href="'.SERVERURL.'gparalelos/" class="nav-link" id="tab-usuarios">
            <i class="nav-icon fas fa-building"></i>
            <p>
              Cupos
            </p>
          </a>
        </li>';
      }
        ?>
        <?php
if ($_SESSION["rol"]=="Administrador"){
          /*=============================================
DOCENTE
=============================================*/
    echo'   <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chalkboard-teacher"></i>
            <p>
              Docente
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="'.SERVERURL.'docente/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Docente</p>
              </a>
            </li>
            <!-- <li class="nav-item">
              <a href="'.SERVERURL.'madocente/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Docentes-Materia</p>
              </a>
            </li> -->
            <li class="nav-item">
              <a href="'.SERVERURL.'docgrado/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Clases</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="'.SERVERURL.'horarios/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Horarios</p>
              </a>
            </li>

          </ul>
        </li>';
}
        ?>
              <?php
    /*=============================================
  DOCENTES PARA SECRETARIA
=============================================*/ 
      if ($_SESSION["rol"]=="Secretaría"){
        echo'<li class="nav-item">
          <a href="'.SERVERURL.'docente/" class="nav-link">
          <i class="nav-icon fas fa-chalkboard-teacher"></i>
            <p>
              Docentes
            </p>
          </a>
        </li>';
      }
        ?>
      <?php
    /*=============================================
ESTUDIANTES PARA SECRETARIA
=============================================*/ 
      if ($_SESSION["rol"]=="Secretaría"){
        echo'<li class="nav-item">
          <a href="'.SERVERURL.'alumnos/" class="nav-link">
          <i class="nav-icon fas fa-user-graduate"></i>
            <p>
              Estudiantes
            </p>
          </a>
        </li>';
      }
        ?>
      <?php
if ($_SESSION["rol"]=="Administrador"){
          /*=============================================
ESTUDIANTE
=============================================*/
     echo'   <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user-graduate"></i>
            <p>
              Estudiante
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="'.SERVERURL.'representantes/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Crear Representante
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="'.SERVERURL.'alumnos/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Crear Estudiante
                </p>
              </a>
            </li>


          </ul>
        </li>
        ';}
        ?>
        <?php
if ($_SESSION["rol"]=="Administrador" || $_SESSION["rol"]=="Secretaría" ){
          /*=============================================
MATRICULA
=============================================*/
    echo'   
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-chalkboard nav-icon"></i>
            <p>
              Matrícula
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="'.SERVERURL.'listaMatricula/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Lista de Matrículas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="'.SERVERURL.'matricula/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Crear Matrícula</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="'.SERVERURL.'listadoEstudiantes/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado de estudiantes</p>
              </a>
            </li>

          </ul>
        </li>';
        }?>
                <?php
if ($_SESSION["rol"]=="Docente"){
          /*=============================================
ASISTENCIA DOCENTE
=============================================*/
    echo'
        <li class="nav-item">
          <a href="'.SERVERURL.'miHorario/" class="nav-link">
            <i class="fas fa-clock nav-icon"></i>
            <p>Mi horario</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="'.SERVERURL.'asistencia/" class="nav-link">
            <i class="fas fa-calendar-check nav-icon"></i>
            <p>
              Asistencia
            </p>
          </a>
        </li>
        ';
        }?>
                        <?php
if ($_SESSION["rol"]=="Docente"){
          /*=============================================
NOTAS DOCENTE
=============================================*/
echo'   
<li class="nav-item">
  <a href="#" class="nav-link">
  <i class="fas fa-notes-medical nav-icon"></i>
    <p>
    Calificaciones
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="'.SERVERURL.'notas/" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Ingresar Calificaciones</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="'.SERVERURL.'reporteNotas/" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Reporte</p>
      </a>
    </li>

  </ul>
</li>';
   
        }?>
                                <?php
if ($_SESSION["rol"]=="Representante"){
          /*=============================================
ASISTENCIA ESTUDIANTE
=============================================*/
    echo'
        <li class="nav-item">
          <a href="'.SERVERURL.'reporteAsistenciaRep/" class="nav-link">
            <i class="fas fa-calendar-check nav-icon"></i>
            <p>
              Asistencia
            </p>
          </a>
        </li>
        ';
        }?>
                        <?php
if ($_SESSION["rol"]=="Estudiante"){
          /*=============================================
ASISTENCIA ESTUDIANTE
=============================================*/
    echo'
        <li class="nav-item">
          <a href="'.SERVERURL.'reporteAsistenciaEs/" class="nav-link">
            <i class="fas fa-calendar-check nav-icon"></i>
            <p>
              Asistencia
            </p>
          </a>
        </li>
        ';
        }?>
                        <?php
if ($_SESSION["rol"]=="Secretaría" || $_SESSION["rol"]=="Administrador"){
          /*=============================================
EDITAR ASISTENCIA 
=============================================*/
    echo'<li class="nav-item">
    <a href="#" class="nav-link">
      <i class="fas fa-calendar-check nav-icon"></i>
      <p>
        Asistencia
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
'.($_SESSION["rol"]=="Secretaría" ? '
        <li class="nav-item">
          <a href="'.SERVERURL.'justificarFaltas/" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Justificar faltas</p>
          </a>
        </li>' : '').'
        <li class="nav-item">
          <a href="'.SERVERURL.'editarAsistencia/" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>
             Editar Asistencia
            </p>
          </a>
        </li> 
        <li class="nav-item">
        <a href="'.SERVERURL.'reporteAsistencia/" class="nav-link">
          <i class="far fa-circle nav-icon"></i>
          <p>
           Reporte Asistencia
          </p>
        </a>
      </li> 
        </ul>
        </li>
        ';
        }?>
                                        <?php
if ($_SESSION["rol"]=="Administrador" || $_SESSION["rol"]=="Secretaría"  ){
          /*=============================================
ASISTENCIA ESTUDIANTE
=============================================*/
    echo'
        <li class="nav-item">
          <a href="'.SERVERURL.'reporteNotasG/" class="nav-link">
          <i class="fas fa-notes-medical nav-icon"></i>
            <p>
              Reporte Calificaciones
            </p>
          </a>
        </li>
        ';
        }?>
                                                <?php
if ($_SESSION["rol"]=="Estudiante"  ){
          /*=============================================
NOTAS ESTUDIANTE
=============================================*/
    echo'
        <li class="nav-item">
          <a href="'.SERVERURL.'reportesNotasEs/" class="nav-link">
          <i class="fas fa-notes-medical nav-icon"></i>
            <p>
              Reporte Calificaciones
            </p>
          </a>
        </li>
        ';
        }?>
                                                        <?php
if ($_SESSION["rol"]=="Representante"  ){
          /*=============================================
NOTAS ESTUDIANTE
=============================================*/
    echo'
        <li class="nav-item">
          <a href="'.SERVERURL.'reporteNotasRep/" class="nav-link">
          <i class="fas fa-notes-medical nav-icon"></i>
            <p>
              Reporte Calificaciones
            </p>
          </a>
        </li>
        ';
        }?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>