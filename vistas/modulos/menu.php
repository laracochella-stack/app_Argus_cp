<?php
/**
 * Menú lateral de navegación.
 */
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="index.php?ruta=inicio" class="brand-link text-center">
    <!-- Cambiar nombre de la marca en el menú lateral -->
    <span class="brand-text font-weight-light">Contratos Grupo Argus</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-item">
          <a href="index.php?ruta=inicio" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Inicio</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="index.php?ruta=clientes" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Clientes</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="index.php?ruta=desarrollos" class="nav-link">
            <i class="nav-icon fas fa-city"></i>
            <p>Desarrollos</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="index.php?ruta=contratos" class="nav-link">
            <i class="nav-icon fas fa-file-contract"></i>
            <p>Contratos</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="index.php?ruta=crearContrato" class="nav-link">
            <i class="nav-icon fas fa-file-signature"></i>
            <p>Crear contrato</p>
          </a>
        </li>
        <?php if (in_array($_SESSION['permission'] ?? '', ['admin','moderator'])) : ?>
        <li class="nav-item">
          <a href="index.php?ruta=parametros" class="nav-link">
            <i class="nav-icon fas fa-sliders-h"></i>
            <p>Parámetros</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="index.php?ruta=roles" class="nav-link">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>Usuarios</p>
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</aside>