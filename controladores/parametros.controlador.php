<?php
/**
 * Controlador para la gestión de parámetros y plantillas.
 * Incluye CRUD para variables (nacionalidades, tipos de contrato) y subida de plantillas.
 */



class ControladorParametros
{
    static public function ctrGenerarIdentificador() {
        // Prefijo con fecha actual + número aleatorio de 6 dígitos
        $identificadorID = 'ID-' . date('Ymd') . '-' . mt_rand(100000, 999999);

        return $identificadorID;
    }
    
    /**
     * Procesa el envío del formulario para agregar una variable. Debe venir con
     * los campos 'tipo', 'identificador' y 'nombre'.
     */
    static public function ctrAgregarVariable()
    {

        
        if (!isset($_POST['agregarVariable'])) {
            return;
        }
        // Comprobar permisos: sólo admin o moderator
        if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok' || !in_array($_SESSION['permission'], ['admin','moderator'])) {
            echo 'error-permiso';
            return;
        }
        // Validar token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            echo 'error-token';
            return;
        }
        $tipo = trim($_POST['tipo']);
        $nombre = trim($_POST['nombre']);
        $identificador = $identificadorID = self::ctrGenerarIdentificador();
        if ($tipo && $identificador && $nombre) {
            $datos = [
                'tipo' => $tipo,
                'identificador' => $identificador,
                'nombre' => $nombre
            ];
            $resp = ModeloVariables::mdlAgregarVariable($datos);
            echo $resp;
        } else {
            echo 'error';
        }
    }

    /**
     * Procesa la edición de una variable existente. Se envía desde un formulario
     * con 'editarVariable' y contiene 'id', 'identificador' y 'nombre'.
     */
    static public function ctrEditarVariable()
    {
        if (!isset($_POST['editarVariable'])) {
            return;
        }
        if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok' || !in_array($_SESSION['permission'], ['admin','moderator'])) {
            echo 'error-permiso';
            return;
        }
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            echo 'error-token';
            return;
        }
        $id = intval($_POST['id']);
        $identificador = trim($_POST['identificador']);
        $nombre = trim($_POST['nombre']);
        $datos = [
            'id' => $id,
            'identificador' => $identificador,
            'nombre' => $nombre
        ];
        $resp = ModeloVariables::mdlEditarVariable($datos);
        echo $resp;
    }

    /**
     * Devuelve un listado de variables para un tipo.
     */
    static public function ctrMostrarVariables($tipo)
    {
        return ModeloVariables::mdlMostrarVariables($tipo);
    }

    /**
     * Procesa la subida de una plantilla. Debe enviarse con un archivo
     * 'plantilla' y un 'tipo_contrato_id'.
     */
    static public function ctrSubirPlantilla()
    {
        if (!isset($_POST['subirPlantilla'])) {
            return;
        }
        if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok' || !in_array($_SESSION['permission'], ['admin','moderator'])) {
            echo 'error-permiso';
            return;
        }
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            echo 'error-token';
            return;
        }
        $tipoId = intval($_POST['tipo_contrato_id']);
        if (!$tipoId) {
            echo 'error';
            return;
        }
        // Comprobar archivo
        if (!isset($_FILES['plantilla']) || $_FILES['plantilla']['error'] !== UPLOAD_ERR_OK) {
            echo 'error-archivo';
            return;
        }
        $file = $_FILES['plantilla'];
        // Validar tamaño (máx. 150MB)
        if ($file['size'] > 150 * 1024 * 1024) {
            echo 'error-tamano';
            return;
        }
        // Validar extensión
        $allowedExt = ['docx','pdf'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) {
            echo 'error-extension';
            return;
        }
        // Crear directorio de plantillas si no existe
        $uploadDir = __DIR__ . '/../vistas/plantillas';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        // Nombre único para el archivo
        $nuevoNombre = uniqid('tpl_') . '.' . $ext;
        $rutaRelativa = 'vistas/plantillas/' . $nuevoNombre;
        $destino = __DIR__ . '/../' . $rutaRelativa;
        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            echo 'error-guardar';
            return;
        }
        $datos = [
            'tipo_contrato_id' => $tipoId,
            'nombre_archivo' => $file['name'],
            'ruta_archivo' => $rutaRelativa
        ];
        $resp = ModeloPlantillas::mdlAgregarPlantilla($datos);
        echo $resp;
    }

    /**
     * Procesa la edición de una plantilla existente. Permite reemplazar el archivo
     * subido y cambiar el tipo de contrato asociado. Requiere permisos de
     * administrador o moderador.
     */
    static public function ctrEditarPlantilla()
    {
        if (!isset($_POST['editarPlantilla'])) {
            return;
        }
        if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok' || !in_array($_SESSION['permission'], ['admin','moderator'])) {
            echo 'error-permiso';
            return;
        }
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            echo 'error-token';
            return;
        }
        $id = intval($_POST['plantilla_id'] ?? 0);
        $tipoId = intval($_POST['tipo_contrato_id'] ?? 0);
        if (!$id || !$tipoId) {
            echo 'error';
            return;
        }
        // Recuperar plantilla actual para eliminar archivo anterior si es reemplazado
        $plantillaActual = null;
        $todas = ModeloPlantillas::mdlMostrarPlantillas();
        foreach ($todas as $tpl) {
            if ((int)$tpl['id'] === $id) {
                $plantillaActual = $tpl;
                break;
            }
        }
        // Manejar archivo nuevo si se sube
        $nuevoNombre = $plantillaActual['nombre_archivo'] ?? '';
        $rutaRelativa = $plantillaActual['ruta_archivo'] ?? '';
        if (isset($_FILES['plantilla']) && $_FILES['plantilla']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['plantilla'];
            // Validar tamaño y extensión
            if ($file['size'] > 150 * 1024 * 1024) {
                echo 'error-tamano';
                return;
            }
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['docx','pdf'])) {
                echo 'error-extension';
                return;
            }
            // Directorio de plantillas
            $uploadDir = __DIR__ . '/../vistas/plantillas';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $nuevoArchivoNombre = uniqid('tpl_') . '.' . $ext;
            $rutaRel = 'vistas/plantillas/' . $nuevoArchivoNombre;
            $destino = __DIR__ . '/../' . $rutaRel;
            if (!move_uploaded_file($file['tmp_name'], $destino)) {
                echo 'error-guardar';
                return;
            }
            // Eliminar archivo anterior si existe
            if ($plantillaActual && !empty($plantillaActual['ruta_archivo'])) {
                $oldPath = __DIR__ . '/../' . $plantillaActual['ruta_archivo'];
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $nuevoNombre = $file['name'];
            $rutaRelativa = $rutaRel;
        }
        // Construir datos para actualizar
        $datos = [
            'id' => $id,
            'tipo_contrato_id' => $tipoId,
            'nombre_archivo' => $nuevoNombre,
            'ruta_archivo' => $rutaRelativa
        ];
        $resp = ModeloPlantillas::mdlEditarPlantilla($datos);
        echo $resp;
    }

    /**
     * Procesa la eliminación de una plantilla. Sólo para administradores y
     * moderadores. Elimina el registro en la base de datos y borra el archivo
     * asociado.
     */
    static public function ctrEliminarPlantilla()
    {
        if (!isset($_POST['eliminarPlantilla'])) {
            return;
        }
        if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok' || !in_array($_SESSION['permission'], ['admin','moderator'])) {
            echo 'error-permiso';
            return;
        }
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            echo 'error-token';
            return;
        }
        $id = intval($_POST['plantilla_id'] ?? 0);
        if (!$id) {
            echo 'error';
            return;
        }
        // Obtener plantilla actual para eliminar archivo
        $plantillaActual = null;
        $todas = ModeloPlantillas::mdlMostrarPlantillas();
        foreach ($todas as $tpl) {
            if ((int)$tpl['id'] === $id) {
                $plantillaActual = $tpl;
                break;
            }
        }
        // Eliminar registro
        $resp = ModeloPlantillas::mdlEliminarPlantilla($id);
        if ($resp === 'ok' && $plantillaActual && !empty($plantillaActual['ruta_archivo'])) {
            $ruta = __DIR__ . '/../' . $plantillaActual['ruta_archivo'];
            if (is_file($ruta)) {
                @unlink($ruta);
            }
        }
        echo $resp;
    }

    /**
     * Devuelve todas las plantillas con su tipo de contrato.
     *
     * @return array
     */
    static public function ctrMostrarPlantillas()
    {
        return ModeloPlantillas::mdlMostrarPlantillas();
    }
}