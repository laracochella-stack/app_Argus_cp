<?php
/**
 * Controlador de desarrollos (placeholder).
 */
require_once 'modelos/desarrollos.modelo.php';

class ControladorDesarrollos {
    /**
     * Registrar un nuevo desarrollo a partir de los datos recibidos por POST.
     * Esta función se ejecuta cuando se recibe el formulario del modal de
     * desarrollos en la ruta inicio con acción agregarDesarrollo.
     *
     * @return string|null Devuelve 'ok' en caso de éxito, 'error' en caso de fallo, 'error_csrf' si falla el token
     */
    public static function ctrAgregarDesarrollo() {
        // Comprobar que se haya enviado el formulario de desarrollo y que la acción sea agregarDesarrollo
        if (isset($_POST['nombre_desarrollo']) && isset($_GET['accion']) && $_GET['accion'] === 'agregarDesarrollo') {
            // Validar token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                return 'error_csrf';
            }
            // Verificar que el usuario tenga sesión iniciada (autenticado)
            if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok') {
                return 'error_permiso';
            }
            // Preparar datos del desarrollo
            $datos = [
                'nombre'            => trim($_POST['nombre_desarrollo']),
                'tipo_contrato'     => trim($_POST['tipo_contrato']),
                'descripcion'       => trim($_POST['descripcion']),
                'superficie'        => trim($_POST['superficie']),
                'clave_catastral'   => trim($_POST['clave_catastral']),
                // Guardamos los lotes disponibles como JSON. Si el campo no está definido, usar un array vacío.
                'lotes_disponibles' => isset($_POST['lotes_disponibles']) ? trim($_POST['lotes_disponibles']) : json_encode([]),
                'precio_lote'       => (float)$_POST['precio_lote'],
                'precio_total'      => (float)$_POST['precio_total']
            ];
            $respuesta = ModeloDesarrollos::mdlAgregarDesarrollo($datos);
            return $respuesta;
        }
        return null;
    }

    /**
     * Editar un desarrollo existente. Recibe los datos mediante POST con la acción editarDesarrollo.
     *
     * @return string|null
     */
    public static function ctrEditarDesarrollo() {
        if (isset($_POST['id']) && isset($_GET['accion']) && $_GET['accion'] === 'editarDesarrollo') {
            // Validar token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                return 'error_csrf';
            }
            // Verificar sesión iniciada
            if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok') {
                return 'error_permiso';
            }
            $datos = [
                'id'                => (int)$_POST['id'],
                'nombre'            => trim($_POST['nombre_desarrollo']),
                'tipo_contrato'     => trim($_POST['tipo_contrato']),
                'descripcion'       => trim($_POST['descripcion']),
                'superficie'        => trim($_POST['superficie']),
                'clave_catastral'   => trim($_POST['clave_catastral']),
                // Recibimos los lotes en formato JSON desde el formulario
                'lotes_disponibles' => isset($_POST['lotes_disponibles']) ? trim($_POST['lotes_disponibles']) : json_encode([]),
                'precio_lote'       => (float)$_POST['precio_lote'],
                'precio_total'      => (float)$_POST['precio_total']
            ];
            $respuesta = ModeloDesarrollos::mdlEditarDesarrollo($datos);
            return $respuesta;
        }
        return null;
    }

    /**
     * Obtener todos los desarrollos registrados.
     *
     * @return array
     */
    public static function ctrMostrarDesarrollos() {
        return ModeloDesarrollos::mdlMostrarDesarrollos();
    }
}