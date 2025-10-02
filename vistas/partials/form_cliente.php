<!-- Sección de datos del cliente -->
<!-- CSS intl-tel-input (si ya lo cargas globalmente, puedes quitar esta línea) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"/>

<div class="col-md-9">
  <label class="form-label">Nombre completo</label>
  <input type="text" class="form-control text-uppercase" name="cliente_nombre" placeholder="ej. JUAN PÉREZ" required oninput="this.value = this.value.toUpperCase();">
</div>
<div class="col-md-3">
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
<div class="col-md-4">
  <label class="form-label">Fecha de nacimiento</label>
  <input type="date" class="form-control" name="cliente_fecha_nacimiento" id="clienteFechaNacimiento" required>
  <!-- Campo oculto para almacenar la edad calculada del cliente -->
  <input type="hidden" name="cliente_edad" id="clienteEdad">
</div>
<div class="col-md-4">
  <label class="form-label">RFC</label>
  <input type="text" class="form-control text-uppercase" name="cliente_rfc" required placeholder="ej. XEXX010101000" pattern="^[A-Za-zñÑ&]{3,4}\d{6}\w{3}$" oninput="this.value = this.value.toUpperCase();">
</div>
<div class="col-md-4">
  <label class="form-label">CURP</label>
  <input type="text" class="form-control text-uppercase" name="cliente_curp" required placeholder="ej. XEXX010101HNEXXXA4" oninput="this.value = this.value.toUpperCase();">
</div>
<div class="col-md-4">
  <label class="form-label">INE (IDMEX)</label>
  <input type="text" class="form-control text-uppercase number" name="cliente_ine" pattern="[0-9]*" maxlength="13" required placeholder="13 Digitos al reverso de la INE" oninput="this.value = this.value.toUpperCase();">
</div>
<div class="col-md-4">
  <label class="form-label">Estado civil y régimen matrimonial</label>
  <input type="text" class="form-control text-uppercase" name="cliente_estado_civil" required placeholder="ej. soltero" oninput="this.value = this.value.toUpperCase();">
</div>
<div class="col-md-4">
  <label class="form-label">Ocupación</label>
  <input type="text" class="form-control text-uppercase" name="cliente_ocupacion" required placeholder="ej. INGENIERO" oninput="this.value = this.value.toUpperCase();">
</div>

<div class="col-md-3">
  <label for="telefono_cliente" class="form-label">Teléfono</label>
  <input type="tel" class="form-control" id="telefono_cliente" required>
  <div class="invalid-feedback">Ingrese un número válido.</div>
</div>
<!-- Campo oculto donde se guardará el número final con código de país -->
<input type="hidden" name="cliente_telefono" id="cliente_telefono">

<div class="col-md-9">
  <label class="form-label">Domicilio</label>
  <input type="text" class="form-control text-uppercase" name="cliente_domicilio" required placeholder="ej. CALLE # COL" oninput="this.value = this.value.toUpperCase();">
</div>
<div class="col-md-6">
  <label class="form-label">Correo electrónico</label>
  <input type="email" class="form-control" name="cliente_email" placeholder="ej. micorreo@dominio.com" required>
</div>

<div class="input-group mb-3">
  <div class="col-md-12">
  <label class="form-label">Beneficiario</label>
  </div>
  <input type="text" class="form-control text-uppercase" name="cliente_beneficiario" required placeholder="ej. nombre (parentezco)" oninput="this.value = this.value.toUpperCase();">
  <span class="input-group-text">/</span>
  <input type="text" class="form-control" name="dice_ser" placeholder="DICE SER" oninput="this.value = this.value.toUpperCase();">
</div>


