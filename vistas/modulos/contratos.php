<?php
/**
 * Módulo de contratos: lista de contratos.
 */
?>
<?php
// Página de listado de contratos. Puede filtrar por cliente vía GET.
// Obtener ID de cliente si se pasa por la URL
$clienteId = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : null;
// Procesar edición de contrato
ControladorContratos::ctrEditarContrato();
// Obtener lista de contratos
$contratos = ControladorContratos::ctrMostrarContratos($clienteId);
// Generar listas únicas de desarrollos y tipos para filtros
$desarrollosLista = [];
$tiposLista = [];
foreach ($contratos as $ct) {
    if (!in_array($ct['nombre_desarrollo'], $desarrollosLista)) {
        $desarrollosLista[] = $ct['nombre_desarrollo'];
    }
    if (!in_array($ct['tipo_contrato'], $tiposLista)) {
        $tiposLista[] = $ct['tipo_contrato'];
    }
}
?>
<section class="content-header">
  <div class="container-fluid">
    <h1>Contratos</h1>
  </div>
</section>
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Listado de contratos</h3>
      </div>
      <div class="card-body">
        <!-- Filtros -->
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Filtrar por desarrollo</label>
            <select id="filtroDesarrollo" class="form-select">
              <option value="">Todos</option>
              <?php foreach ($desarrollosLista as $des) : ?>
                <option value="<?php echo htmlspecialchars($des, ENT_QUOTES); ?>"><?php echo htmlspecialchars($des); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Filtrar por tipo de contrato</label>
            <select id="filtroTipo" class="form-select">
              <option value="">Todos</option>
              <?php foreach ($tiposLista as $tip) : ?>
                <option value="<?php echo htmlspecialchars($tip, ENT_QUOTES); ?>"><?php echo htmlspecialchars($tip); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover" id="tablaContratos">
            <thead>
              <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Desarrollo</th>
                <th>Tipo contrato</th>
                <th>Mensualidades</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($contratos as $ct) : ?>
              <tr>
                <td><?php echo $ct['id']; ?></td>
                <td><?php echo htmlspecialchars($ct['nombre_cliente']); ?></td>
                <td><?php echo htmlspecialchars($ct['nombre_desarrollo']); ?></td>
                <td><?php echo htmlspecialchars($ct['tipo_contrato']); ?></td>
                <td><?php echo htmlspecialchars($ct['mensualidades']); ?></td>
                <td>
                  <!-- Botón editar contrato -->
                  <button type="button" class="btn btn-primary btn-sm btnEditarContrato" data-bs-toggle="modal" data-bs-target="#modalEditarContrato"
                    data-contrato-id="<?php echo $ct['id']; ?>"
                    data-mensualidades="<?php echo htmlspecialchars($ct['mensualidades']); ?>"
                    data-superficie="<?php echo htmlspecialchars($ct['superficie']); ?>"
                    data-fraccion="<?php echo htmlspecialchars($ct['fraccion_vendida']); ?>"
                    data-entrega="<?php echo htmlspecialchars($ct['entrega_posecion']); ?>"
                    data-firma="<?php echo htmlspecialchars($ct['fecha_firma_contrato']); ?>"
                    data-habitacional="<?php echo htmlspecialchars($ct['habitacional_colindancias']); ?>"
                    data-inicio="<?php echo htmlspecialchars($ct['inicio_pagos']); ?>"
                    data-tipo="<?php echo htmlspecialchars($ct['tipo_contrato']); ?>">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <!-- Botón generar documento (placeholder) -->
                  <button type="button" class="btn btn-secondary btn-sm" disabled title="Generar contrato (en construcción)">
                    <i class="fas fa-file-alt"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal editar contrato -->
  <div class="modal fade" id="modalEditarContrato" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <form id="formEditarContrato" method="post" action="index.php?ruta=contratos&accion=editarContrato">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="editarContrato" value="1">
          <input type="hidden" name="contrato_id" id="editarContratoId">
          <div class="modal-header">
            <h5 class="modal-title">Editar contrato</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Mensualidades</label>
                <input type="number" name="mensualidades" id="editarContratoMensualidades" class="form-control" min="1" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Superficie</label>
                <input type="text" name="superficie" id="editarContratoSuperficie" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Fracción vendida/cedida</label>
                <!-- Campo de texto para ingresar fracciones como etiquetas al editar -->
                <input type="text" class="form-control" id="inputFraccionEditar" placeholder="Ingresa una fracción y presiona Enter">
                <!-- Contenedor de etiquetas de fracciones -->
                <div id="contenedorFraccionesEditar" class="mt-2"></div>
                <!-- Campo oculto para enviar la lista separada por coma -->
                <input type="hidden" name="fracciones" id="hiddenFraccionesEditar">
              </div>
              <div class="col-md-6">
                <label class="form-label">Entrega de posesión</label>
                <input type="date" name="entrega_posecion" id="editarContratoEntrega" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Fecha de firma</label>
                <input type="date" name="fecha_firma" id="editarContratoFirma" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Habitacional y colindancias</label>
                <input type="text" name="habitacional" id="editarContratoHabitacional" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Inicio de pagos</label>
                <input type="date" name="inicio_pagos" id="editarContratoInicio" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Tipo de contrato</label>
                <input type="text" name="tipo_contrato" id="editarContratoTipo" class="form-control" readonly required>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>