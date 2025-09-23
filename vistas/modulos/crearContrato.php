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
        <form id="formCrearContratoCompleto" method="post" action="index.php?ruta=crearContrato">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="crearContratoCompleto" value="1">
          <!-- Sección de datos del cliente -->
          <h5 class="mb-3">Datos del cliente</h5>
          <div class="row g-3 mb-4 p-3" style="background:#f8f9fa;border-radius:6px;">
            <div class="col-md-6">
              <label class="form-label">Nombre completo</label>
              <input type="text" class="form-control text-uppercase" name="cliente_nombre" required oninput="this.value = this.value.toUpperCase();">
            </div>
            <div class="col-md-6">
              <label class="form-label">Nacionalidad</label>
              <!-- Para mantener compatibilidad con la base de datos, almacenamos el nombre de la nacionalidad en lugar del identificador -->
              <select class="form-select" name="cliente_nacionalidad" required>
                <option value="">Seleccione</option>
                <?php foreach ($nacionalidades as $nac) : ?>
                  <option value="<?php echo htmlspecialchars($nac['nombre'], ENT_QUOTES); ?>">
                    <?php echo htmlspecialchars($nac['nombre']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha de nacimiento</label>
              <input type="date" class="form-control" name="cliente_fecha_nacimiento" id="clienteFechaNacimiento" required>
              <!-- Campo oculto para almacenar la edad calculada del cliente -->
              <input type="hidden" name="cliente_edad" id="clienteEdad">
            </div>
            <div class="col-md-6">
              <label class="form-label">RFC</label>
              <input type="text" class="form-control text-uppercase" name="cliente_rfc" required oninput="this.value = this.value.toUpperCase();">
            </div>
            <div class="col-md-6">
              <label class="form-label">CURP</label>
              <input type="text" class="form-control text-uppercase" name="cliente_curp" required oninput="this.value = this.value.toUpperCase();">
            </div>
            <div class="col-md-6">
              <label class="form-label">INE (IDMEX)</label>
              <input type="text" class="form-control text-uppercase" name="cliente_ine" required oninput="this.value = this.value.toUpperCase();">
            </div>
            <div class="col-md-6">
              <label class="form-label">Estado civil y régimen matrimonial</label>
              <input type="text" class="form-control text-uppercase" name="cliente_estado_civil" required oninput="this.value = this.value.toUpperCase();">
            </div>
            <div class="col-md-6">
              <label class="form-label">Ocupación</label>
              <input type="text" class="form-control text-uppercase" name="cliente_ocupacion" required oninput="this.value = this.value.toUpperCase();">
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input type="tel" class="form-control" name="cliente_telefono" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Domicilio</label>
              <input type="text" class="form-control text-uppercase" name="cliente_domicilio" required oninput="this.value = this.value.toUpperCase();">
            </div>
            <div class="col-md-6">
              <label class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" name="cliente_email" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Beneficiario</label>
              <input type="text" class="form-control text-uppercase" name="cliente_beneficiario" required oninput="this.value = this.value.toUpperCase();">
            </div>
          </div>

          <!-- Sección de datos del desarrollo -->
          <h5 class="mb-3">Datos del desarrollo</h5>
          <div class="row g-3 mb-4 p-3" style="background:#f8f9fa;border-radius:6px;">
            <div class="col-md-6">
              <label class="form-label">Desarrollo</label>
              <select class="form-select" name="desarrollo_id" id="selectDesarrolloCrear" required>
                <option value="">Seleccione un desarrollo</option>
                <?php foreach ($desarrollos as $des) : ?>
                  <option value="<?php echo $des['id']; ?>"
                          data-superficie="<?php echo htmlspecialchars($des['superficie'], ENT_QUOTES); ?>"
                          data-tipo-id="<?php echo htmlspecialchars($des['tipo_contrato'], ENT_QUOTES); ?>"
                          data-tipo-nombre="<?php echo htmlspecialchars($tiposContrato[$des['tipo_contrato']] ?? $des['tipo_contrato'], ENT_QUOTES); ?>"
                          data-lotes="<?php echo htmlspecialchars($des['lotes_disponibles'] ?? '', ENT_QUOTES); ?>">
                    <?php echo htmlspecialchars($des['nombre']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Superficie</label>
              <input type="text" class="form-control" id="crearSuperficie" name="contrato_superficie" readonly>
              <!-- Campo oculto para almacenar la superficie convertida a letras -->
              <input type="hidden" name="superficie_fixed" id="crearSuperficieFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Tipo de contrato</label>
              <input type="hidden" name="tipo_contrato" id="crearTipoId">
              <input type="text" class="form-control" id="crearTipoNombre" readonly>
            </div>
          </div>

          <!-- Sección de datos del contrato -->
          <h5 class="mb-3">Datos del contrato</h5>
          <div class="row g-3 p-3" style="background:#f8f9fa;border-radius:6px;">
            <div class="col-md-6">
              <label class="form-label">Folio</label>
              <input type="text" class="form-control text-uppercase" name="folio" required oninput="this.value = this.value.toUpperCase();">
            </div>
            <div class="col-md-6">
              <label class="form-label">Mensualidades</label>
              <input type="number" class="form-control" name="mensualidades" min="1" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Fracción vendida/cedida</label>
              <input type="text" class="form-control" id="inputFraccionCrear" placeholder="Ingresa una fracción y presiona Enter">
              <div id="contenedorFraccionesCrear" class="mt-2"></div>
              <label class="form-label mt-2">Lotes disponibles:</label>
              <div id="listaFraccionesDisponiblesCrear" class="mt-1" style="font-size:0.8rem;"></div>
              <input type="hidden" name="fracciones" id="hiddenFraccionesCrear">
            </div>
            <div class="col-md-6">
              <label class="form-label">Entrega de posesión</label>
              <input type="date" class="form-control" name="entrega_posecion" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha de firma del contrato</label>
              <input type="date" class="form-control" name="fecha_firma" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Inicio de pagos</label>
              <input type="date" class="form-control" name="inicio_pagos" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Habitacional y colindancias</label>
              <!-- Campo de texto simple. Se almacenará en mayúsculas -->
              <textarea class="form-control text-uppercase" name="habitacional" id="crearHabitacional" rows="3" oninput="this.value = this.value.toUpperCase();"></textarea>
            </div>
            <!-- Rango de pago (inicio y fin) -->
            <div class="col-md-6">
              <label class="form-label">Rango de pago (de - a)</label>
              <div class="d-flex align-items-center">
                <input type="date" class="form-control" name="rango_pago_inicio" id="rangoPagoInicio" required>
                <span class="mx-2">a</span>
                <input type="date" class="form-control" name="rango_pago_fin" id="rangoPagoFin" required>
              </div>
            </div>
            <!-- Campos financieros existentes -->
            <div class="col-md-6">
              <label class="form-label">Monto del precio del inmueble</label>
              <input type="number" step="0.01" class="form-control" name="monto_inmueble" id="crearMontoInmueble" required>
              <input type="hidden" name="monto_inmueble_fixed" id="crearMontoInmuebleFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Enganche o pago inicial</label>
              <input type="number" step="0.01" class="form-control" name="enganche" id="crearEnganche" required>
              <input type="hidden" name="enganche_fixed" id="crearEngancheFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Saldo de pago</label>
              <input type="number" step="0.01" class="form-control" name="saldo_pago" id="crearSaldoPago" readonly required>
              <input type="hidden" name="saldo_pago_fixed" id="crearSaldoPagoFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Parcialidades anuales</label>
              <input type="text" class="form-control" name="parcialidades_anuales" id="crearParcialidadesAnuales">
            </div>

            <!-- Campo para fecha del contrato y su versión fija -->
            <div class="col-md-6">
              <label class="form-label">Fecha del contrato</label>
              <input type="date" class="form-control" name="fecha_contrato" id="crearFechaContrato">
              <input type="hidden" name="fecha_contrato_fixed" id="crearFechaContratoFixed">
              <!-- Día de inicio (sólo número), calculado desde la fecha del contrato -->
              <input type="hidden" name="dia_inicio" id="crearDiaInicio">
            </div>

            <!-- Nuevo campo: pago mensual -->
            <div class="col-md-6">
              <label class="form-label">Pago mensual</label>
              <input type="number" step="0.01" class="form-control" name="pago_mensual" id="crearPagoMensual" required>
              <input type="hidden" name="pago_mensual_fixed" id="crearPagoMensualFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Penalización 10%</label>
              <input type="number" step="0.01" class="form-control" name="penalizacion" id="crearPenalizacion" readonly required>
              <input type="hidden" name="penalizacion_fixed" id="crearPenalizacionFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Vigencia del pagaré</label>
              <input type="date" class="form-control" name="vigencia_pagare" id="crearVigenciaPagare">
            </div>
          </div>
          <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Guardar contrato</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>