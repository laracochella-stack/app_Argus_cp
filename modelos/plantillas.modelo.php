<?php
/**
 * Modelo para plantillas de contratos.
 */
class ModeloPlantillas
{
    /**
     * Inserta un registro de plantilla.
     *
     * @param array $datos
     * @return string 'ok' o 'error'
     */
    static public function mdlAgregarPlantilla($datos)
    {
        $stmt = Conexion::conectar()->prepare(
            "INSERT INTO argus_plantillas (tipo_contrato_id, nombre_archivo, ruta_archivo) VALUES (:tipo_id, :nombre_archivo, :ruta_archivo)"
        );
        $stmt->bindParam(':tipo_id', $datos['tipo_contrato_id'], PDO::PARAM_INT);
        $stmt->bindParam(':nombre_archivo', $datos['nombre_archivo'], PDO::PARAM_STR);
        $stmt->bindParam(':ruta_archivo', $datos['ruta_archivo'], PDO::PARAM_STR);
        return $stmt->execute() ? 'ok' : 'error';
    }

    /**
     * Obtiene todas las plantillas con información del tipo de contrato.
     *
     * @return array
     */
    static public function mdlMostrarPlantillas()
    {
        $stmt = Conexion::conectar()->prepare(
            "SELECT p.id, p.tipo_contrato_id, v.nombre AS nombre_tipo, p.nombre_archivo, p.ruta_archivo, p.created_at
             FROM argus_plantillas p
             LEFT JOIN argus_variables v ON p.tipo_contrato_id = v.id"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Elimina una plantilla por su ID.
     *
     * @param int $id
     * @return string
     */
    static public function mdlEliminarPlantilla($id)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM argus_plantillas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute() ? 'ok' : 'error';
    }
}