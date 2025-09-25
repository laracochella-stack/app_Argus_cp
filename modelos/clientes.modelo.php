<?php
require_once 'conexion.php';

class ModeloClientes {
    /**
     * Inserta un nuevo registro en la tabla argus_clientes.
     * @param array $datos Datos del cliente
     * @return string 'ok' en caso de éxito, 'error' en caso de fallo
     */
    public static function mdlAgregarCliente($datos) {
        $link = Conexion::conectar();
        $sql = "INSERT INTO argus_clientes (nombre, nacionalidad, fecha_nacimiento, rfc, curp, ine, estado_civil, ocupacion, telefono, domicilio, email, beneficiario, referencias) VALUES (:nombre, :nacionalidad, :fecha, :rfc, :curp, :ine, :estado_civil, :ocupacion, :telefono, :domicilio, :email, :beneficiario, :referencias)";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':nacionalidad', $datos['nacionalidad'], PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $datos['fecha']);
        $stmt->bindParam(':rfc', $datos['rfc'], PDO::PARAM_STR);
        $stmt->bindParam(':curp', $datos['curp'], PDO::PARAM_STR);
        $stmt->bindParam(':ine', $datos['ine'], PDO::PARAM_STR);
        $stmt->bindParam(':estado_civil', $datos['estado_civil'], PDO::PARAM_STR);
        $stmt->bindParam(':ocupacion', $datos['ocupacion'], PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $datos['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(':domicilio', $datos['domicilio'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $datos['email'], PDO::PARAM_STR);
        $stmt->bindParam(':beneficiario', $datos['beneficiario'], PDO::PARAM_STR);
        $stmt->bindParam(':referencias', $datos['referencias'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return 'ok';
        }
        return 'error';
    }

    /**
     * Inserta un nuevo cliente y devuelve el ID generado. Si falla, devuelve 0.
     * Este método se utiliza cuando se necesita enlazar inmediatamente al cliente
     * con otros registros (por ejemplo, crear un contrato). No reemplaza a
     * mdlAgregarCliente, que sigue devolviendo 'ok' o 'error'.
     *
     * @param array $datos Datos del cliente
     * @return int ID del cliente insertado o 0 en caso de error
     */
    public static function mdlAgregarClienteRetId($datos) {
        $link = Conexion::conectar();
        $sql = "INSERT INTO argus_clientes (nombre, nacionalidad, fecha_nacimiento, rfc, curp, ine, estado_civil, ocupacion, telefono, domicilio, email, beneficiario, referencias) VALUES (:nombre, :nacionalidad, :fecha, :rfc, :curp, :ine, :estado_civil, :ocupacion, :telefono, :domicilio, :email, :beneficiario, :referencias)";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':nacionalidad', $datos['nacionalidad'], PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $datos['fecha']);
        $stmt->bindParam(':rfc', $datos['rfc'], PDO::PARAM_STR);
        $stmt->bindParam(':curp', $datos['curp'], PDO::PARAM_STR);
        $stmt->bindParam(':ine', $datos['ine'], PDO::PARAM_STR);
        $stmt->bindParam(':estado_civil', $datos['estado_civil'], PDO::PARAM_STR);
        $stmt->bindParam(':ocupacion', $datos['ocupacion'], PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $datos['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(':domicilio', $datos['domicilio'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $datos['email'], PDO::PARAM_STR);
        $stmt->bindParam(':beneficiario', $datos['beneficiario'], PDO::PARAM_STR);
        $stmt->bindParam(':referencias', $datos['referencias'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return (int)$link->lastInsertId();
        }
        return 0;
    }

    /**
     * Obtiene todos los clientes registrados.
     * @return array
     */
    public static function mdlMostrarClientes() {
        $link = Conexion::conectar();
        $stmt = $link->query("SELECT * FROM argus_clientes ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza un registro de la tabla argus_clientes.
     *
     * @param array $datos Datos del cliente (incluye id)
     * @return string 'ok' en caso de éxito, 'error' en caso de fallo
     */
    public static function mdlEditarCliente($datos) {
        $link = Conexion::conectar();
        $sql = "UPDATE argus_clientes SET nombre = :nombre, nacionalidad = :nacionalidad, fecha_nacimiento = :fecha, rfc = :rfc, curp = :curp, ine = :ine, estado_civil = :estado_civil, ocupacion = :ocupacion, telefono = :telefono, domicilio = :domicilio, email = :email, beneficiario = :beneficiario, referencias = :referencias WHERE id = :id";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':id', $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':nacionalidad', $datos['nacionalidad'], PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $datos['fecha']);
        $stmt->bindParam(':rfc', $datos['rfc'], PDO::PARAM_STR);
        $stmt->bindParam(':curp', $datos['curp'], PDO::PARAM_STR);
        $stmt->bindParam(':ine', $datos['ine'], PDO::PARAM_STR);
        $stmt->bindParam(':estado_civil', $datos['estado_civil'], PDO::PARAM_STR);
        $stmt->bindParam(':ocupacion', $datos['ocupacion'], PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $datos['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(':domicilio', $datos['domicilio'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $datos['email'], PDO::PARAM_STR);
        $stmt->bindParam(':beneficiario', $datos['beneficiario'], PDO::PARAM_STR);
        $stmt->bindParam(':referencias', $datos['referencias'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return 'ok';
        }
        return 'error';
    }

    /**
     * Obtiene un cliente por su ID.
     *
     * @param int $id
     * @return array|null
     */
    public static function mdlMostrarClientePorId($id) {
        $link = Conexion::conectar();
        $stmt = $link->prepare("SELECT * FROM argus_clientes WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}