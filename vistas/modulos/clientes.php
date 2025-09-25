<?php
/**
 * Módulo de lista de clientes.
 */
$clientes = null;
// Procesar edición si llega formulario
ControladorClientes::ctrEditarCliente();
// Procesar creación de contrato si llega formulario
ControladorContratos::ctrCrearContrato();
// Obtener listado de clientes
$clientes = ControladorClientes::ctrMostrarClientes();
// Obtener lista de desarrollos para el formulario de contrato
$desarrollosDisponibles = ControladorDesarrollos::ctrMostrarDesarrollos();

// Obtener lista de nacionalidades
$listaNacionalidades = [];
if (class_exists('ControladorParametros')) {
    $listaNacionalidades = ControladorParametros::ctrMostrarVariables('nacionalidad');
}
// Construir mapa identificador -> nombre para nacionalidades
$mapNacionalidades = [];
foreach ($listaNacionalidades as $nac) {
    $mapNacionalidades[$nac['identificador']] = $nac['nombre'];
}

// Obtener lista de tipos de contrato para mapear identificador a nombre (usado en el select de desarrollos)
$listaTiposContrato = [];
if (class_exists('ControladorParametros')) {
    $varsTipo = ControladorParametros::ctrMostrarVariables('tipo_contrato');
    foreach ($varsTipo as $var) {
        $listaTiposContrato[$var['identificador']] = $var['nombre'];
    }
}

$hmacSecret = getenv('HMAC_SECRET');


?>
<section class="content-header">
  <div class="container-fluid">
    <h1>Clientes</h1>
    
  </div>
</section>

<!-- Modal ver cliente -->
<div class="modal fade" id="modalVerCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle del cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" id="verNombreCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nacionalidad</label>
            <input type="text" class="form-control" id="verNacionalidadCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Fecha de nacimiento</label>
            <input type="date" class="form-control" id="verFechaCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">RFC</label>
            <input type="text" class="form-control" id="verRfcCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">CURP</label>
            <input type="text" class="form-control" id="verCurpCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">INE (IDMEX)</label>
            <input type="text" class="form-control" id="verIneCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Estado civil y régimen matrimonial</label>
            <input type="text" class="form-control" id="verEstadoCivilCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Ocupación</label>
            <input type="text" class="form-control" id="verOcupacionCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="verTelefonoCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Domicilio</label>
            <input type="text" class="form-control" id="verDomicilioCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Correo electrónico</label>
            <input type="text" class="form-control" id="verEmailCliente" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Beneficiario</label>
            <input type="text" class="form-control" id="verBeneficiarioCliente" readonly>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal editar cliente -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="formEditarCliente" method="post" action="index.php?ruta=clientes&accion=editarCliente">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="id_cliente" id="editarIdCliente">
        <div class="modal-header">
          <h5 class="modal-title">Editar cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" name="nombre" id="editarNombreCliente" maxlength="50" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]{1,50}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nacionalidad</label>
              <select class="form-select" name="nacionalidad" id="editarNacionalidadCliente" required>
                <?php if (!empty($listaNacionalidades)) : ?>
                  <?php foreach ($listaNacionalidades as $nac) : ?>
                    <option value="<?php echo htmlspecialchars($nac['identificador'], ENT_QUOTES); ?>">
                      <?php echo htmlspecialchars($nac['nombre']); ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha de nacimiento</label>
              <input type="date" class="form-control" name="fecha_nacimiento" id="editarFechaCliente" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">RFC</label>
              <input type="text" class="form-control" name="rfc" id="editarRfcCliente" pattern="^[A-Za-zñÑ&]{3,4}\d{6}\w{3}$" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">CURP</label>
              <input type="text" class="form-control" name="curp" id="editarCurpCliente" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">INE (IDMEX)</label>
              <input type="text" class="form-control" name="ine" id="editarIneCliente" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Estado civil y régimen matrimonial</label>
              <input type="text" class="form-control" name="estado_civil" id="editarEstadoCivilCliente" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Ocupación</label>
              <input type="text" class="form-control" name="ocupacion" id="editarOcupacionCliente" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input type="tel" class="form-control" name="telefono" id="editarTelefonoCliente" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Domicilio</label>
              <input type="text" class="form-control" name="domicilio" id="editarDomicilioCliente" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" name="email" id="editarEmailCliente" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Beneficiario</label>
              <input type="text" class="form-control" name="beneficiario" id="editarBeneficiarioCliente" required>
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


