<?php
/**
 * Módulo de inicio de sesión.
 */
?>
<div class="login-page" style="min-height:100vh; display:flex; justify-content:center; align-items:center;">
  <div class="login-box">
    <div class="login-logo">
      <b>Argus</b> CRM
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Inicie sesión para acceder</p>
        <form method="post" action="">
          <!-- Incluir un token CSRF oculto para proteger contra peticiones forjadas -->
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="ingUsuario" placeholder="Usuario" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-user"></span></div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="ingPassword" placeholder="Contraseña" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
            </div>
          </div>
        </form>

        <!-- Enlace para abrir el modal de registro -->
        <div class="mt-3 text-center">
          <a href="#" data-bs-toggle="modal" data-bs-target="#modalRegistro">Crear cuenta</a>
        </div>

        <?php
        // Llamar al controlador para procesar registro e inicio de sesión
        ControladorUsuarios::ctrRegistrarUsuario();
        ControladorUsuarios::ctrIngresoUsuario();
        ?>

      </div>
    </div>
  </div>
</div>

<!-- Modal de registro de usuario -->
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRegistroLabel">Crear cuenta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form method="post" id="formRegistro" action="">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <div class="mb-3">
            <input type="text" name="nuevoUsuario" class="form-control" placeholder="Usuario" required>
          </div>
          <div class="mb-3">
            <input type="password" name="nuevoPassword" class="form-control" placeholder="Contraseña" required>
          </div>
          <div class="mb-3">
            <input type="password" name="repetirPassword" class="form-control" placeholder="Repetir contraseña" required>
          </div>
          <!-- Rol se establece por defecto como 'user' para registros públicos -->
          <input type="hidden" name="nuevoRol" value="user">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="formRegistro" class="btn btn-primary">Registrarse</button>
      </div>
    </div>
  </div>