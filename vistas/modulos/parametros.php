<?php
/**
 * Panel de parámetros generales para administradores y moderadores.
 * Permite gestionar nacionalidades, tipos de contrato y subir plantillas.
 */

// Procesar formularios
ControladorParametros::ctrAgregarVariable();
ControladorParametros::ctrEditarVariable();
ControladorParametros::ctrSubirPlantilla();
ControladorParametros::ctrEditarPlantilla();
ControladorParametros::ctrEliminarPlantilla();

// Obtener variables
$nacionalidades = ControladorParametros::ctrMostrarVariables('nacionalidad');
$tiposContrato = ControladorParametros::ctrMostrarVariables('tipo_contrato');
$plantillas = ControladorParametros::ctrMostrarPlantillas();
?>
<section class="content-header">
  <div class="container-fluid">
    <h1>Parámetros Generales</h1>
  </div>
</section>
<section class="content">
  <div class="container-fluid">
    <!-- Nacionalidades -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white"><h5 class="card-title mb-0">Nacionalidades</h5></div>
      <div class="card-body">
        <form id="formAddNacionalidad" method="post" class="mb-3">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="tipo" value="nacionalidad">
          <input type="hidden" name="agregarVariable" value="1">
          <div class="row g-2 align-items-end">
            <div class="col-md-4">
              <label class="form-label">Identificador</label>
              <input type="text" name="identificador" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-4">
              <button type="submit" class="btn btn-success">Agregar</button>
            </div>
          </div>
        </form>
        <table class="table table-hover" id="tablaNacionalidades">
          <thead>
            <tr><th>ID</th><th>Identificador</th><th>Nombre</th><th>Acciones</th></tr>
          </thead>
          <tbody>
            <?php foreach ($nacionalidades as $nac) : ?>
            <tr>
              <td><?php echo $nac['id']; ?></td>
              <td><?php echo htmlspecialchars($nac['identificador']); ?></td>
              <td><?php echo htmlspecialchars($nac['nombre']); ?></td>
              <td>
                <button type="button" class="btn btn-primary btn-sm btnEditarVariable" data-bs-toggle="modal" data-bs-target="#modalEditarVariable"
                  data-id="<?php echo $nac['id']; ?>" data-identificador="<?php echo htmlspecialchars($nac['identificador'], ENT_QUOTES); ?>" data-nombre="<?php echo htmlspecialchars($nac['nombre'], ENT_QUOTES); ?>">
                  <i class="fas fa-pencil-alt"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Tipos de Contrato -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white"><h5 class="card-title mb-0">Tipos de Contrato</h5></div>
      <div class="card-body">
        <form id="formAddTipo" method="post" class="mb-3">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="tipo" value="tipo_contrato">
          <input type="hidden" name="agregarVariable" value="1">
          <div class="row g-2 align-items-end">
            <div class="col-md-4">
              <label class="form-label">Identificador</label>
              <input type="text" name="identificador" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-4">
              <button type="submit" class="btn btn-success">Agregar</button>
            </div>
          </div>
        </form>
        <table class="table table-hover" id="tablaTipos">
          <thead>
            <tr><th>ID</th><th>Identificador</th><th>Nombre</th><th>Acciones</th></tr>
          </thead>
          <tbody>
            <?php foreach ($tiposContrato as $tip) : ?>
            <tr>
              <td><?php echo $tip['id']; ?></td>
              <td><?php echo htmlspecialchars($tip['identificador']); ?></td>
              <td><?php echo htmlspecialchars($tip['nombre']); ?></td>
              <td>
                <button type="button" class="btn btn-primary btn-sm btnEditarVariable" data-bs-toggle="modal" data-bs-target="#modalEditarVariable"
                  data-id="<?php echo $tip['id']; ?>" data-identificador="<?php echo htmlspecialchars($tip['identificador'], ENT_QUOTES); ?>" data-nombre="<?php echo htmlspecialchars($tip['nombre'], ENT_QUOTES); ?>">
                  <i class="fas fa-pencil-alt"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Plantillas -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white"><h5 class="card-title mb-0">Plantillas de Contrato</h5></div>
      <div class="card-body">
        <form id="formSubirPlantilla" method="post" enctype="multipart/form-data" class="mb-3">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="subirPlantilla" value="1">
          <div class="row g-2 align-items-end">
            <div class="col-md-4">
              <label class="form-label">Tipo de contrato</label>
              <select name="tipo_contrato_id" class="form-select" required>
                <option value="">Seleccione tipo</option>
                <?php foreach ($tiposContrato as $tip) : ?>
                  <option value="<?php echo $tip['id']; ?>"><?php echo htmlspecialchars($tip['nombre']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Archivo (docx o pdf)</label>
              <input type="file" name="plantilla" class="form-control" accept=".docx,.pdf" required>
              <small class="text-muted">Máximo 150 MB</small>
            </div>
            <div class="col-md-4">
              <button type="submit" class="btn btn-success">Subir plantilla</button>
            </div>
          </div>
        </form>
        <table class="table table-hover" id="tablaPlantillas">
          <thead>
            <tr><th>ID</th><th>Tipo</th><th>Nombre original</th><th>Archivo</th><th>Acciones</th></tr>
          </thead>
          <tbody>
            <?php foreach ($plantillas as $pl) : ?>
            <tr>
              <td><?php echo $pl['id']; ?></td>
              <td><?php echo htmlspecialchars($pl['nombre_tipo'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($pl['nombre_archivo']); ?></td>
              <td><a href="<?php echo htmlspecialchars($pl['ruta_archivo']); ?>" target="_blank">Descargar</a></td>
              <td>
                <?php if (isset($_SESSION['iniciarSesion']) && $_SESSION['iniciarSesion'] === 'ok' && in_array($_SESSION['permission'], ['admin','moderator'])) : ?>
                <!-- Botón editar plantilla -->
                <button type="button" class="btn btn-primary btn-sm btnEditarPlantilla" data-bs-toggle="modal" data-bs-target="#modalEditarPlantilla"
                  data-id="<?php echo $pl['id']; ?>" data-tipo-id="<?php echo $pl['tipo_contrato_id']; ?>" data-nombre="<?php echo htmlspecialchars($pl['nombre_archivo'], ENT_QUOTES); ?>">
                  <i class="fas fa-pencil-alt"></i>
                </button>
                <!-- Formulario de eliminación -->
                <form method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta plantilla?');">
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                  <input type="hidden" name="eliminarPlantilla" value="1">
                  <input type="hidden" name="plantilla_id" value="<?php echo $pl['id']; ?>">
                  <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                </form>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Modal editar variable -->
  <div class="modal fade" id="modalEditarVariable" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="formEditarVariable" method="post">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="editarVariable" value="1">
          <input type="hidden" name="id" id="editarVariableId">
          <div class="modal-header">
            <h5 class="modal-title">Editar variable</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Identificador</label>
              <input type="text" name="identificador" id="editarVariableIdentificador" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" id="editarVariableNombre" class="form-control" required>
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

  <!-- Modal editar plantilla -->
  <div class="modal fade" id="modalEditarPlantilla" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="formEditarPlantilla" method="post" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <input type="hidden" name="editarPlantilla" value="1">
          <input type="hidden" name="plantilla_id" id="editarPlantillaId">
          <div class="modal-header">
            <h5 class="modal-title">Editar plantilla</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Tipo de contrato</label>
              <select name="tipo_contrato_id" id="editarPlantillaTipo" class="form-select" required>
                <?php foreach ($tiposContrato as $t) : ?>
                  <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['nombre']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Reemplazar archivo (opcional)</label>
              <input type="file" name="plantilla" class="form-control" accept=".docx,.pdf">
              <small class="text-muted">Dejar en blanco para conservar el archivo actual. Tamaño máximo 150 MB.</small>
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