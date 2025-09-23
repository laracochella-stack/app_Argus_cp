<?php
/**
 * Modelo de contratos (placeholder).
 */
class ModeloContratos
{
    /**
     * Comprueba si existe un contrato para un cliente determinado.
     *
     * @param int $clienteId
     * @return bool Verdadero si existe un contrato
     */
    static public function mdlExisteContratoPorCliente($clienteId)
    {
        $stmt = Conexion::conectar()->prepare("SELECT id FROM argus_contratos_data WHERE cliente_id = :id LIMIT 1");
        $stmt->bindParam(":id", $clienteId, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch();
        return $resultado ? true : false;
    }

    /**
     * Inserta un nuevo contrato en la base de datos.
     *
     * @param array $datos
     * @return string 'ok' si se insertó, 'error' en caso contrario
     */
    static public function mdlCrearContrato($datos)
    {
        try {
            $stmt = Conexion::conectar()->prepare(
                "INSERT INTO argus_contratos_data (cliente_id, desarrollo_id, datta_contrato) VALUES (:cliente_id, :desarrollo_id, :datta_contrato)"
            );
            $stmt->bindParam(":cliente_id", $datos['cliente_id'], PDO::PARAM_INT);
            $stmt->bindParam(":desarrollo_id", $datos['desarrollo_id'], PDO::PARAM_INT);
            $stmt->bindParam(":datta_contrato", $datos['datta_contrato'], PDO::PARAM_STR);
            if ($stmt->execute()) {
                return 'ok';
            }
            return 'error';
        } catch (Exception $e) {
            return 'error';
        }
    }

    /**
     * Obtiene un contrato existente para un cliente. Se une con la tabla de desarrollos
     * para incluir el nombre del desarrollo, su superficie y tipo de contrato.
     *
     * @param int $clienteId
     * @return array|null
     */
    static public function mdlMostrarContratoPorCliente($clienteId)
    {
        $stmt = Conexion::conectar()->prepare(
            "SELECT c.datta_contrato, d.nombre AS nombre_desarrollo
             FROM argus_contratos_data c
             INNER JOIN argus_desarrollos d ON c.desarrollo_id = d.id
             WHERE c.cliente_id = :cliente_id
             LIMIT 1"
        );
        $stmt->bindParam(":cliente_id", $clienteId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene uno o varios contratos. Cuando se pasa un ID de cliente, se
     * devuelven únicamente los contratos para ese cliente, incluyendo los
     * nombres del cliente y del desarrollo y el tipo de contrato. Si no se
     * pasa parámetro, se devuelven todos los contratos existentes.
     *
     * @param int|null $clienteId ID del cliente o null para todos
     * @return array Lista de contratos
     */
    static public function mdlMostrarContratos($clienteId = null)
    {
        $pdo = Conexion::conectar();
        if ($clienteId !== null) {
            $stmt = $pdo->prepare(
                "SELECT c.id, c.cliente_id, c.desarrollo_id, c.datta_contrato, c.created_at,
                        cl.nombre AS nombre_cliente, d.nombre AS nombre_desarrollo, d.tipo_contrato AS tipo_contrato,
                        d.lotes_disponibles
                 FROM argus_contratos_data c
                 INNER JOIN argus_clientes cl ON c.cliente_id = cl.id
                 INNER JOIN argus_desarrollos d ON c.desarrollo_id = d.id
                 WHERE c.cliente_id = :cli"
            );
            $stmt->bindParam(':cli', $clienteId, PDO::PARAM_INT);
        } else {
            $stmt = $pdo->prepare(
                "SELECT c.id, c.cliente_id, c.desarrollo_id, c.datta_contrato, c.created_at,
                        cl.nombre AS nombre_cliente, d.nombre AS nombre_desarrollo, d.tipo_contrato AS tipo_contrato,
                        d.lotes_disponibles
                 FROM argus_contratos_data c
                 INNER JOIN argus_clientes cl ON c.cliente_id = cl.id
                 INNER JOIN argus_desarrollos d ON c.desarrollo_id = d.id"
            );
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza un contrato existente. Sólo se actualiza el campo datta_contrato
     * con un nuevo JSON. Se identifica por su ID.
     *
     * @param int $idContrato ID del contrato
     * @param string $jsonData JSON codificado con los datos del contrato
     * @return string 'ok' o 'error'
     */
    static public function mdlEditarContrato($idContrato, $jsonData)
    {
        $stmt = Conexion::conectar()->prepare(
            "UPDATE argus_contratos_data SET datta_contrato = :json WHERE id = :id"
        );
        $stmt->bindParam(':json', $jsonData, PDO::PARAM_STR);
        $stmt->bindParam(':id', $idContrato, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return 'ok';
        }
        return 'error';
    }

    /**
     * Devuelve un contrato específico por ID, incluyendo su JSON y llaves
     * de cliente y desarrollo. Si no existe, devuelve null.
     *
     * @param int $idContrato
     * @return array|null
     */
    static public function mdlMostrarContratoPorId($idContrato)
    {
        $stmt = Conexion::conectar()->prepare(
            "SELECT c.id, c.cliente_id, c.desarrollo_id, c.datta_contrato
             FROM argus_contratos_data c
             WHERE c.id = :id LIMIT 1"
        );
        $stmt->bindParam(':id', $idContrato, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}