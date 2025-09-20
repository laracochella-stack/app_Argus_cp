<?php
/**
 * Controlador de contratos (placeholder).
 */
class ControladorContratos
{
    /**
     * Verifica si un cliente ya tiene un contrato existente.
     *
     * @param int $clienteId
     * @return bool
     */
    static public function ctrExisteContrato($clienteId)
    {
        return ModeloContratos::mdlExisteContratoPorCliente($clienteId);
    }

    /**
     * Crea un nuevo contrato para un cliente. Procesa el formulario de creación
     * enviado desde la vista. Devuelve una cadena indicando 'ok' o 'error'.
     */
    static public function ctrCrearContrato()
    {
        if (!isset($_POST['crearContrato'])) {
            return;
        }
        // Debe haber sesión iniciada
        if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok') {
            echo 'error-sesion';
            return;
        }
        // Validar token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            echo 'error-token';
            return;
        }
        // Recoger datos básicos
        $clienteId = intval($_POST['cliente_id']);
        $desarrolloId = intval($_POST['desarrollo_id']);
        // Ya no se valida existencia de contrato: se permiten múltiples contratos por cliente
        // Obtener datos completos del cliente y el desarrollo
        $clienteData = ModeloClientes::mdlMostrarClientePorId($clienteId);
        $desarrolloData = ModeloDesarrollos::mdlMostrarDesarrolloPorId($desarrolloId);
        if (!$clienteData || !$desarrolloData) {
            echo 'error';
            return;
        }
        // Recoger campos del formulario de contrato
        $mensualidades = intval($_POST['mensualidades']);
        $superficie = trim($_POST['superficie']);
        // Recoger lista de fracciones (array de etiquetas) o cadena separada por comas
        $fracciones = [];
        if (isset($_POST['fracciones'])) {
            $temp = trim($_POST['fracciones']);
            // Puede venir como JSON o como lista separada por comas
            if ($temp) {
                // Intentar decodificar JSON
                $decoded = null;
                try {
                    $decoded = json_decode($temp, true);
                } catch (Exception $e) {
                    $decoded = null;
                }
                if (is_array($decoded)) {
                    $fracciones = array_filter($decoded, function ($v) {
                        return $v !== '';
                    });
                } else {
                    $fracciones = array_filter(array_map('trim', explode(',', $temp)), function ($v) {
                        return $v !== '';
                    });
                }
            }
        }
        // Convertir las fracciones a una cadena separada por comas para almacenarla en el JSON
        $fraccion = implode(',', $fracciones);
        $entrega = $_POST['entrega_posecion'];
        $fechaFirma = $_POST['fecha_firma'];
        $habitacional = trim($_POST['habitacional']);
        $inicioPagos = $_POST['inicio_pagos'];
        $tipoContrato = trim($_POST['tipo_contrato']);
        // Construir estructura JSON con todos los datos
        $contratoDetalle = [
            'mensualidades' => $mensualidades,
            'superficie' => $superficie,
            'fraccion_vendida' => $fraccion,
            'entrega_posecion' => $entrega,
            'fecha_firma_contrato' => $fechaFirma,
            'habitacional_colindancias' => $habitacional,
            'inicio_pagos' => $inicioPagos,
            'tipo_contrato' => $tipoContrato
        ];
        $jsonData = json_encode([
            'cliente' => $clienteData,
            'desarrollo' => $desarrolloData,
            'contrato' => $contratoDetalle
        ], JSON_UNESCAPED_UNICODE);
        $datos = [
            'cliente_id' => $clienteId,
            'desarrollo_id' => $desarrolloId,
            'datta_contrato' => $jsonData
        ];
        $respuesta = ModeloContratos::mdlCrearContrato($datos);
        echo $respuesta;
    }

    /**
     * Obtiene los datos de un contrato existente para un cliente específico.
     * Devuelve un array asociativo con los datos o null si no existe.
     *
     * @param int $clienteId
     * @return array|null
     */
    static public function ctrMostrarContratoPorCliente($clienteId)
    {
        $data = ModeloContratos::mdlMostrarContratoPorCliente($clienteId);
        if (!$data) return null;
        // Decodificar JSON y combinar con datos
        $result = [];
        $result['nombre_desarrollo'] = $data['nombre_desarrollo'];
        if (!empty($data['datta_contrato'])) {
            $json = json_decode($data['datta_contrato'], true);
            if (isset($json['contrato']) && is_array($json['contrato'])) {
                // Mapear campos a los nombres utilizados en la vista
                $contrato = $json['contrato'];
                $result['mensualidades'] = $contrato['mensualidades'] ?? '';
                $result['superficie'] = $contrato['superficie'] ?? '';
                $result['fraccion_vendida'] = $contrato['fraccion_vendida'] ?? '';
                $result['entrega_posecion'] = $contrato['entrega_posecion'] ?? '';
                $result['fecha_firma_contrato'] = $contrato['fecha_firma_contrato'] ?? '';
                $result['habitacional_colindancias'] = $contrato['habitacional_colindancias'] ?? '';
                $result['inicio_pagos'] = $contrato['inicio_pagos'] ?? '';
                $result['tipo_contrato'] = $contrato['tipo_contrato'] ?? '';
            }
        }
        return $result;
    }

    /**
     * Devuelve la lista de contratos. Si se especifica un cliente, sólo se listan
     * los contratos de ese cliente. Decodifica el JSON de datta_contrato para
     * exponer campos básicos en el array de resultados.
     *
     * @param int|null $clienteId
     * @return array
     */
    static public function ctrMostrarContratos($clienteId = null)
    {
        $contratos = ModeloContratos::mdlMostrarContratos($clienteId);
        $resultado = [];
        foreach ($contratos as $c) {
            $row = [
                'id' => $c['id'],
                'cliente_id' => $c['cliente_id'],
                'nombre_cliente' => $c['nombre_cliente'] ?? '',
                'desarrollo_id' => $c['desarrollo_id'],
                'nombre_desarrollo' => $c['nombre_desarrollo'] ?? '',
                'tipo_contrato' => $c['tipo_contrato'] ?? '',
                'created_at' => $c['created_at'] ?? ''
            ];
            // Decodificar json para obtener campos adicionales (mensualidades, superficie, fraccion)
            if (!empty($c['datta_contrato'])) {
                $json = json_decode($c['datta_contrato'], true);
                if (isset($json['contrato']) && is_array($json['contrato'])) {
                    $row['mensualidades'] = $json['contrato']['mensualidades'] ?? '';
                    $row['superficie'] = $json['contrato']['superficie'] ?? '';
                    $row['fraccion_vendida'] = $json['contrato']['fraccion_vendida'] ?? '';
                    $row['entrega_posecion'] = $json['contrato']['entrega_posecion'] ?? '';
                    $row['fecha_firma_contrato'] = $json['contrato']['fecha_firma_contrato'] ?? '';
                    $row['habitacional_colindancias'] = $json['contrato']['habitacional_colindancias'] ?? '';
                    $row['inicio_pagos'] = $json['contrato']['inicio_pagos'] ?? '';
                    // Type contract may come from contrato as well; override if set
                    if (!empty($json['contrato']['tipo_contrato'])) {
                        $row['tipo_contrato'] = $json['contrato']['tipo_contrato'];
                    }
                }
            }
            $resultado[] = $row;
        }
        return $resultado;
    }

    /**
     * Edita un contrato existente. Procesa el formulario de edición de contrato
     * enviado desde la vista. Se identifica el contrato por su ID y se guarda
     * un nuevo JSON con los datos actualizados.
     */
    static public function ctrEditarContrato()
    {
        if (!isset($_POST['editarContrato'])) {
            return;
        }
        // Debe haber sesión iniciada
        if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok') {
            echo 'error-sesion';
            return;
        }
        // Validar token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            echo 'error-token';
            return;
        }
        $contratoId = intval($_POST['contrato_id']);
        $mensualidades = intval($_POST['mensualidades']);
        $superficie = trim($_POST['superficie']);
        // Recoger lista de fracciones para edición
        $fracciones = [];
        if (isset($_POST['fracciones'])) {
            $temp = trim($_POST['fracciones']);
            if ($temp) {
                $decoded = null;
                try {
                    $decoded = json_decode($temp, true);
                } catch (Exception $e) {
                    $decoded = null;
                }
                if (is_array($decoded)) {
                    $fracciones = array_filter($decoded, function ($v) {
                        return $v !== '';
                    });
                } else {
                    $fracciones = array_filter(array_map('trim', explode(',', $temp)), function ($v) {
                        return $v !== '';
                    });
                }
            }
        }
        // Convertir a cadena separada por comas
        $fraccion = implode(',', $fracciones);
        $entrega = $_POST['entrega_posecion'];
        $fechaFirma = $_POST['fecha_firma'];
        $habitacional = trim($_POST['habitacional']);
        $inicioPagos = $_POST['inicio_pagos'];
        $tipoContrato = trim($_POST['tipo_contrato']);
        // Obtener datos actuales del contrato para conservar información de cliente y desarrollo
        $contratoActual = ModeloContratos::mdlMostrarContratoPorId($contratoId);
        if (!$contratoActual) {
            echo 'error';
            return;
        }
        // Decodificar json anterior para obtener estructura de cliente y desarrollo
        $clienteData = [];
        $desarrolloData = [];
        if (!empty($contratoActual['datta_contrato'])) {
            $jsonOld = json_decode($contratoActual['datta_contrato'], true);
            $clienteData = $jsonOld['cliente'] ?? [];
            $desarrolloData = $jsonOld['desarrollo'] ?? [];
        }
        $contratoDetalle = [
            'mensualidades' => $mensualidades,
            'superficie' => $superficie,
            'fraccion_vendida' => $fraccion,
            'entrega_posecion' => $entrega,
            'fecha_firma_contrato' => $fechaFirma,
            'habitacional_colindancias' => $habitacional,
            'inicio_pagos' => $inicioPagos,
            'tipo_contrato' => $tipoContrato
        ];
        $jsonData = json_encode([
            'cliente' => $clienteData,
            'desarrollo' => $desarrolloData,
            'contrato' => $contratoDetalle
        ], JSON_UNESCAPED_UNICODE);
        $respuesta = ModeloContratos::mdlEditarContrato($contratoId, $jsonData);
        echo $respuesta;
    }
}