<?php
/**
 * Modelo para variables generales (nacionalidades, tipos de contrato, etc.).
 */
class ModeloVariables
{
    /**
     * Obtiene todas las variables de un tipo dado.
     *
     * @param string $tipo Tipo de variable (ej: 'nacionalidad', 'tipo_contrato')
     * @return array
     */
    static public function mdlMostrarVariables($tipo)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM argus_variables WHERE tipo = :tipo ORDER BY id DESC");
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta una nueva variable.
     *
     * @param array $datos
     * @return string 'ok' o 'error'
     */
    static public function mdlAgregarVariable($datos)
    {
        $stmt = Conexion::conectar()->prepare(
            "INSERT INTO argus_variables (tipo, identificador, nombre) VALUES (:tipo, :identificador, :nombre)"
        );
        $stmt->bindParam(':tipo', $datos['tipo'], PDO::PARAM_STR);
        $stmt->bindParam(':identificador', $datos['identificador'], PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
        return $stmt->execute() ? 'ok' : 'error';
    }

    /**
     * Actualiza una variable existente.
     *
     * @param array $datos
     * @return string
     */
    static public function mdlEditarVariable($datos)
    {
        $stmt = Conexion::conectar()->prepare(
            "UPDATE argus_variables SET identificador = :identificador, nombre = :nombre WHERE id = :id"
        );
        $stmt->bindParam(':identificador', $datos['identificador'], PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $datos['id'], PDO::PARAM_INT);
        return $stmt->execute() ? 'ok' : 'error';
    }

    /**
     * Elimina una variable por su ID.
     *
     * @param int $id
     * @return string
     */
    static public function mdlEliminarVariable($id)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM argus_variables WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute() ? 'ok' : 'error';
    }
}