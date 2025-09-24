<?php
/**
 * Módulo de lista de desarrollos.
 * Muestra una tabla con los desarrollos registrados y permite editarlos mediante un modal.
 */
// Procesar edición de desarrollo si se envía el formulario
ControladorDesarrollos::ctrAgregarDesarrollo();
ControladorDesarrollos::ctrEditarDesarrollo();
// Obtener todos los desarrollos para listarlos
$tiposContratoList  = ControladorParametros::ctrMostrarVariables('tipo_contrato');
$desarrollos = ControladorDesarrollos::ctrMostrarDesarrollos();
// Obtener listado de tipos de contrato para mapear identificador a nombre
$listaTiposContrato = [];
if (class_exists('ControladorParametros')) {
    $varsTipo = ControladorParametros::ctrMostrarVariables('tipo_contrato');
    foreach ($varsTipo as $var) {
        $listaTiposContrato[$var['identificador']] = $var['nombre'];
    }
}
?>
<section class="content-header">
  <div class="container-fluid">
    <h1>Desarrollos</h1>
  </div>
  
</section>
<div class="col-lg-3 col-6">
      <div class="small-box bg-success" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#modalNuevoDesarrollo">
        <div class="inner"><h4>Crear desarrollo</h4></div>
        
      </div>
    </div>
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Listado</h3></div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover" id="tablaDesarrollos">
          <thead><tr><th>ID</th><th>Nombre</th><th>Tipo de contrato</th><th>Acciones</th></tr></thead>
          <tbody>
            <?php foreach ($desarrollos as $des) : ?>
            <tr>
              <td><?php echo $des['id']; ?></td>
              <td><?php echo htmlspecialchars($des['nombre']); ?></td>
              <td><?php echo htmlspecialchars($listaTiposContrato[$des['tipo_contrato']] ?? $des['tipo_contrato']); ?></td>
              <td>
                <!-- Botón ver -->
                <button type="button" class="btn btn-warning btn-sm btnVerDesarrollo" data-bs-toggle="modal" data-bs-target="#modalVerDesarrollo"
                    data-id="<?php echo $des['id']; ?>"
                    data-nombre="<?php echo htmlspecialchars($des['nombre'], ENT_QUOTES); ?>"
                    data-tipocontrato-id="<?php echo htmlspecialchars($des['tipo_contrato'], ENT_QUOTES); ?>"
                    data-tipocontrato-nombre="<?php echo htmlspecialchars($listaTiposContrato[$des['tipo_contrato']] ?? $des['tipo_contrato'], ENT_QUOTES); ?>"
                    data-descripcion="<?php echo htmlspecialchars($des['descripcion'], ENT_QUOTES); ?>"
                    data-superficie="<?php echo htmlspecialchars($des['superficie'], ENT_QUOTES); ?>"
                    data-clave="<?php echo htmlspecialchars($des['clave_catastral'], ENT_QUOTES); ?>"
                    data-lotes="<?php echo htmlspecialchars($des['lotes_disponibles'], ENT_QUOTES); ?>"
                    data-preciolote="<?php echo $des['precio_lote']; ?>"
                    data-preciototal="<?php echo $des['precio_total']; ?>">
                  <i class="fas fa-eye"></i>
                </button>
                <!-- Botón editar -->
                <button type="button" class="btn btn-primary btn-sm btnEditarDesarrollo" data-bs-toggle="modal" data-bs-target="#modalEditarDesarrollo"
                    data-id="<?php echo $des['id']; ?>"
                    data-nombre="<?php echo htmlspecialchars($des['nombre'], ENT_QUOTES); ?>"
                    data-tipocontrato-id="<?php echo htmlspecialchars($des['tipo_contrato'], ENT_QUOTES); ?>"
                    data-tipocontrato-nombre="<?php echo htmlspecialchars($listaTiposContrato[$des['tipo_contrato']] ?? $des['tipo_contrato'], ENT_QUOTES); ?>"
                    data-descripcion="<?php echo htmlspecialchars($des['descripcion'], ENT_QUOTES); ?>"
                    data-superficie="<?php echo htmlspecialchars($des['superficie'], ENT_QUOTES); ?>"
                    data-clave="<?php echo htmlspecialchars($des['clave_catastral'], ENT_QUOTES); ?>"
                    data-lotes="<?php echo htmlspecialchars($des['lotes_disponibles'], ENT_QUOTES); ?>"
                    data-preciolote="<?php echo $des['precio_lote']; ?>"
                    data-preciototal="<?php echo $des['precio_total']; ?>">
                  <i class="fas fa-pencil-alt"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- Modal editar desarrollo -->
<div class="modal fade" id="modalEditarDesarrollo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="formEditarDesarrollo" method="post" action="index.php?ruta=desarrollos&accion=editarDesarrollo">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="id" id="editarIdDesarrollo">
        <div class="modal-header">
          <h5 class="modal-title">Editar desarrollo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre del desarrollo</label>
              <input type="text" class="form-control" name="nombre_desarrollo" id="editarNombreDesarrollo" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tipo de contrato</label>
              <select class="form-select" name="tipo_contrato" id="editarTipoContrato" required>
                <?php foreach ($listaTiposContrato as $iden => $nom) : ?>
                  <option value="<?php echo htmlspecialchars($iden, ENT_QUOTES); ?>">
                    <?php echo htmlspecialchars($nom); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Ubicación y descripción</label>
              <textarea class="form-control" name="descripcion" id="editarDescripcion" rows="2" required></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Superficie</label>
              <input type="text" class="form-control" name="superficie" id="editarSuperficie" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Clave catastral</label>
              <input type="text" class="form-control" name="clave_catastral" id="editarClaveCatastral" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Lotes disponibles</label>
              <!-- Campo para ingresar nuevos lotes en edición. Escribe un lote y presiona Enter para agregarlo -->
              <input type="text" class="form-control" id="inputLoteEditar" placeholder="Ingresa un lote y presiona Enter">
              <div id="contenedorLotesEditar" class="mt-2"></div>
              <!-- Input oculto que contendrá el arreglo JSON con los lotes -->
              <input type="hidden" name="lotes_disponibles" id="lotesDisponiblesEditar">
            </div>
            <div class="col-md-6">
              <label class="form-label">Precio por lote</label>
              <!-- Prefijo de moneda para edición de desarrollos -->
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" name="precio_lote" id="editarPrecioLote" required>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Precio total</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="text" class="form-control" name="precio_total" id="editarPrecioTotal" required>
              </div>
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

<!-- Modal ver desarrollo (solo lectura) -->
<div class="modal fade" id="modalVerDesarrollo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle del desarrollo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nombre del desarrollo</label>
            <input type="text" class="form-control" id="verNombreDesarrollo" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tipo de contrato</label>
            <input type="text" class="form-control" id="verTipoContrato" readonly>
          </div>
          <div class="col-12">
            <label class="form-label">Ubicación y descripción</label>
            <textarea class="form-control" id="verDescripcion" rows="2" readonly></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Superficie</label>
            <input type="text" class="form-control" id="verSuperficie" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Clave catastral</label>
            <input type="text" class="form-control" id="verClaveCatastral" readonly>
          </div>
          <div class="col-12">
            <label class="form-label">Lotes disponibles</label>
            <div id="contenedorLotesVer" class="mt-2"></div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Precio por lote</label>
            <input type="text" class="form-control" id="verPrecioLote" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Precio total</label>
            <input type="text" class="form-control" id="verPrecioTotal" readonly>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
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