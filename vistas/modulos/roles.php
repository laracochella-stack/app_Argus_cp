<?php
/**
 * Módulo de gestión de usuarios/roles.
 * Permite al administrador crear nuevos usuarios y ver los existentes.
 */
// Procesar alta de nuevo usuario si se envió el formulario
ControladorUsuarios::ctrRegistrarUsuario();
// Procesar eliminación de usuarios
ControladorUsuarios::ctrEliminarUsuario();
// Obtener lista de usuarios para mostrar
$usuarios = ControladorUsuarios::ctrMostrarUsuarios();
// Restringir acceso a este módulo sólo para administradores
if (($_SESSION['permission'] ?? '') !== 'admin') {
    echo '<div class="alert alert-danger m-3">No tiene permisos para acceder a este módulo.</div>';
    return;
}
?>
<section class="content-header">
  <div class="container-fluid">
    <h1>Usuarios y roles</h1>
  </div>
</section>
<section class="content">
  <div class="container-fluid">
    <!-- Formulario para crear un nuevo usuario -->
    <div class="card mb-4">
      <div class="card-header"><h3 class="card-title">Crear nuevo usuario</h3></div>
      <div class="card-body">
        <form method="post" action="">
          <!-- Token CSRF -->
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <div class="row">
            <div class="col-md-6 mb-6">
              <label class="form-label">Alias</label>
              <input type="text" name="nuevoNombre" class="form-control" required>
            </div>
            <div class="col-md-6 mb-6">
              <label class="form-label">Nombre de usuario</label>
              <input type="text" name="nuevoUsuario" class="form-control" required>
            </div>
            <div class="col-md-6 mb-6">
              <label class="form-label">Contraseña</label>
              <input type="password" name="nuevoPassword" class="form-control" required>
            </div>
            <div class="col-md-6 mb-6">
              <label class="form-label">Repetir contraseña</label>
              <input type="password" name="repetirPassword" class="form-control" required>
            </div>
            <div class="col-md-6 mb-6">
              <label class="form-label">Rol</label>
              <select name="nuevoRol" class="form-select" required>
                <option value="user">Usuario</option>
                <option value="admin">Administrador</option>
              </select>
            </div>
            
          </div>
          <div class="col-md-12 mb-12">   
              <div class="form-text">El rol "Usuario" tiene permisos limitados. Sólo los administradores pueden crear usuarios con rol "Administrador".</div>
            </div>
            <div class="col-md-6 mb-6">
              <button type="submit" class="btn btn-primary btn-sm">Guardar usuario</button>
            </div>
        </form>
      </div>
    </div>
    <!-- Tabla de usuarios existentes -->
    <div class="card">
      <div class="card-header"><h3 class="card-title">Usuarios registrados</h3></div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover">
          <thead>
            <tr><th>ID</th><th>Alias</th><th>Usuario</th><th>Permiso</th><th>Fecha alta</th><th>Acciones</th></tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $u) : ?>
            <tr>
              <td><?php echo $u['id']; ?></td>
              <td><?php echo htmlspecialchars($u['nombre_corto']); ?></td>
              <td><?php echo htmlspecialchars($u['username']); ?></td>
              <td><?php echo htmlspecialchars($u['permission']); ?></td>
              <td><?php echo htmlspecialchars($u['created_at']); ?></td>
              <td>
                <?php if (isset($_SESSION['iniciarSesion']) && $_SESSION['iniciarSesion'] === 'ok' && in_array($_SESSION['permission'], ['admin','moderator'])) : ?>
                  <?php if ($u['id'] != ($_SESSION['id'] ?? 0)) : ?>
                  <form method="post" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este usuario?');">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <input type="hidden" name="eliminarUsuario" value="1">
                    <input type="hidden" name="usuario_id" value="<?php echo $u['id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                  </form>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>