<!-- Modal Crear Contrato desde clientes.php -->
<div class="modal fade" id="modalCrearContrato" tabindex="-1" aria-labelledby="modalCrearContratoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <form id="formCrearContratoCompleto" method="post" action="index.php?ruta=contratos">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="modalCrearContratoLabel">Crear nuevo contrato</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <?php
          if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
          }
          ?>
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
          <input type="hidden" name="crearContratoCompleto" value="1">
          <!-- Campo obligatorio: ID del cliente -->
          <input type="hidden" name="cliente_id" id="cliente_id_modal">

          <!-- ================= DATOS DEL DESARROLLO ================= -->
          <h5 class="mb-3">Datos del desarrollo</h5>
          <div class="row g-3 mb-4 p-3 bg-light rounded">
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
              <input type="hidden" name="superficie_fixed" id="crearSuperficieFixed">
            </div>
            <div class="col-md-6">
              <label class="form-label">Plantilla del contrato</label>
              <input type="hidden" name="tipo_contrato" id="crearTipoId">
              <input type="text" class="form-control" id="crearTipoNombre" readonly>
            </div>
          </div>

          <!-- ================= DATOS DEL CONTRATO ================= -->
          <h5 class="mb-3">Datos del contrato</h5>
          <div class="row g-3 p-3 bg-light rounded">
            <div class="col-md-3">
              <label class="form-label">Folio</label>
              <input type="text" class="form-control text-uppercase" name="folio" required oninput="this.value=this.value.toUpperCase();">
            </div>
            <div class="col-md-3">
              <label class="form-label">Fecha del contrato</label>
              <input type="date" class="form-control" name="fecha_contrato" id="crearFechaContrato">
              <input type="hidden" name="fecha_contrato_fixed" id="crearFechaContratoFixed">
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
              <textarea class="form-control text-uppercase" name="habitacional" id="crearHabitacional" rows="4" oninput="this.value=this.value.toUpperCase();"></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha de la posesión</label>
              <input type="date" class="form-control" name="entrega_posecion" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Plazo del financiamiento</label>
              <div class="d-flex align-items-center">
                <input type="date" class="form-control" name="rango_pago_inicio" id="rangoPagoInicio" required>
                <span class="mx-2">a</span>
                <input type="date" class="form-control" name="rango_pago_fin" id="rangoPagoFin" required>
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label">Meses del financiamiento</label>
              <input type="number" class="form-control" name="mensualidades" min="1" placeholder="ej. 6" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Años del financiamiento</label>
              <input type="text" class="form-control" name="rango_pago" id="crearRangoPago" placeholder="ej. 1 AÑO, 18 MESES" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Parcialidades anuales</label>
              <input type="text" class="form-control" name="parcialidades_anuales" id="crearParcialidadesAnuales" placeholder="SIN PARCIALIDADES">
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
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar contrato</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Tabla de clientes -->
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Listado</h3></div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover" id="tablaClientes">
          <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Acciones</th></tr></thead>
          <tbody>
            <?php foreach ($clientes as $cli) : ?>
            <tr>
              <td><?php echo $cli['id']; ?></td>
              <td>
                <!-- Nombre clickable para ver detalles -->
                <a href="#" class="verClienteNombre"
                   data-bs-toggle="modal" data-bs-target="#modalVerCliente"
                   data-id="<?php echo $cli['id']; ?>"
                   data-nombre="<?php echo htmlspecialchars($cli['nombre'], ENT_QUOTES); ?>"
                   data-nacionalidad-id="<?php echo htmlspecialchars($cli['nacionalidad'], ENT_QUOTES); ?>"
                   data-nacionalidad-nombre="<?php echo htmlspecialchars($mapNacionalidades[$cli['nacionalidad']] ?? $cli['nacionalidad'], ENT_QUOTES); ?>"
                   data-fecha="<?php echo htmlspecialchars($cli['fecha_nacimiento'], ENT_QUOTES); ?>"
                   data-rfc="<?php echo htmlspecialchars($cli['rfc'], ENT_QUOTES); ?>"
                   data-curp="<?php echo htmlspecialchars($cli['curp'], ENT_QUOTES); ?>"
                   data-ine="<?php echo htmlspecialchars($cli['ine'], ENT_QUOTES); ?>"
                   data-estado_civil="<?php echo htmlspecialchars($cli['estado_civil'], ENT_QUOTES); ?>"
                   data-ocupacion="<?php echo htmlspecialchars($cli['ocupacion'], ENT_QUOTES); ?>"
                   data-telefono="<?php echo htmlspecialchars($cli['telefono'], ENT_QUOTES); ?>"
                   data-domicilio="<?php echo htmlspecialchars($cli['domicilio'], ENT_QUOTES); ?>"
                   data-email="<?php echo htmlspecialchars($cli['email'], ENT_QUOTES); ?>"
                   data-beneficiario="<?php echo htmlspecialchars($cli['beneficiario'], ENT_QUOTES); ?>"
                ><?php echo htmlspecialchars($cli['nombre']); ?></a>
              </td>
              <td><?php echo htmlspecialchars($cli['email']); ?></td>
              <td>
                <!-- Botón ver -->
                <button type="button" class="btn btn-warning btn-sm btnVerCliente" data-bs-toggle="modal" data-bs-target="#modalVerCliente"
                  data-id="<?php echo $cli['id']; ?>"
                  data-nombre="<?php echo htmlspecialchars($cli['nombre'], ENT_QUOTES); ?>"
                  data-nacionalidad-id="<?php echo htmlspecialchars($cli['nacionalidad'], ENT_QUOTES); ?>"
                  data-nacionalidad-nombre="<?php echo htmlspecialchars($mapNacionalidades[$cli['nacionalidad']] ?? $cli['nacionalidad'], ENT_QUOTES); ?>"
                  data-fecha="<?php echo htmlspecialchars($cli['fecha_nacimiento'], ENT_QUOTES); ?>"
                  data-rfc="<?php echo htmlspecialchars($cli['rfc'], ENT_QUOTES); ?>"
                  data-curp="<?php echo htmlspecialchars($cli['curp'], ENT_QUOTES); ?>"
                  data-ine="<?php echo htmlspecialchars($cli['ine'], ENT_QUOTES); ?>"
                  data-estado_civil="<?php echo htmlspecialchars($cli['estado_civil'], ENT_QUOTES); ?>"
                  data-ocupacion="<?php echo htmlspecialchars($cli['ocupacion'], ENT_QUOTES); ?>"
                  data-telefono="<?php echo htmlspecialchars($cli['telefono'], ENT_QUOTES); ?>"
                  data-domicilio="<?php echo htmlspecialchars($cli['domicilio'], ENT_QUOTES); ?>"
                  data-email="<?php echo htmlspecialchars($cli['email'], ENT_QUOTES); ?>"
                  data-beneficiario="<?php echo htmlspecialchars($cli['beneficiario'], ENT_QUOTES); ?>">
                  <i class="fas fa-eye"></i>
                </button>
                <!-- Botón editar -->
                <button type="button" class="btn btn-primary btn-sm btnEditarCliente" data-bs-toggle="modal" data-bs-target="#modalEditarCliente"
                  data-id="<?php echo $cli['id']; ?>"
                  data-nombre="<?php echo htmlspecialchars($cli['nombre'], ENT_QUOTES); ?>"
                  data-nacionalidad-id="<?php echo htmlspecialchars($cli['nacionalidad'], ENT_QUOTES); ?>"
                  data-nacionalidad-nombre="<?php echo htmlspecialchars($mapNacionalidades[$cli['nacionalidad']] ?? $cli['nacionalidad'], ENT_QUOTES); ?>"
                  data-fecha="<?php echo htmlspecialchars($cli['fecha_nacimiento'], ENT_QUOTES); ?>"
                  data-rfc="<?php echo htmlspecialchars($cli['rfc'], ENT_QUOTES); ?>"
                  data-curp="<?php echo htmlspecialchars($cli['curp'], ENT_QUOTES); ?>"
                  data-ine="<?php echo htmlspecialchars($cli['ine'], ENT_QUOTES); ?>"
                  data-estado_civil="<?php echo htmlspecialchars($cli['estado_civil'], ENT_QUOTES); ?>"
                  data-ocupacion="<?php echo htmlspecialchars($cli['ocupacion'], ENT_QUOTES); ?>"
                  data-telefono="<?php echo htmlspecialchars($cli['telefono'], ENT_QUOTES); ?>"
                  data-domicilio="<?php echo htmlspecialchars($cli['domicilio'], ENT_QUOTES); ?>"
                  data-email="<?php echo htmlspecialchars($cli['email'], ENT_QUOTES); ?>"
                  data-beneficiario="<?php echo htmlspecialchars($cli['beneficiario'], ENT_QUOTES); ?>">
                  <i class="fas fa-pencil-alt"></i>
                </button>
                <?php
                  // Obtener lista de contratos del cliente
                  $contratosCliente = ControladorContratos::ctrMostrarContratos($cli['id']);
                  $numContratos = count($contratosCliente);
                ?>
                
                <a href="index.php?ruta=crearContrato&cliente_id=<?php echo $cli['id']; ?>" 
                  class="btn btn-success btn-sm">
                  Crear contrato
                </a>                              
                <!-- Botón ver contratos (redirige a listado) -->
                <a href="index.php?ruta=contratos&cliente_id=<?php echo $cli['id']; ?>" class="btn btn-info btn-sm">
                  Ver contratos
                </a>
                
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>