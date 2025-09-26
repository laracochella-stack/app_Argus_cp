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
//ControladorContratos::ctrEditarContrato();
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

// Obtener lista de tipos de contrato para mapear identificador a nombre
$varsTipoContrato = [];
if (class_exists('ControladorParametros')) {
    $varsTipoContrato = ControladorParametros::ctrMostrarVariables('tipo_contrato');
}
$mapTiposContrato = [];
foreach ($varsTipoContrato as $var) {
    $mapTiposContrato[$var['identificador']] = $var['nombre'];
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
            
            <div id="accionesContrato" class="mt-3" style="display:none;"> 
              <label class="form-label">Filtrar por tipo de contrato</label> 
              <div id="contenedorBotones">sdsadasd</div>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover" id="tablaContratos">
  <thead>
    <tr>
      <th></th> <!-- columna nueva para selección -->
      <th>ID</th>
      <th>Creado el</th>
      <th>Creado por</th>
      <th>Folio</th>
      <th>Cliente</th>
      <th>Desarrollo</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($contratos as $ct) : ?>
    <tr data-contrato-id="<?php echo (int)$ct['id']; ?>" data-estatus="<?php echo (int)$ct['estatus']; ?>">
      <!-- Checkbox de selección -->
      <td><input type="checkbox" class="select-contrato"></td>
      <td><?php echo $ct['id']; ?></td>
      <td><?php echo htmlspecialchars($ct['created_at']); ?></td>
      <td><?php echo htmlspecialchars($ct['nombre_corto']); ?></td>
      <td><?php echo htmlspecialchars($ct['folio'] ?? ''); ?></td>
      <td><?php echo htmlspecialchars($ct['nombre_cliente']); ?></td>
      <td><?php echo htmlspecialchars($ct['nombre_desarrollo']); ?></td>
      <td>
        <button type="button"
                class="btn btn-success btn-sm btnGenerarContrato"
                data-contrato-id="<?php echo $ct['id']; ?>"
                title="Generar contrato">
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
                <input type="text" name="superficie" id="editarContratoSuperficie" class="form-control text-uppercase" required oninput="this.value = this.value.toUpperCase();">
                <!-- Campo oculto para almacenar la superficie convertida a letras en edición -->
                <input type="hidden" name="superficie_fixed" id="editarSuperficieFixed">
              </div>
              <div class="col-md-6">
                <label class="form-label">Fracción vendida/cedida</label>
                <!-- Campo de texto para ingresar fracciones como etiquetas al editar -->
                <input type="text" class="form-control" id="inputFraccionEditar" placeholder="Ingresa una fracción y presiona Enter">
                <!-- Contenedor de etiquetas de fracciones -->
                <div id="contenedorFraccionesEditar" class="mt-2"></div>
                <!-- Lista de fracciones disponibles para el desarrollo seleccionado en edición -->
                <label class="form-label mt-2">Lotes disponibles:</label>
                <div id="listaFraccionesDisponiblesEditar" class="mt-1" style="font-size:0.8rem;"></div>
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
                <!-- Campo de texto simple en mayúsculas para habitacional en edición -->
                <textarea class="form-control text-uppercase" name="habitacional" id="editarHabitacional" rows="3" oninput="this.value = this.value.toUpperCase();"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label">Inicio de pagos</label>
                <input type="date" name="inicio_pagos" id="editarContratoInicio" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Tipo de contrato</label>
                <!-- Campo oculto para enviar el identificador del tipo de contrato en edición -->
                <input type="hidden" name="tipo_contrato" id="editarContratoTipoId">
                <!-- Campo de solo lectura para mostrar el nombre del tipo de contrato -->
                <input type="text" id="editarContratoTipoNombre" class="form-control" readonly required>
              </div>

            <!-- Nuevos campos para el contrato -->
            <div class="col-md-6">
              <label class="form-label">Monto del precio del inmueble</label>
              <input type="number" step="0.01" name="monto_inmueble" id="editarMontoInmueble" class="form-control" required>
              <!-- Campo oculto con el monto convertido a letras -->
              <input type="hidden" name="monto_inmueble_fixed" id="editarMontoInmuebleFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Enganche o pago inicial</label>
              <input type="number" step="0.01" name="enganche" id="editarEnganche" class="form-control" required>
              <input type="hidden" name="enganche_fixed" id="editarEngancheFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Saldo de pago</label>
              <input type="number" step="0.01" name="saldo_pago" id="editarSaldoPago" class="form-control" readonly required>
              <input type="hidden" name="saldo_pago_fixed" id="editarSaldoPagoFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Parcialidades anuales</label>
              <input type="text" name="parcialidades_anuales" id="editarParcialidadesAnuales" class="form-control">
            </div>

            <!-- Nuevo campo: pago mensual en edición -->
            <div class="col-md-6">
              <label class="form-label">Pago mensual</label>
              <input type="number" step="0.01" name="pago_mensual" id="editarPagoMensual" class="form-control" required>
              <input type="hidden" name="pago_mensual_fixed" id="editarPagoMensualFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Penalización 10%</label>
              <input type="number" step="0.01" name="penalizacion" id="editarPenalizacion" class="form-control" readonly required>
              <input type="hidden" name="penalizacion_fixed" id="editarPenalizacionFixed">
            </div>
              <!-- Campo para folio -->
              <div class="col-md-6">
                <label class="form-label">Folio</label>
                <input type="text" name="folio" id="editarContratoFolio" class="form-control text-uppercase" required oninput="this.value = this.value.toUpperCase();">
              </div>
              <!-- Rango de pago unificado: inicio y fin -->
              <div class="col-md-6">
                <label class="form-label">Rango de pago (de - a)</label>
                <div class="input-group">
                  <input type="date" name="rango_pago_inicio" id="editarRangoPagoInicio" class="form-control">
                  <span class="input-group-text">a</span>
                  <input type="date" name="rango_pago_fin" id="editarRangoPagoFin" class="form-control">
                </div>
              </div>
            <div class="col-md-6">
              <label class="form-label">Vigencia del pagaré</label>
              <input type="date" name="vigencia_pagare" id="editarVigenciaPagare" class="form-control">
            </div>

            <!-- Fecha del contrato en edición -->
            <div class="col-md-6">
              <label class="form-label">Fecha del contrato</label>
              <input type="date" name="fecha_contrato" id="editarFechaContrato" class="form-control">
              <input type="hidden" name="fecha_contrato_fixed" id="editarFechaContratoFixed">
              <!-- Día de inicio (sólo número), calculado desde la fecha del contrato -->
              <input type="hidden" name="dia_inicio" id="editarDiaInicio">
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