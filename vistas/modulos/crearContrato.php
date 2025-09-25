<?php
/**
 * Vista para crear un cliente y su contrato de manera unificada.
 * Este módulo reemplaza a los formularios modales y se muestra como una página
 * independiente dentro del panel. Agrupa los campos en secciones para facilitar
 * la captura de información y utiliza la lógica existente para cargar
 * nacionalidades, tipos de contrato y desarrollos.
 */
// Procesar creación si se envía el formulario
ControladorContratos::ctrCrearContratoCompleto();

// Obtener listas para los selects
$nacionalidades = [];
$tiposContrato = [];
if (class_exists('ControladorParametros')) {
    $nacionalidades = ControladorParametros::ctrMostrarVariables('nacionalidad');
    $tipos = ControladorParametros::ctrMostrarVariables('tipo_contrato');
    foreach ($tipos as $t) {
        $tiposContrato[$t['identificador']] = $t['nombre'];
    }
}
// Obtener desarrollos disponibles
$desarrollos = ControladorDesarrollos::ctrMostrarDesarrollos();

$clienteId = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : 0;
$clienteData = null;

if ($clienteId > 0) {
    $clienteData = ModeloClientes::mdlMostrarClientePorId($clienteId);
}



?>
<section class="content-header">
  <div class="container-fluid">
    <h1>Crear contrato</h1>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <!-- Usar la misma ruta crearContrato para enviar el formulario. El controlador identificará
             la operación a través del campo oculto crearContratoCompleto -->
        <form id="formCrearContratoCompleto" method="post" action="index.php?ruta=contratos">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="crearContratoCompleto" value="1">

          <!-- Sección de datos del cliente -->
          <h5 class="mb-3">Datos del cliente</h5>
          <div class="row g-3 mb-4 p-3" style="background:#f8f9fa;border-radius:6px;">
            <?php if ($clienteId > 0 && $clienteData): ?>
              <!-- Cliente existente -->
              <input type="hidden" name="cliente_id" value="<?php echo $clienteId; ?>">

              <div class="alert alert-info">
                <strong>Cliente existente:</strong> <?php echo htmlspecialchars($clienteData['nombre']); ?> <br>
                RFC: <?php echo htmlspecialchars($clienteData['rfc']); ?> <br>
                Tel: <?php echo htmlspecialchars($clienteData['telefono']); ?>
              </div>
            <?php else: ?>
              <!-- Mostrar sección completa de datos del cliente -->
              <?php include "vistas/partials/form_cliente.php"; ?>
            <?php endif; ?>
          </div>

          <!-- Sección de datos del desarrollo -->
          <h5 class="mb-3">Datos del desarrollo</h5>
          <div class="row g-3 mb-4 p-3" style="background:#f8f9fa;border-radius:6px;">
            <?php include "vistas/partials/form_desarrollo.php"; ?>
          </div>

          <!-- Sección de datos del contrato -->
          <h5 class="mb-3">Datos del contrato</h5>
          <div class="row g-3 p-3" style="background:#f8f9fa;border-radius:6px;">
            <?php include "vistas/partials/form_contrato.php"; ?>
          </div>

          <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Guardar contrato</button>
          </div>
        </form>

        
      </div>
    </div>
  </div>
</section>