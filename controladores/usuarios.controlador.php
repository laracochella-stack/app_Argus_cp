<?php
require_once 'modelos/usuarios.modelo.php';

class ControladorUsuarios {
    // Se elimina la contraseña maestra; la creación de usuarios se controla por rol de sesión
    /**
     * Procesar inicio de sesión.
     */
    public static function ctrIngresoUsuario() {
        // Procesar inicio de sesión sólo cuando se envían credenciales por POST
        if (isset($_POST['ingUsuario'])) {
            // Validar token CSRF para evitar peticiones forjadas
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo '<script>Swal.fire({title: "Error de seguridad", text: "Token inválido. Recargue la página.", icon: "error"});</script>';
                return;
            }
            $tabla = 'argus_users';
            $item  = 'username';
            $valor = $_POST['ingUsuario'];
            $respuesta = ModeloUsuarios::mdlMostrarUsuario($tabla, $item, $valor);
            // Verificar que exista el usuario y que la contraseña coincida utilizando password_verify
            if ($respuesta && password_verify($_POST['ingPassword'], $respuesta['password'])) {
                // Autenticación exitosa: regenerar el ID de sesión para mitigar ataques de fijación de sesión
                session_regenerate_id(true);
                $_SESSION['iniciarSesion'] = 'ok';
                $_SESSION['id'] = $respuesta['id'];
                $_SESSION['username'] = $respuesta['username'];
                $_SESSION['permission'] = $respuesta['permission'];
                // Mostrar alerta de éxito y redirigir
                echo '<script>
                    Swal.fire({
                        title: "Bienvenido",
                        text: "Inicio de sesión exitoso",
                        icon: "success",
                        confirmButtonText: "Continuar"
                    }).then(function() {
                        window.location = "index.php?ruta=inicio";
                    });
                    </script>';
            } else {
                // Credenciales incorrectas
                echo '<script>Swal.fire({title: "Error", text: "Usuario o contraseña incorrectos", icon: "error"});</script>';
            }
        }
    }

    /**
     * Procesar el registro de un nuevo usuario desde el módulo de roles.
     * Sólo usuarios con rol administrador pueden registrar nuevos usuarios.
     */
    public static function ctrRegistrarUsuario() {
        if (isset($_POST['nuevoUsuario'])) {
            // Validar token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo '<script>Swal.fire({title:"Error de seguridad", text:"Token inválido", icon:"error"});</script>';
                return;
            }
            // Si hay sesión iniciada y no tiene permiso de administrador, negar la creación de usuarios
            if (isset($_SESSION['permission']) && $_SESSION['permission'] !== 'admin') {
                echo '<script>Swal.fire({title:"Sin permiso", text:"No tiene permisos para crear usuarios", icon:"error"});</script>';
                return;
            }
            // Obtener y validar campos
            $username    = trim($_POST['nuevoUsuario']);
            $password    = $_POST['nuevoPassword'] ?? '';
            $password2   = $_POST['repetirPassword'] ?? '';
            $permissionInput   = $_POST['nuevoRol'] ?? 'user';
            // Verificar que las contraseñas coincidan
            if ($password === '' || $password !== $password2) {
                echo '<script>Swal.fire({title:"Error", text:"Las contraseñas no coinciden", icon:"error"});</script>';
                return;
            }
            // Determinar el rol: sólo los administradores pueden elegir un rol distinto de 'user'
            $isAdmin = isset($_SESSION['permission']) && $_SESSION['permission'] === 'admin';
            $permission = $isAdmin ? $permissionInput : 'user';
            // Hashear la nueva contraseña
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $datos = [
                'username'   => $username,
                'password'   => $hash,
                'permission' => $permission
            ];
            $resultado = ModeloUsuarios::mdlAgregarUsuario($datos);
            if ($resultado === 'ok') {
                echo '<script>Swal.fire({title:"Éxito", text:"Usuario creado correctamente", icon:"success"});</script>';
            } else {
                echo '<script>Swal.fire({title:"Error", text:"No se pudo crear el usuario", icon:"error"});</script>';
            }
        }
    }

    /**
     * Obtener lista de usuarios para mostrar en la vista de roles.
     *
     * @return array
     */
    public static function ctrMostrarUsuarios() {
        return ModeloUsuarios::mdlMostrarUsuarios();
    }
}