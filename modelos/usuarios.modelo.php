<?php
require_once 'conexion.php';

class ModeloUsuarios {
    /**
     * Obtener un usuario por campo específico
     *
     * @param string $tabla Nombre de la tabla
     * @param string $item  Columna a buscar
     * @param mixed  $valor Valor a comparar
     * @return array|false
     */
    public static function mdlMostrarUsuario($tabla, $item, $valor) {
        $link = Conexion::conectar();
        $stmt = $link->prepare("SELECT * FROM $tabla WHERE $item = :valor LIMIT 1");
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insertar un nuevo usuario con contraseña hasheada y permiso.
     *
     * @param array $datos Datos del usuario: username, password, permission
     * @return string 'ok' en caso de éxito, 'error' en caso de fallo
     */
    public static function mdlAgregarUsuario($datos) {
        $link = Conexion::conectar();
        $sql = "INSERT INTO argus_users (username, password, permission) VALUES (:username, :password, :permission)";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':username', $datos['username'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $datos['password'], PDO::PARAM_STR);
        $stmt->bindParam(':permission', $datos['permission'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return 'ok';
        }
        return 'error';
    }

    /**
     * Obtener todos los usuarios registrados.
     *
     * @return array Lista de usuarios
     */
    public static function mdlMostrarUsuarios() {
        $link = Conexion::conectar();
        $stmt = $link->query("SELECT id, username, permission, created_at FROM argus_users ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}