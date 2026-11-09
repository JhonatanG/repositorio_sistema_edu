  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?php echo SERVERURL;  ?>VISTA/dist/img/escudo.png" alt="U.E.“Cristóbal Colón" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="dropdown user user-menu open">
        <!-- <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a> -->
        <a href="inicio" class="dropdown-toggle" sytle="text-decoration: none; color: white;" data-toggle="dropdown" aria-expanded="true">
          <?php 
          if(isset($_SESSION["foto"]) && $_SESSION["foto"]!=""){
            echo '<img src="'.SERVERURL.''.$_SESSION["foto"].'" class="user-image" alt="Foto">';
          }else{
            echo '<img src="'.SERVERURL.'VISTA/img/anonymous.png" class="user-image" alt="Foto">';
          }
          ?>
          <span class="hidden-xs" style="color: white;"><?php 
          if(isset($_SESSION["apellidos"])){
            echo $_SESSION["nombre"];
            echo " ";
            echo $_SESSION["apellidos"];
          }else{
            echo $_SESSION["nombre"];
          }
          ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">

            <div class="media">
              <div class="media-body">

                <h3 class="dropdown-item-title">
                  ROL
                </h3>
                <p class="text-sm"><?php echo $_SESSION["rol"]?> </p>
              </div>
            </div>
          </a>
            <a href="<?php echo SERVERURL;  ?>perfil/" class="dropdown-item">
              <i class="fas fa-user-circle mr-2"></i> Mi perfil</a>
            <a href="<?php echo SERVERURL;  ?>cambiarPassword/" class="dropdown-item">
              <i class="fas fa-key mr-2"></i> Cambiar contraseña</a>
            <a href="<?php echo SERVERURL;  ?>salir/" class="dropdown-item dropdown-footer text-danger">
              <i class="fas fa-sign-out-alt mt-3 mr-3"></i>  Salir</a>
        </div>

      </li>
    </ul>
  </nav>