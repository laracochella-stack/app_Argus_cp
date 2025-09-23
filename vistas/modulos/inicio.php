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
    <div class="col-lg-3 col-6">
      <div class="small-box bg-info" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente" style="cursor:pointer;">
        <div class="inner"><h4>Crear nuevo cliente</h4></div>
        <div class="icon"><i class="fas fa-user-plus"></i></div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-success" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#modalNuevoDesarrollo">
        <div class="inner"><h4>Crear desarrollo</h4></div>
        <div class="icon"><i class="fas fa-city"></i></div>
      </div>
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
        <div class="small-box bg-danger">
          <div class="inner"><h4>Lista de contratos</h4></div>
          <div class="icon"><i class="fas fa-file-contract"></i></div>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-6">
      <a href="index.php?ruta=desarrollos" style="color:inherit;text-decoration:none;">
        <div class="small-box bg-primary">
          <div class="inner"><h4>Lista de desarrollos</h4></div>
          <div class="icon"><i class="fas fa-city"></i></div>
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

<!-- Modal nuevo desarrollo -->
<div class="modal fade" id="modalNuevoDesarrollo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="formDesarrollo" method="post" action="index.php?ruta=inicio&accion=agregarDesarrollo">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="modal-header">
          <h5 class="modal-title">Crear desarrollo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre del desarrollo</label>
              <input type="text" class="form-control" name="nombre_desarrollo" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tipo de contrato</label>
              <select class="form-select" name="tipo_contrato" required>
                <option value="" disabled selected>Seleccione</option>
                <?php if (!empty($tiposContratoList)) : ?>
                  <?php foreach ($tiposContratoList as $tipo) : ?>
                    <option value="<?php echo htmlspecialchars($tipo['identificador'], ENT_QUOTES); ?>">
                      <?php echo htmlspecialchars($tipo['nombre']); ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Ubicación y descripción</label>
              <textarea class="form-control" name="descripcion" rows="2" required></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Superficie</label>
              <input type="text" class="form-control" name="superficie" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Clave catastral</label>
              <input type="text" class="form-control" name="clave_catastral" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Lotes disponibles</label>
              <!-- Campo para ingresar lotes individuales. El usuario escribe un lote y presiona Enter para agregarlo -->
              <input type="text" class="form-control" id="inputLoteNuevo" placeholder="Ingresa un lote y presiona Enter">
              <!-- Contenedor donde se mostrarán las etiquetas de lotes ingresados -->
              <div id="contenedorLotesNuevo" class="mt-2"></div>
              <!-- Input oculto que contendrá el arreglo JSON con los lotes -->
              <input type="hidden" name="lotes_disponibles" id="lotesDisponiblesNuevo">
            </div>
            <div class="col-md-6">
              <label class="form-label">Precio por lote</label>
              <!-- Agrupamos el campo con un prefijo para mostrar el símbolo de pesos -->
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" name="precio_lote" id="crearPrecioLote" required>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Precio total</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" name="precio_total" id="crearPrecioTotal" required>
              </div>
            </div>
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