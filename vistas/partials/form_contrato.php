<!-- Sección de datos del contrato -->
<div class="col-md-3">
  <label class="form-label">Folio</label>
  <input type="text" class="form-control text-uppercase" name="folio" required oninput="this.value = this.value.toUpperCase();">
</div>

<!-- Campo para fecha del contrato y su versión fija -->
<div class="col-md-3">
  <label class="form-label">Fecha del contrato</label>
  <input type="date" class="form-control" name="fecha_contrato" id="crearFechaContrato">
  <input type="hidden" name="fecha_contrato_fixed" id="crearFechaContratoFixed">
  <!-- Día de inicio (sólo número), calculado desde la fecha del contrato -->
  <input type="hidden" name="dia_inicio" id="crearDiaInicio">
</div>

<div class="col-md-6">
  <label class="form-label">Fracción vendida/cedida</label>
  <input type="text" class="form-control" id="inputFraccionCrear" placeholder="Ingresa una fracción y presiona Enter">
  <div id="contenedorFraccionesCrear" class="mt-2"></div>
  <label class="form-label mt-2">Lotes disponibles:</label>
  <div id="listaFraccionesDisponiblesCrear" class="mt-1" style="font-size:0.8rem;"></div>
  <input type="hidden" name="fracciones" id="hiddenFraccionesCrear">
</div>

<div class="col-md-12">
  <label class="form-label">Habitacional y colindancias</label>
  <!-- Campo de texto simple. Se almacenará en mayúsculas -->
  <textarea class="form-control text-uppercase" name="habitacional" id="crearHabitacional" rows="4" oninput="this.value = this.value.toUpperCase();"></textarea>
</div>

<div class="col-md-6">
  <label class="form-label">Fecha de la posesión</label>
  <input type="date" class="form-control" name="entrega_posecion" required>
</div>

<!-- Rango de pago (inicio y fin) -->
<div class="col-md-6">
  <label class="form-label">Plazo del financiamiento</label>
  <div class="d-flex align-items-center">
    <input type="date" class="form-control" name="rango_pago_inicio" id="rangoPagoInicio" required>
    <span class="mx-2">a</span>
    <input type="date" class="form-control" name="rango_pago_fin" id="rangoPagoFin" required>
  </div>
</div>

<!-- Campos financieros existentes -->
<div class="col-md-3">
  <label class="form-label">Meses del financiamiento</label>
  <input type="number" class="form-control" name="mensualidades" min="1" placeholder="ej. 6" required>
</div>
<div class="col-md-3">
  <label class="form-label">Años del financiamiento</label>
  <input type="text" class="form-control" name="rango_pago" id="crearRangoPago" data-bs-toggle="tooltip" title="" placeholder="ej. 1 AÑO, 18 MESES" required>
</div>
<div class="col-md-3">
  <label class="form-label">Parcialidades anuales</label>
  <input type="text" placeholder="SIN PARCIALIDADES" class="form-control" name="parcialidades_anuales" id="crearParcialidadesAnuales">
</div>

<div class="col-md-3">
  <label class="form-label">Monto del precio del inmueble</label>
  <div class="input-group">
    <span class="input-group-text">$</span>
    <input type="number" step="0.01" class="form-control" name="monto_inmueble" id="crearMontoInmueble" required>
    <input type="hidden" name="monto_inmueble_fixed" id="crearMontoInmuebleFixed">
  </div>
</div>

<div class="col-md-3">
  <label class="form-label">Enganche o pago inicial</label>
  <div class="input-group">
    <span class="input-group-text">$</span>
    <input type="number" step="0.01" class="form-control" name="enganche" id="crearEnganche" required>
    <input type="hidden" name="enganche_fixed" id="crearEngancheFixed">
  </div>
</div>

<div class="col-md-3">
  <label class="form-label">Saldo de pago</label>
  <div class="input-group">
    <span class="input-group-text">$</span>
    <input type="number" step="0.01" class="form-control" name="saldo_pago" id="crearSaldoPago" readonly required>
    <input type="hidden" name="saldo_pago_fixed" id="crearSaldoPagoFixed">
  </div>
</div>

<!-- Nuevo campo: pago mensual -->
<div class="col-md-3">
  <label class="form-label">Pago mensual</label>
  <div class="input-group">
    <span class="input-group-text">$</span>
    <input type="number" step="0.01" class="form-control" name="pago_mensual" id="crearPagoMensual" required>
    <input type="hidden" name="pago_mensual_fixed" id="crearPagoMensualFixed">
  </div>
</div>

<div class="col-md-3">
  <label class="form-label">Penalización 10%</label>
  <div class="input-group">
    <span class="input-group-text">$</span>
    <input type="number" step="0.01" class="form-control" name="penalizacion" id="crearPenalizacion" readonly required>
    <input type="hidden" name="penalizacion_fixed" id="crearPenalizacionFixed">
  </div>
</div>

<!--
<div class="col-md-6">
  <label class="form-label">Vigencia del pagaré</label>
  <input type="date" class="form-control" name="vigencia_pagare" id="crearVigenciaPagare">
</div>
-->
