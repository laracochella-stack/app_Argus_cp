<?php
/**
 * Controlador para la gestión de parámetros y plantillas.
 * Incluye CRUD para variables (nacionalidades, tipos de contrato) y subida de plantillas.
 */
class ControladorParametros
{
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
        $identificador = trim($_POST['identificador']);
        $nombre = trim($_POST['nombre']);
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
     * Devuelve todas las plantillas con su tipo de contrato.
     *
     * @return array
     */
    static public function ctrMostrarPlantillas()
    {
        return ModeloPlantillas::mdlMostrarPlantillas();
    }
}