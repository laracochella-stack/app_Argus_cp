<?php
/**
 * Módulo de inicio (dashboard) con opciones principales.
 */
ControladorClientes::ctrAgregarCliente();
// Procesar alta de desarrollo desde formulario (en caso de envío tradicional)
ControladorDesarrollos::ctrAgregarDesarrollo();

// Obtener listas dinámicas para formularios
// Cargar nacionalidades y tipos de contrato desde el módulo de parámetros
$nacionalidadesList = [];
$tiposContratoList = [];
if (class_exists('ControladorParametros')) {
    $nacionalidadesList = ControladorParametros::ctrMostrarVariables('nacionalidad');
    $tiposContratoList = ControladorParametros::ctrMostrarVariables('tipo_contrato');
}

// Obtener listas de variables dinámicas para formularios
// Nacionalidades y tipos de contrato se gestionan desde el panel de parámetros.
$nacionalidadesList = ControladorParametros::ctrMostrarVariables('nacionalidad');
$tiposContratoList  = ControladorParametros::ctrMostrarVariables('tipo_contrato');
?>
<section class="content-header">
  <div class="container-fluid">
    <h1>Panel de control</h1>
  </div>
</section>
<section class="content">
  <div class="row">
    <!--<div class="col-lg-3 col-6">
      <div class="small-box bg-info" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente" style="cursor:pointer;">
        <div class="inner"><h4>Crear nuevo cliente</h4></div>
        <div class="icon"><i class="fas fa-user-plus"></i></div>
      </div>
    </div>-->
    <div class="col-lg-3 col-6">
      <a href="index.php?ruta=crearContrato" style="color:inherit;text-decoration:none;">
        <div class="small-box bg-success">
          <div class="inner"><h4>Crear contrato</h4></div>
          <div class="icon"><i class="nav-icon fas fa-file-signature"></i></div>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-6">
      <a href="index.php?ruta=clientes" style="color:inherit;text-decoration:none;">
        <div class="small-box bg-warning">
          <div class="inner"><h4>Lista de clientes</h4></div>
          <div class="icon"><i class="fas fa-users"></i></div>
        </div>
      </a>
    </div>
    
    <div class="col-lg-3 col-6">
      <a href="index.php?ruta=contratos" style="color:inherit;text-decoration:none;">
        <div class="small-box bg-primary">
          <div class="inner"><h4>Lista de contratos</h4></div>
          <div class="icon"><i class="nav-icon fas fa-file-contract"></i></div>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- Modal nuevo cliente -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="formCliente" method="post" action="index.php?ruta=inicio&accion=agregar">
        <!-- Token CSRF oculto para proteger el formulario de altas -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="modal-header">
          <h5 class="modal-title">Crear nuevo cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre completo</label>
              <input type="text" class="form-control" name="nombre" maxlength="50" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]{1,50}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nacionalidad</label>
              <select class="form-select" name="nacionalidad" required>
                <option value="" disabled selected>Seleccione</option>
                <?php if (!empty($nacionalidadesList)) : ?>
                  <?php foreach ($nacionalidadesList as $nac) : ?>
                    <option value="<?php echo htmlspecialchars($nac['identificador'], ENT_QUOTES); ?>">
                      <?php echo htmlspecialchars($nac['nombre']); ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha de nacimiento</label>
              <input type="date" class="form-control" name="fecha_nacimiento" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">RFC</label>
              <input type="text" class="form-control" name="rfc" pattern="^[A-Za-zñÑ&]{3,4}\d{6}\w{3}$" required>
            </div>
            <div class="col-md-6"><label class="form-label">CURP</label><input type="text" class="form-control" name="curp" required></div>
            <div class="col-md-6"><label class="form-label">INE (IDMEX)</label><input type="text" class="form-control" name="ine" required></div>
            <div class="col-md-6"><label class="form-label">Estado civil y régimen matrimonial</label><input type="text" class="form-control" name="estado_civil" required></div>
            <div class="col-md-6"><label class="form-label">Ocupación</label><input type="text" class="form-control" name="ocupacion" required></div>
            <div class="col-md-6"><label class="form-label">Teléfono</label><input type="tel" class="form-control" name="telefono" required></div>
            <div class="col-md-6"><label class="form-label">Domicilio</label><input type="text" class="form-control" name="domicilio" required></div>
            <div class="col-md-6"><label class="form-label">Correo electrónico</label><input type="email" class="form-control" name="email" required></div>
            <div class="col-md-6"><label class="form-label">Beneficiario</label><input type="text" class="form-control" name="beneficiario" required></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

