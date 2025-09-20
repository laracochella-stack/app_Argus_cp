<?php
require_once 'modelos/clientes.modelo.php';

class ControladorClientes {
    /**
     * Registrar un nuevo cliente a partir de los datos recibidos por POST.
     * Se espera que la vista envíe los datos con nombres de campo coincidentes.
     */
    public static function ctrAgregarCliente() {
        // Comprobar que se haya enviado el formulario de cliente y que la acción sea agregar
        if (isset($_POST['nombre']) && isset($_GET['accion']) && $_GET['accion'] === 'agregar') {
            // Validar token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                return 'error_csrf';
            }
            // Verificar que el usuario tenga sesión iniciada y permisos adecuados (al menos autenticado)
            if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok') {
                return 'error_permiso';
            }
            $datos = [
                'nombre'        => trim($_POST['nombre']),
                'nacionalidad'  => trim($_POST['nacionalidad']),
                'fecha'         => $_POST['fecha_nacimiento'] ?? null,
                'rfc'           => trim($_POST['rfc']),
                'curp'          => trim($_POST['curp']),
                'ine'           => trim($_POST['ine']),
                'estado_civil'  => trim($_POST['estado_civil']),
                'ocupacion'     => trim($_POST['ocupacion']),
                'telefono'      => trim($_POST['telefono']),
                'domicilio'     => trim($_POST['domicilio']),
                'email'         => trim($_POST['email']),
                'beneficiario'  => trim($_POST['beneficiario'])
            ];
            $respuesta = ModeloClientes::mdlAgregarCliente($datos);
            return $respuesta;
        }
        return null;
    }

    /**
     * Obtener todos los clientes para mostrarlos en la vista.
     */
    public static function ctrMostrarClientes() {
        return ModeloClientes::mdlMostrarClientes();
    }

    /**
     * Editar un cliente existente. Recibe datos por POST con la acción editarCliente.
     *
     * @return string|null
     */
    public static function ctrEditarCliente() {
        if (isset($_POST['id_cliente']) && isset($_GET['accion']) && $_GET['accion'] === 'editarCliente') {
            // Validar token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                return 'error_csrf';
            }
            // Verificar sesión iniciada
            if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok') {
                return 'error_permiso';
            }
            // Recolectar datos del formulario
            $datos = [
                'id'           => (int)$_POST['id_cliente'],
                'nombre'       => trim($_POST['nombre']),
                'nacionalidad' => trim($_POST['nacionalidad']),
                'fecha'        => $_POST['fecha_nacimiento'] ?? null,
                'rfc'          => trim($_POST['rfc']),
                'curp'         => trim($_POST['curp']),
                'ine'          => trim($_POST['ine']),
                'estado_civil' => trim($_POST['estado_civil']),
                'ocupacion'    => trim($_POST['ocupacion']),
                'telefono'     => trim($_POST['telefono']),
                'domicilio'    => trim($_POST['domicilio']),
                'email'        => trim($_POST['email']),
                'beneficiario' => trim($_POST['beneficiario'])
            ];
            $respuesta = ModeloClientes::mdlEditarCliente($datos);
            return $respuesta;
        }
        return null;
    }
}