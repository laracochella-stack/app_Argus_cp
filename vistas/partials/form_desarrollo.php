<!-- Sección de datos del desarrollo -->
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
  <input type="text" class="form-control number" id="crearSuperficie" name="contrato_superficie" placeholder="TAMAÑO DE LA FRACCIÓN" required>
  <!-- Campo oculto para almacenar la superficie convertida a letras -->
  <input type="hidden" name="superficie_fixed" id="crearSuperficieFixed">
</div>
<div class="col-md-6">
  <label class="form-label">Plantilla del contrato</label>
  <input type="hidden" name="tipo_contrato" id="crearTipoId">
  <input type="text" class="form-control" id="crearTipoNombre" readonly>
</div>
