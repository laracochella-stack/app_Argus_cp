<?php
/**
 * Clase de conexión centralizada para la base de datos.
 * Proporciona un método estático para obtener una instancia PDO.
 */
class Conexion {

    public static function conectar() {
        $host = 'localhost';
        $db   = 'argus';
        $user = 'root';
        $pass = '';
        try {
            $link = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $link;
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
}