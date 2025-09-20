<?php
/**
 * Cabezote (barra superior) de la aplicación.
 */
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Izquierda: botón para ocultar/mostrar sidebar -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
  <!-- Derecha: menú de usuario -->
  <ul class="navbar-nav ms-auto">
    <li class="nav-item dropdown">
      <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button">
        <?php echo htmlspecialchars($_SESSION['username'] ?? 'Usuario'); ?>
        <i class="far fa-user-circle"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="index.php?ruta=salir"><i class="fas fa-sign-out-alt me-2"></i>Salir</a></li>
      </ul>
    </li>
  </ul>
</nav>