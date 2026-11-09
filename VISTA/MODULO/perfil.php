<?php
/*=============================================
MI PERFIL (TODOS LOS ROLES)
=============================================*/
ControladorPerfil::ctrFoto();
ControladorPerfil::ctrDatos();
$cfg = ControladorPerfil::config();
$perfil = ControladorPerfil::datos();
$rol = Seguridad::rol();
$foto = ($perfil && !empty($perfil["foto"])) ? SERVERURL.$perfil["foto"] : SERVERURL."VISTA/img/anonymous.png";
$nombreCompleto = $perfil ? (isset($perfil["nombres"]) ? $perfil["nombres"]." ".$perfil["apellidos"] : $perfil["nombre"]) : "";
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-user-circle"></i> Mi perfil</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo SERVERURL; ?>inicio/">Inicio</a></li>
            <li class="breadcrumb-item active">Mi perfil</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <?php if(!$perfil){ ?>
        <div class="alert alert-warning">No se encontró su perfil. Cierre sesión e ingrese de nuevo.</div>
      <?php }else{ ?>
      <div class="row">
        <!-- FOTO -->
        <div class="col-lg-4">
          <div class="card card-primary card-outline">
            <div class="card-body text-center">
              <form method="post" enctype="multipart/form-data" id="formFoto">
                <?php echo Seguridad::campoToken(); ?>
                <input type="hidden" name="accionFoto" id="accionFoto" value="cambiar">
                <div class="zona-foto" id="zonaFoto" title="Haga clic o arrastre una foto aquí">
                  <img src="<?php echo htmlspecialchars($foto); ?>" id="vistaFoto" alt="Foto de perfil">
                  <span class="zona-foto-icono"><i class="fas fa-camera"></i></span>
                </div>
                <input type="file" name="fotoPerfil" id="fotoPerfil" accept="image/jpeg,image/png" class="d-none">
                <h4 class="mt-3 mb-0"><?php echo htmlspecialchars($nombreCompleto); ?></h4>
                <span class="badge badge-primary"><?php echo htmlspecialchars($rol); ?></span>
                <p class="text-muted small mt-2 mb-3" id="ayudaFoto">Haga clic en la foto o arrástrela aquí. JPG o PNG; se recorta en cuadrado.</p>
                <div id="botonesNuevaFoto" class="d-none">
                  <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar foto</button>
                  <button type="button" class="btn btn-default" id="cancelarFoto">Cancelar</button>
                </div>
                <div id="botonesFoto">
                  <button type="button" class="btn btn-outline-primary" id="elegirFoto"><i class="fas fa-camera"></i> Cambiar foto</button>
                  <?php if(!empty($perfil["foto"])){ ?>
                  <button type="button" class="btn btn-outline-danger" id="quitarFoto"><i class="fas fa-trash"></i> Quitar</button>
                  <?php } ?>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- DATOS -->
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-id-card"></i> Mis datos</h3></div>
            <form method="post">
              <?php echo Seguridad::campoToken(); ?>
              <input type="hidden" name="actualizarPerfil" value="1">
              <div class="card-body">
                <div class="form-row">
                  <?php if(isset($perfil["cedula"])){ ?>
                  <div class="form-group col-md-4">
                    <label>Cédula</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($perfil["cedula"]); ?>" readonly>
                  </div>
                  <?php } ?>
                  <div class="form-group col-md-4">
                    <label>Usuario</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($perfil["usuario"]); ?>" readonly>
                  </div>
                  <?php if($rol=="Estudiante"){ $curso = ModeloPerfil::mdlCursoEstudiante($_SESSION["id"]); ?>
                  <div class="form-group col-md-4">
                    <label>Curso</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($curso ? $curso : "Sin matrícula activa"); ?>" readonly>
                  </div>
                  <?php } ?>
                </div>

                <?php if(in_array("nombre", $cfg["campos"])){ ?>
                <div class="form-group">
                  <label>Nombre</label>
                  <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($perfil["nombre"]); ?>" maxlength="60" required>
                </div>
                <?php }else{ ?>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Nombres</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($perfil["nombres"]); ?>" readonly>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Apellidos</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($perfil["apellidos"]); ?>" readonly>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Correo electrónico</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($perfil["email"]); ?>" maxlength="100">
                  </div>
                  <div class="form-group col-md-6">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" name="telefono" value="<?php echo htmlspecialchars($perfil["telefono"]); ?>" maxlength="20" pattern="[0-9 +()\-]{7,20}">
                  </div>
                </div>
                <div class="form-group">
                  <label>Dirección</label>
                  <input type="text" class="form-control" name="direccion" value="<?php echo htmlspecialchars($perfil["direccion"]); ?>" maxlength="150">
                </div>
                <small class="text-muted">La cédula, los nombres y los apellidos solo los puede corregir Secretaría o el Administrador.</small>
                <?php } ?>
              </div>
              <div class="card-footer d-flex align-items-center">
                <a href="<?php echo SERVERURL; ?>cambiarPassword/" class="btn btn-outline-secondary"><i class="fas fa-key"></i> Cambiar contraseña</a>
                <button class="btn btn-primary ml-auto"><i class="fas fa-save"></i> Guardar datos</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </section>
</div>
<script>
$(function () {
  var fotoOriginal = $("#vistaFoto").attr("src");
  var entrada = document.getElementById("fotoPerfil");
  if (!entrada) return;

  function abrirSelector() { entrada.click(); }
  $("#zonaFoto, #elegirFoto").on("click", abrirSelector);

  /* La foto se recorta en cuadrado y se reduce en el navegador (fotos del celular de varios MB) */
  function prepararFoto(archivo) {
    if (!archivo || !/^image\/(jpeg|png)$/.test(archivo.type)) {
      Swal.fire({ icon: "error", title: "Formato no válido", text: "Elija una imagen JPG o PNG." });
      return;
    }
    var lector = new FileReader();
    lector.onload = function (e) {
      var img = new Image();
      img.onload = function () {
        var lado = Math.min(img.width, img.height), tam = Math.min(lado, 800);
        var lienzo = document.createElement("canvas");
        lienzo.width = lienzo.height = tam;
        lienzo.getContext("2d").drawImage(img, (img.width - lado) / 2, (img.height - lado) / 2, lado, lado, 0, 0, tam, tam);
        lienzo.toBlob(function (blob) {
          var listo = new File([blob], "foto.jpg", { type: "image/jpeg" });
          var dt = new DataTransfer();
          dt.items.add(listo);
          entrada.files = dt.files;
          $("#vistaFoto").attr("src", URL.createObjectURL(blob));
          $("#accionFoto").val("cambiar");
          $("#botonesFoto").addClass("d-none");
          $("#botonesNuevaFoto").removeClass("d-none");
          $("#ayudaFoto").text("Así se verá su foto. Presione «Guardar foto».");
        }, "image/jpeg", 0.88);
      };
      img.src = e.target.result;
    };
    lector.readAsDataURL(archivo);
  }

  $(entrada).on("change", function () { prepararFoto(this.files[0]); });
  $("#zonaFoto").on("dragover", function (e) { e.preventDefault(); $(this).addClass("arrastrando"); })
    .on("dragleave drop", function () { $(this).removeClass("arrastrando"); })
    .on("drop", function (e) { e.preventDefault(); prepararFoto(e.originalEvent.dataTransfer.files[0]); });

  $("#cancelarFoto").on("click", function () {
    entrada.value = "";
    $("#vistaFoto").attr("src", fotoOriginal);
    $("#botonesNuevaFoto").addClass("d-none");
    $("#botonesFoto").removeClass("d-none");
    $("#ayudaFoto").text("Haga clic en la foto o arrástrela aquí. JPG o PNG; se recorta en cuadrado.");
  });

  $("#quitarFoto").on("click", function () {
    Swal.fire({ title: "¿Quitar su foto?", icon: "warning", showCancelButton: true, confirmButtonColor: "#d33",
      confirmButtonText: "Sí, quitar", cancelButtonText: "Cancelar" }).then(function (r) {
      if (r.value) {
        entrada.value = "";
        $("#accionFoto").val("quitar");
        $("#formFoto").submit();
      }
    });
  });
});
</script>
