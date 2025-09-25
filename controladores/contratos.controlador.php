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
        // Verificar que se envió el formulario correcto
        
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
        // Nuevos campos numéricos y de configuración
        $montoInmueble      = isset($_POST['monto_inmueble']) ? floatval($_POST['monto_inmueble']) : 0;
        $montoInmuebleFixed = isset($_POST['monto_inmueble_fixed']) ? trim($_POST['monto_inmueble_fixed']) : '';
        $enganche           = isset($_POST['enganche']) ? floatval($_POST['enganche']) : 0;
        $engancheFixed      = isset($_POST['enganche_fixed']) ? trim($_POST['enganche_fixed']) : '';
        $saldoPago          = isset($_POST['saldo_pago']) ? floatval($_POST['saldo_pago']) : 0;
        $saldoPagoFixed     = isset($_POST['saldo_pago_fixed']) ? trim($_POST['saldo_pago_fixed']) : '';
        $parcialidades      = isset($_POST['parcialidades_anuales']) ? trim($_POST['parcialidades_anuales']) : '';
        $penalizacion       = isset($_POST['penalizacion']) ? floatval($_POST['penalizacion']) : 0;
        $penalizacionFixed  = isset($_POST['penalizacion_fixed']) ? trim($_POST['penalizacion_fixed']) : '';
        $diaPago            = isset($_POST['dia_pago']) ? trim($_POST['dia_pago']) : '';
        $rangoCompromiso    = isset($_POST['rango_compromiso_pago']) ? trim($_POST['rango_compromiso_pago']) : '';
        $vigenciaPagare     = isset($_POST['vigencia_pagare']) ? trim($_POST['vigencia_pagare']) : '';
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
            'mensualidades'            => $mensualidades,
            'superficie'               => $superficie,
            'fraccion_vendida'         => $fraccion,
            'entrega_posecion'         => $entrega,
            'fecha_firma_contrato'     => $fechaFirma,
            'habitacional_colindancias' => $habitacional,
            'inicio_pagos'             => $inicioPagos,
            'tipo_contrato'            => $tipoContrato,
            // Nuevos campos
            'monto_precio_inmueble'     => $montoInmueble,
            'monto_precio_inmueble_fixed' => $montoInmuebleFixed,
            'enganche'                 => $enganche,
            'enganche_fixed'           => $engancheFixed,
            'saldo_pago'               => $saldoPago,
            'saldo_pago_fixed'         => $saldoPagoFixed,
            'parcialidades_anuales'    => $parcialidades,
            'penalizacion_10'          => $penalizacion,
            'penalizacion_10_fixed'    => $penalizacionFixed,
            'dia_pago'                 => $diaPago,
            'rango_compromiso_pago'    => $rangoCompromiso,
            'vigencia_pagare'          => $vigenciaPagare
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
     * Crea un cliente y su contrato en un solo paso. Este método se invoca
     * desde la vista crearContrato.php cuando se envía el formulario
     * unificado. Valida sesión y CSRF, inserta primero el cliente y luego
     * registra el contrato junto con todos los datos en formato JSON.
     *
     * El formulario envía un campo oculto "crearContratoCompleto" para
     * asegurar que sólo se ejecute en la página correcta.
     */

    //ESTE ES EL METODO PARA crearContrato.php
    static public function ctrCrearContratoCompleto()
    {
        if (!isset($_POST['crearContratoCompleto'])) {
            return;
            
        }
        // Debe haber sesión iniciada
        if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok') {
            echo 'error-sesion';
            return;
            exit;
        }
        // Validar token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            echo 'error-token';
            return;
            exit;
        }
        // ----- Datos del cliente -----
        $clienteNombre        = isset($_POST['cliente_nombre']) ? strtoupper(trim($_POST['cliente_nombre'])) : '';
        $clienteNacionalidad  = isset($_POST['cliente_nacionalidad']) ? trim($_POST['cliente_nacionalidad']) : '';
        $clienteFechaNac      = isset($_POST['cliente_fecha_nacimiento']) ? trim($_POST['cliente_fecha_nacimiento']) : '';
        $clienteRfc           = isset($_POST['cliente_rfc']) ? strtoupper(trim($_POST['cliente_rfc'])) : '';
        $clienteCurp          = isset($_POST['cliente_curp']) ? strtoupper(trim($_POST['cliente_curp'])) : '';
        $clienteIne           = isset($_POST['cliente_ine']) ? strtoupper(trim($_POST['cliente_ine'])) : '';
        $clienteEstadoCivil   = isset($_POST['cliente_estado_civil']) ? strtoupper(trim($_POST['cliente_estado_civil'])) : '';
        $clienteOcupacion     = isset($_POST['cliente_ocupacion']) ? strtoupper(trim($_POST['cliente_ocupacion'])) : '';
        $clienteTelefono      = isset($_POST['cliente_telefono']) ? trim($_POST['cliente_telefono']) : '';
        $clienteDomicilio     = isset($_POST['cliente_domicilio']) ? strtoupper(trim($_POST['cliente_domicilio'])) : '';
        $clienteEmail         = isset($_POST['cliente_email']) ? trim($_POST['cliente_email']) : '';
        $clienteBeneficiario  = isset($_POST['cliente_beneficiario']) ? strtoupper(trim($_POST['cliente_beneficiario'])) : '';
        $clienteReferencias   = isset($_POST['cliente_referencias']) ? strtoupper(trim($_POST['cliente_referencias'])) : '';
        // Función para convertir una fecha YYYY-MM-DD a "DD de Mes de YYYY cliente_referencias"
        $formatearFechaLarga = function ($fecha) {
            if (!$fecha) return '';
            $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
            $partes = explode('-', $fecha);
            if (count($partes) === 3) {
                $anio = $partes[0];
                $mes  = (int)$partes[1];
                $dia  = (int)$partes[2];
                $mesNombre = $meses[$mes - 1] ?? '';
                return $dia . ' DE ' . ucfirst($mesNombre) . ' DE ' . $anio;
            }
            return $fecha;
        };
        // Convertir fechas
        $clienteFechaNacLomng  = $formatearFechaLarga($clienteFechaNac);
        
        // Construir arreglo de cliente para guardar
        $datosCliente = [
            'nombre'       => $clienteNombre,
            'nacionalidad' => $clienteNacionalidad,
            'fecha'        => $clienteFechaNacLomng,
            'rfc'          => $clienteRfc,
            'curp'         => $clienteCurp,
            'ine'          => $clienteIne,
            'estado_civil' => $clienteEstadoCivil,
            'ocupacion'    => $clienteOcupacion,
            'telefono'     => $clienteTelefono,
            'domicilio'    => $clienteDomicilio,
            'email'        => $clienteEmail,
            'beneficiario' => $clienteBeneficiario,
            // Edad calculada del cliente
            'edad'         => isset($_POST['cliente_edad']) ? intval($_POST['cliente_edad']) : '',
            'referencias'  => $clienteReferencias
        ];
        // Insertar cliente y obtener ID
        $clienteId = ModeloClientes::mdlAgregarClienteRetId($datosCliente);
        if (!$clienteId) {
            echo 'error';
            return;
        }

        // ----- Datos del desarrollo -----
        $desarrolloId = isset($_POST['desarrollo_id']) ? intval($_POST['desarrollo_id']) : 0;
        $desarrolloData = ModeloDesarrollos::mdlMostrarDesarrolloPorId($desarrolloId);
        if (!$desarrolloData) {
            echo 'error';
            return;
        }
        // ----- Datos del contrato -----
        $folio         = isset($_POST['folio']) ? strtoupper(trim($_POST['folio'])) : '';
        $mensualidades = isset($_POST['mensualidades']) ? intval($_POST['mensualidades']) : 0;
        $superficie    = isset($_POST['contrato_superficie']) ? trim($_POST['contrato_superficie']) : '';
        // Procesar fracciones (puede venir como JSON o lista separada por comas)
        $fraccionesInput = isset($_POST['fracciones']) ? trim($_POST['fracciones']) : '';
        $fraccionesArr = [];
        if ($fraccionesInput) {
            $decoded = null;
            try {
                $decoded = json_decode($fraccionesInput, true);
            } catch (Exception $e) {
                $decoded = null;
            }
            if (is_array($decoded)) {
                $fraccionesArr = array_filter($decoded, function ($v) { return $v !== ''; });
            } else {
                $fraccionesArr = array_filter(array_map('trim', explode(',', $fraccionesInput)), function ($v) { return $v !== ''; });
            }
        }
        $fraccion = implode(',', $fraccionesArr);
        $entregaPosecion = isset($_POST['entrega_posecion']) ? trim($_POST['entrega_posecion']) : '';
        $fechaFirma      = isset($_POST['fecha_firma']) ? trim($_POST['fecha_firma']) : '';
        // Contenido habitacional como texto. Convertir a mayúsculas
        $habitacional    = isset($_POST['habitacional']) ? strtoupper(trim($_POST['habitacional'])) : '';
        $inicioPagos     = isset($_POST['inicio_pagos']) ? trim($_POST['inicio_pagos']) : '';
        $tipoContratoId  = isset($_POST['tipo_contrato']) ? trim($_POST['tipo_contrato']) : '';
        // Rango de pago (inicio y fin) se reciben independientemente
        $rangoInicioRaw = isset($_POST['rango_pago_inicio']) ? trim($_POST['rango_pago_inicio']) : '';
        $rangoFinRaw    = isset($_POST['rango_pago_fin']) ? trim($_POST['rango_pago_fin']) : '';
        // Montos y cálculos
        $montoInmueble      = isset($_POST['monto_inmueble']) ? floatval($_POST['monto_inmueble']) : 0;
        $montoInmuebleFixed = isset($_POST['monto_inmueble_fixed']) ? trim($_POST['monto_inmueble_fixed']) : '';
        $enganche           = isset($_POST['enganche']) ? floatval($_POST['enganche']) : 0;
        $engancheFixed      = isset($_POST['enganche_fixed']) ? trim($_POST['enganche_fixed']) : '';
        $saldoPago          = isset($_POST['saldo_pago']) ? floatval($_POST['saldo_pago']) : 0;
        $saldoPagoFixed     = isset($_POST['saldo_pago_fixed']) ? trim($_POST['saldo_pago_fixed']) : '';
        $parcialidadesAnuales = isset($_POST['parcialidades_anuales']) ? trim($_POST['parcialidades_anuales']) : '';
        $penalizacion10        = isset($_POST['penalizacion']) ? floatval($_POST['penalizacion']) : 0;
        $penalizacionFixed     = isset($_POST['penalizacion_fixed']) ? trim($_POST['penalizacion_fixed']) : '';
        $vigenciaPagareRaw     = isset($_POST['vigencia_pagare']) ? trim($_POST['vigencia_pagare']) : '';
        $rangoPago        = isset($_POST['rango_pago']) ? trim($_POST['rango_pago']) : '';
        // Superficie fija en texto (convertida en el frontend)
        $superficieFixed       = isset($_POST['superficie_fixed']) ? trim($_POST['superficie_fixed']) : '';
        // Nuevo: pago mensual y su versión fija
        $pagoMensual          = isset($_POST['pago_mensual']) ? floatval($_POST['pago_mensual']) : 0;
        $pagoMensualFixed     = isset($_POST['pago_mensual_fixed']) ? trim($_POST['pago_mensual_fixed']) : '';
        // Fecha del contrato y su versión fija
        $fechaContrato        = isset($_POST['fecha_contrato']) ? trim($_POST['fecha_contrato']) : '';
        $fechaContratoFixed   = isset($_POST['fecha_contrato_fixed']) ? trim($_POST['fecha_contrato_fixed']) : '';
        // Día de inicio (sólo día numérico extraído de fecha de contrato)
        $diaInicio = '';
        if ($fechaContrato) {
            $partesFecha = explode('-', $fechaContrato);
            if (count($partesFecha) === 3) {
                $diaInicio = intval($partesFecha[2]);
            }
        }

        // Convertir número a letras para superficie (requiere extensión intl habilitada)
        $numeroASuperficie = function ($numero) {
            if (!class_exists('NumberFormatter')) return $numero;
            $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);

            $entero = floor($numero); // tomamos solo la parte entera
            $letras = strtoupper($formatter->format($entero));

            return $numero . "M²". " (" . $letras . "METROS CUADRADOS)";
        };


        
        // Convertir fechas
        $entregaLong  = $formatearFechaLarga($entregaPosecion);
        $firmaLong    = $formatearFechaLarga($fechaFirma);
        $inicioLong   = $formatearFechaLarga($inicioPagos);
        $vigenciaLong = $formatearFechaLarga($vigenciaPagareRaw);
        $rangoInicio  = $formatearFechaLarga($rangoInicioRaw);
        $rangoFin     = $formatearFechaLarga($rangoFinRaw);
        //$rangoPago    = ($rangoInicio && $rangoFin) ? ($rangoInicio . ' A ' . $rangoFin) : ($rangoInicio ?: $rangoFin);
        // Convertir fecha del contrato a formato largo
        $fechaContratoLong = $formatearFechaLarga($fechaContrato);
        // Construir detalle del contrato
        $contratoDetalle = [
            'folio'                      => $folio,
            'mensualidades'              => $mensualidades,
            'superficie'                 => $superficie,
            'superficie_fixed'              => $numeroASuperficie($superficie),
            'fraccion_vendida'           => $fraccion,
            'entrega_posecion'           => $entregaLong,
            'fecha_firma_contrato'       => $firmaLong,
            // Guardar el HTML del habitacional tal cual sin convertir a mayúsculas
            'habitacional_colindancias'  => $habitacional,
            'inicio_pagos'               => $inicioLong,
            'tipo_contrato'              => $tipoContratoId,
            // Formatear montos numéricos con separadores de miles y dos decimales
            'monto_precio_inmueble'       => number_format($montoInmueble, 2, '.', ','),
            'monto_precio_inmueble_fixed' => $montoInmuebleFixed,
            'enganche'                    => number_format($enganche, 2, '.', ','),
            'enganche_fixed'              => $engancheFixed,
            'saldo_pago'                  => number_format($saldoPago, 2, '.', ','),
            'saldo_pago_fixed'            => $saldoPagoFixed,
            'parcialidades_anuales'       => $parcialidadesAnuales,
            'penalizacion_10'             => number_format($penalizacion10, 2, '.', ','),
            'penalizacion_10_fixed'       => $penalizacionFixed,
            'pago_mensual'                => number_format($pagoMensual, 2, '.', ','),
            'pago_mensual_fixed'          => $pagoMensualFixed,
            // Guardar fecha de contrato en formato largo y su versión fija
            'fecha_contrato'              => $fechaContratoLong,
            'fecha_contrato_fixed'        => $fechaContratoFixed,
            // Guardar rango de pago tanto unido como separado
            'rango_pago_inicio'           => $rangoInicio,
            'rango_pago_fin'              => $rangoFin,
            'rango_pago'                  => $rangoPago,
            // Almacenar día de inicio para referencia
            'dia_inicio'                  => $diaInicio,
            'vigencia_pagare'             => $vigenciaLong
        ];
        // Construir JSON completo
        $jsonData = json_encode([
            'cliente'   => $datosCliente,
            'desarrollo'=> $desarrolloData,
            'contrato'  => $contratoDetalle
        ], JSON_UNESCAPED_UNICODE);
        // Preparar datos para insertar
        $datosContrato = [
            'cliente_id'     => $clienteId,
            'desarrollo_id'  => $desarrolloId,
            'datta_contrato' => $jsonData
        ];
        $resp = ModeloContratos::mdlCrearContrato($datosContrato);

    // 🔑 Enviar respuesta limpia para fetch
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            
            header('Content-Type: application/json');
            if ($resp === 'ok') {
                echo json_encode(['status' => 'ok', 'message' => 'Contrato creado correctamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo crear el contrato']);
            }
            exit; // 🚨 evitar que siga renderizando vistas
        }

        // Si no es AJAX, se comporta como antes
        echo $resp;
    }

    /*
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
                'created_at' => $c['created_at'] ?? '',
                'lotes_disponibles' => $c['lotes_disponibles'] ?? ''
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
                    // Nuevos campos del contrato (monto, enganche, saldo, parcialidades, penalización, etc.)
                    $row['monto_precio_inmueble']      = $json['contrato']['monto_precio_inmueble'] ?? '';
                    $row['monto_precio_inmueble_fixed'] = $json['contrato']['monto_precio_inmueble_fixed'] ?? '';
                    $row['enganche']                   = $json['contrato']['enganche'] ?? '';
                    $row['enganche_fixed']             = $json['contrato']['enganche_fixed'] ?? '';
                    $row['saldo_pago']                 = $json['contrato']['saldo_pago'] ?? '';
                    $row['saldo_pago_fixed']           = $json['contrato']['saldo_pago_fixed'] ?? '';
                    $row['parcialidades_anuales']      = $json['contrato']['parcialidades_anuales'] ?? '';
                    $row['penalizacion_10']            = $json['contrato']['penalizacion_10'] ?? '';
                    $row['penalizacion_10_fixed']      = $json['contrato']['penalizacion_10_fixed'] ?? '';
                    // Folio y rango de pago se almacenan en versiones unificadas
                    $row['folio']                      = $json['contrato']['folio'] ?? '';
                    $row['rango_pago']                 = $json['contrato']['rango_pago'] ?? '';
                    $row['vigencia_pagare']            = $json['contrato']['vigencia_pagare'] ?? '';
                    // Campos de compatibilidad antiguos
                    $row['dia_pago']                   = $json['contrato']['dia_pago'] ?? '';
                    $row['rango_compromiso_pago']      = $json['contrato']['rango_compromiso_pago'] ?? '';
                    // Nuevos campos: pago mensual y fecha de contrato
                    $row['pago_mensual']               = $json['contrato']['pago_mensual'] ?? '';
                    $row['pago_mensual_fixed']         = $json['contrato']['pago_mensual_fixed'] ?? '';
                    $row['fecha_contrato']             = $json['contrato']['fecha_contrato'] ?? '';
                    $row['fecha_contrato_fixed']       = $json['contrato']['fecha_contrato_fixed'] ?? '';
                    // Superficie convertida a letras y día de inicio
                    $row['superficie_fixed']           = $json['contrato']['superficie_fixed'] ?? '';
                    $row['dia_inicio']                 = $json['contrato']['dia_inicio'] ?? '';
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
     *//*
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
        // Superficie fija recibida desde el formulario (texto en letras)
        $superficieFixed = isset($_POST['superficie_fixed']) ? trim($_POST['superficie_fixed']) : '';
        // Nuevos campos recibidos para edición
        $montoInmueble      = isset($_POST['monto_inmueble']) ? floatval($_POST['monto_inmueble']) : 0;
        $montoInmuebleFixed = isset($_POST['monto_inmueble_fixed']) ? trim($_POST['monto_inmueble_fixed']) : '';
        $enganche           = isset($_POST['enganche']) ? floatval($_POST['enganche']) : 0;
        $engancheFixed      = isset($_POST['enganche_fixed']) ? trim($_POST['enganche_fixed']) : '';
        $saldoPago          = isset($_POST['saldo_pago']) ? floatval($_POST['saldo_pago']) : 0;
        $saldoPagoFixed     = isset($_POST['saldo_pago_fixed']) ? trim($_POST['saldo_pago_fixed']) : '';
        $parcialidades      = isset($_POST['parcialidades_anuales']) ? trim($_POST['parcialidades_anuales']) : '';
        $penalizacion       = isset($_POST['penalizacion']) ? floatval($_POST['penalizacion']) : 0;
        $penalizacionFixed  = isset($_POST['penalizacion_fixed']) ? trim($_POST['penalizacion_fixed']) : '';
        $vigenciaPagare     = isset($_POST['vigencia_pagare']) ? trim($_POST['vigencia_pagare']) : '';
        // Nuevos campos: folio, rango de pago (inicio y fin), pago mensual y fecha de contrato
        $folio              = isset($_POST['folio']) ? strtoupper(trim($_POST['folio'])) : '';
        $rangoInicioRaw     = isset($_POST['rango_pago_inicio']) ? trim($_POST['rango_pago_inicio']) : '';
        $rangoFinRaw        = isset($_POST['rango_pago_fin']) ? trim($_POST['rango_pago_fin']) : '';
        $pagoMensual        = isset($_POST['pago_mensual']) ? floatval($_POST['pago_mensual']) : 0;
        $pagoMensualFixed   = isset($_POST['pago_mensual_fixed']) ? trim($_POST['pago_mensual_fixed']) : '';
        $fechaContrato      = isset($_POST['fecha_contrato']) ? trim($_POST['fecha_contrato']) : '';
        $fechaContratoFixed = isset($_POST['fecha_contrato_fixed']) ? trim($_POST['fecha_contrato_fixed']) : '';
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
        // Contenido habitacional como texto (sin WYSIWYG), convertir a mayúsculas
        $habitacional = isset($_POST['habitacional']) ? strtoupper(trim($_POST['habitacional'])) : '';
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
        // Función para convertir una fecha YYYY-MM-DD a "DD de Mes de YYYY"
        $formatearFechaLarga = function ($fecha) {
            if (!$fecha) return '';
            $meses = [
                'enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'
            ];
            $partes = explode('-', $fecha);
            if (count($partes) === 3) {
                $anio = $partes[0];
                $mes = (int)$partes[1];
                $dia = (int)$partes[2];
                $mesNombre = $meses[$mes - 1] ?? '';
                return $dia . ' de ' . ucfirst($mesNombre) . ' de ' . $anio;
            }
            return $fecha;
        };
        // Convertir fechas a formato largo
        $entregaLong   = $formatearFechaLarga($entrega);
        $firmaLong     = $formatearFechaLarga($fechaFirma);
        $inicioLong    = $formatearFechaLarga($inicioPagos);
        $vigenciaLong  = $formatearFechaLarga($vigenciaPagare);
        $rangoInicio   = $formatearFechaLarga($rangoInicioRaw);
        $rangoFin      = $formatearFechaLarga($rangoFinRaw);
        $rangoPago     = ($rangoInicio && $rangoFin) ? ($rangoInicio . ' a ' . $rangoFin) : ($rangoInicio ?: $rangoFin);
        // Fecha del contrato en formato largo
        $fechaContratoLong = $formatearFechaLarga($fechaContrato);
        // Calcular día de inicio a partir de fecha de contrato
        $diaInicio = '';
        if ($fechaContrato) {
            $partesFechaEd = explode('-', $fechaContrato);
            if (count($partesFechaEd) === 3) {
                $diaInicio = intval($partesFechaEd[2]);
            }
        }
        $contratoDetalle = [
            'folio'                      => $folio,
            'mensualidades'              => $mensualidades,
            'superficie'                 => $superficie,
            'superficie_fixed'           => $superficieFixed,
            'fraccion_vendida'           => $fraccion,
            'entrega_posecion'           => $entregaLong,
            'fecha_firma_contrato'       => $firmaLong,
            'habitacional_colindancias'  => $habitacional,
            'inicio_pagos'               => $inicioLong,
            'tipo_contrato'              => $tipoContrato,
            // Montos y pagos
            'monto_precio_inmueble'       => number_format($montoInmueble, 2, '.', ','),
            'monto_precio_inmueble_fixed' => $montoInmuebleFixed,
            'enganche'                    => number_format($enganche, 2, '.', ','),
            'enganche_fixed'              => $engancheFixed,
            'saldo_pago'                  => number_format($saldoPago, 2, '.', ','),
            'saldo_pago_fixed'            => $saldoPagoFixed,
            'parcialidades_anuales'       => $parcialidades,
            'penalizacion_10'             => number_format($penalizacion, 2, '.', ','),
            'penalizacion_10_fixed'       => $penalizacionFixed,
            // Pago mensual
            'pago_mensual'                => number_format($pagoMensual, 2, '.', ','),
            'pago_mensual_fixed'          => $pagoMensualFixed,
            // Fecha del contrato y su versión fija
            'fecha_contrato'              => $fechaContratoLong,
            'fecha_contrato_fixed'        => $fechaContratoFixed,
            // Rango de pago tanto unido como separado
            'rango_pago_inicio'           => $rangoInicio,
            'rango_pago_fin'              => $rangoFin,
            'rango_pago'                  => $rangoPago,
            // Día de inicio
            'dia_inicio'                  => $diaInicio,
            'vigencia_pagare'             => $vigenciaLong
        ];
        $jsonData = json_encode([
            'cliente' => $clienteData,
            'desarrollo' => $desarrolloData,
            'contrato' => $contratoDetalle
        ], JSON_UNESCAPED_UNICODE);
        $respuesta = ModeloContratos::mdlEditarContrato($contratoId, $jsonData);
        echo $respuesta;
    }*/

    /**
     * Genera un documento de contrato en formato DOCX y PDF a partir del registro
     * existente de un contrato. Este método lee los datos JSON almacenados en la
     * tabla argus_contratos_data, reemplaza los placeholders de la plantilla
     * correspondiente y crea un archivo ZIP con ambos formatos listo para descargar.
     *
     * La identificación del contrato se recibe por GET o POST mediante el
     * parámetro 'contrato_id'. Si la plantilla o los datos no existen, devuelve
     * un mensaje de error en formato JSON.
     *
     * @return void
     */
    static public function ctrGenerarDocumento()
    {
        // Obtener el identificador del contrato desde GET o POST
        $contratoId = null;
        if (isset($_GET['contrato_id'])) {
            $contratoId = intval($_GET['contrato_id']);
        } elseif (isset($_POST['contrato_id'])) {
            $contratoId = intval($_POST['contrato_id']);
        }
        if (!$contratoId) {
            echo json_encode(['status' => 'error', 'msg' => 'ID de contrato no proporcionado']);
            return;
        }
        // Recuperar el contrato desde la base de datos
        $contratoRow = ModeloContratos::mdlMostrarContratoPorId($contratoId);
        if (!$contratoRow) {
            echo json_encode(['status' => 'error', 'msg' => 'Contrato no encontrado']);
            return;
        }
        // Decodificar JSON de datos
        $jsonData = $contratoRow['datta_contrato'] ?? null;
        $data = $jsonData ? json_decode($jsonData, true) : null;
        if (!$data || !is_array($data)) {
            echo json_encode(['status' => 'error', 'msg' => 'Datos del contrato no válidos']);
            return;
        }
        $cliente = $data['cliente'] ?? [];
        $desarrollo = $data['desarrollo'] ?? [];
        $contrato = $data['contrato'] ?? [];
        // Obtener tipo de contrato para seleccionar la plantilla
        $tipoContrato = $contrato['tipo_contrato'] ?? null;
        if (!$tipoContrato) {
            echo json_encode(['status' => 'error', 'msg' => 'Tipo de contrato no definido']);
            return;
        }
        // Obtener plantilla por tipo
        $plantilla = ModeloPlantillas::mdlObtenerPlantillaPorTipo($tipoContrato);
        if (!$plantilla || empty($plantilla['ruta_archivo'])) {
            echo json_encode(['status' => 'error', 'msg' => 'No se encontró una plantilla para el tipo de contrato']);
            return;
        }
        $plantillaPath = $plantilla['ruta_archivo'];
        // Asegurar que el archivo exista en el servidor
        if (!file_exists($plantillaPath)) {
            // Permitir rutas relativas dentro de la carpeta app_Argus_cp
            $relPath = __DIR__ . '/../' . ltrim($plantillaPath, '/');
            if (file_exists($relPath)) {
                $plantillaPath = $relPath;
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'El archivo de plantilla no existe en el servidor']);
                return;
            }
        }
        /*
        // Construir arreglo de reemplazos para placeholders
        $placeholders = [
            'CLIENTE_NOMBRE' => $cliente['nombre'] ?? '',
            'CLIENTE_NACIONALIDAD' => $cliente['nacionalidad'] ?? '',
            'CLIENTE_FECHA_NACIMIENTO' => $cliente['fecha_nacimiento'] ?? '',
            'CLIENTE_DOMICILIO' => $cliente['domicilio'] ?? '',
            'CLIENTE_RFC' => $cliente['rfc'] ?? '',
            'CLIENTE_CURP' => $cliente['curp'] ?? '',
            'DESARROLLO_NOMBRE' => $desarrollo['nombre'] ?? '',
            'DESARROLLO_CLAVE_CATASTRAL' => $desarrollo['clave_catastral'] ?? '',
            'DESARROLLO_SUPERFICIE' => $desarrollo['superficie'] ?? '',
            'DESARROLLO_DESCRIPCION' => $desarrollo['descripcion'] ?? '',
            'DESARROLLO_PRECIO_TOTAL' => $desarrollo['precio_total'] ?? '',
            'CONTRATO_FECHA_FIRMA' => $contrato['fecha_firma_contrato'] ?? '',
            'CONTRATO_FRACCION_VENDIDA' => $contrato['fraccion_vendida'] ?? '',
            'CONTRATO_MENSUALIDADES' => $contrato['mensualidades'] ?? '',
            'CONTRATO_INICIO_PAGOS' => $contrato['inicio_pagos'] ?? '',
            'CONTRATO_ENTREGA_POSESION' => $contrato['entrega_posecion'] ?? ''
        ];*/

        // Construir arreglo de reemplazos para placeholders
        $placeholders = [
        // Cliente
        'CLIENTE_NOMBRE'             => $cliente['nombre'] ?? '',
        'CLIENTE_NACIONALIDAD'       => $cliente['nacionalidad'] ?? '',
        'CLIENTE_FECHA'              => $cliente['fecha'] ?? '',
        'CLIENTE_RFC'                => $cliente['rfc'] ?? '',
        'CLIENTE_CURP'               => $cliente['curp'] ?? '',
        'CLIENTE_INE'                => $cliente['ine'] ?? '',
        'CLIENTE_ESTADO_CIVIL'       => $cliente['estado_civil'] ?? '',
        'CLIENTE_OCUPACION'          => $cliente['ocupacion'] ?? '',
        'CLIENTE_TELEFONO'           => $cliente['telefono'] ?? '',
        'CLIENTE_DOMICILIO'          => $cliente['domicilio'] ?? '',
        'CLIENTE_EMAIL'              => $cliente['email'] ?? '',
        'CLIENTE_BENEFICIARIO'       => $cliente['beneficiario'] ?? '',
        'CLIENTE_EDAD'               => $cliente['edad'] ?? '',
        'CLIENTE_REFERENCIA'         => $cliente['referencia'] ?? '',
            

        // Desarrollo
        'DESARROLLO_ID'              => $desarrollo['id'] ?? '',
        'DESARROLLO_NOMBRE'          => $desarrollo['nombre'] ?? '',
        'DESARROLLO_TIPO_CONTRATO'   => $desarrollo['tipo_contrato'] ?? '',
        'DESARROLLO_DESCRIPCION'     => $desarrollo['descripcion'] ?? '',
        'DESARROLLO_SUPERFICIE'      => $desarrollo['superficie'] ?? '',
        'DESARROLLO_CLAVE_CATASTRAL' => $desarrollo['clave_catastral'] ?? '',
        'DESARROLLO_LOTES'           => $desarrollo['lotes_disponibles'] ?? '',
        'DESARROLLO_PRECIO_LOTE'     => $desarrollo['precio_lote'] ?? '',
        'DESARROLLO_PRECIO_TOTAL'    => $desarrollo['precio_total'] ?? '',
        'DESARROLLO_CREATED_AT'      => $desarrollo['created_at'] ?? '',

        // Contrato
        'CONTRATO_FOLIO'                 => $contrato['folio'] ?? '',
        'CONTRATO_MENSUALIDADES'         => $contrato['mensualidades'] ?? '',
        //'CONTRATO_SUPERFICIE'     => $contrato['superficie'] ?? '',
        'CONTRATO_SUPERFICIE'            => $contrato['superficie_fixed'] ?? '',
        'CONTRATO_FRACCION_VENDIDA'      => $contrato['fraccion_vendida'] ?? '',
        'CONTRATO_ENTREGA_POSESION'      => $contrato['entrega_posecion'] ?? '',
        'CONTRATO_FECHA_FIRMA'           => $contrato['fecha_firma_contrato'] ?? '',
        'CONTRATO_COLINDANCIAS'          => $contrato['habitacional_colindancias'] ?? '',
        'CONTRATO_INICIO_PAGOS'          => $contrato['inicio_pagos'] ?? '',
        'CONTRATO_TIPO'                  => $contrato['tipo_contrato'] ?? '',
        //'CONTRATO_MONTO'                 => $contrato['monto_precio_inmueble'] ?? '',
        'CONTRATO_PRECIO_INMUEBLE'       => $contrato['monto_precio_inmueble_fixed'] ?? '',
        //'CONTRATO_ENGANCHE'              => $contrato['enganche'] ?? '',
        'CONTRATO_ENGANCHE'        => $contrato['enganche_fixed'] ?? '',
        //'CONTRATO_SALDO'                 => $contrato['saldo_pago'] ?? '',
        'CONTRATO_SALDO'           => $contrato['saldo_pago_fixed'] ?? '',
        'CONTRATO_PARCIALIDADES_ANUALES' => $contrato['parcialidades_anuales'] ?? '',
        'CONTRATO_PENALIZACION'          => $contrato['penalizacion_10'] ?? '',
        'CONTRATO_PENALIZACION_FIXED'    => $contrato['penalizacion_10_fixed'] ?? '',
        //'CONTRATO_PAGO_MENSUAL'          => $contrato['pago_mensual'] ?? '',
        'CONTRATO_PAGO_MENSUAL'    => $contrato['pago_mensual_fixed'] ?? '',
        'CONTRATO_FECHA_N'                 => $contrato['fecha_contrato'] ?? '',
        'CONTRATO_FECHA_T'                 => $contrato['fecha_contrato_fixed'] ?? '',
        'CONTRATO_INICIO_PAGO'          => $contrato['rango_pago_inicio'] ?? '',
        'CONTRATO_FIN_PAGO'             => $contrato['rango_pago_fin'] ?? '',
        'CONTRATO_RANGO'                 => $contrato['rango_pago'] ?? '',
        'CONTRATO_DIA_INICIO'            => $contrato['dia_inicio'] ?? '',
        'CONTRATO_VIGENCIA_PAGARE'       => $contrato['vigencia_pagare'] ?? ''
    ];



        // Crear directorio temporal si no existe
        $tmpDir = __DIR__ . '/../tmp';
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0777, true);
        }
        // Construir nombres de archivos según cliente, tipo y fecha
        $nombreCliente = preg_replace('/[^A-Za-z0-9_-]+/', '_', $cliente['nombre'] ?? 'cliente');
        $tipoNom = preg_replace('/[^A-Za-z0-9_-]+/', '_', $tipoContrato ?? 'contrato');
        $fecha = preg_replace('/[^0-9-]+/', '_', $contrato['fecha_firma_contrato'] ?? date('Y-m-d'));
        $baseName = $nombreCliente . '_' . $tipoNom . '_' . $fecha;
        $docxPath = $tmpDir . '/' . $baseName . '.docx';
        $pdfPath = $tmpDir . '/' . $baseName . '.pdf';
        $zipPath = $tmpDir . '/' . $baseName . '.zip';
        try {
            // Cargar plantilla y reemplazar variables
            // Se usa el alias completamente calificado para evitar conflictos
            $template = new \PhpOffice\PhpWord\TemplateProcessor($plantillaPath);
            foreach ($placeholders as $clave => $valor) {
                $template->setValue($clave, $valor);
            }
            $template->saveAs($docxPath);
            // Configurar biblioteca de generación de PDF
            // PHPWord requiere definir el renderizador de PDF y su ruta.
            // Usamos Dompdf instalado mediante Composer.
            $dompdfPath = dirname(__DIR__) . '/vendor/dompdf/dompdf';
            if (class_exists('\\PhpOffice\\PhpWord\\Settings')) {
                \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);
                \PhpOffice\PhpWord\Settings::setPdfRendererPath($dompdfPath);
            }
            // Convertir a PDF
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($docxPath);
            $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($pdfPath);
            // Crear ZIP con ambos archivos
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
                $zip->addFile($docxPath, basename($docxPath));
                $zip->addFile($pdfPath, basename($pdfPath));
                $zip->close();
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'No se pudo crear el archivo ZIP']);
                return;
            }
            // Devolver ruta relativa (respecto al directorio público)
            echo json_encode([
                'status' => 'ok',
                'zip' => 'tmp/' . basename($zipPath),
                'nombre' => basename($zipPath)
            ]);
        } catch (\Throwable $e) {
            echo json_encode(['status' => 'error', 'msg' => 'Error al generar el contrato: ' . $e->getMessage()]);
        }
    }

    /**
     * Proceso completo para crear un cliente y su contrato en un único formulario.
     * Este método recoge los datos de cliente y contrato enviados desde la ruta
     * crearContrato y los guarda en sus respectivas tablas. Devuelve cadenas
     * indicativas de éxito o error directamente al navegador.
     *//*
    static public function ctrCrearContratoCompletoLC()
    {
        if (!isset($_POST['crearContratoCompleto'])) {
            return;
        }
        if (!isset($_SESSION['iniciarSesion']) || $_SESSION['iniciarSesion'] !== 'ok') {
            echo 'error_sesion';
            return;
        }
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            echo 'error_token';
            return;
        }
        // Normalizar keys del formulario a las que espera el controlador
        $aliases = [
        'nombre_cliente'           => 'cliente_nombre',
        'nacionalidad_cliente'     => 'cliente_nacionalidad',
        'fecha_nacimiento_cliente' => 'cliente_fecha_nacimiento',
        'rfc_cliente'              => 'cliente_rfc',
        'curp_cliente'             => 'cliente_curp',
        'ine_cliente'              => 'cliente_ine',
        'estado_civil_cliente'     => 'cliente_estado_civil',
        'ocupacion_cliente'        => 'cliente_ocupacion',
        'telefono_cliente'         => 'cliente_telefono',
        'domicilio_cliente'        => 'cliente_domicilio',
        'email_cliente'            => 'cliente_email',
        'beneficiario_cliente'     => 'cliente_beneficiario',
        // contrato
        'superficie'               => 'contrato_superficie',
        ];

        foreach ($aliases as $expected => $fromForm) {
        if (!isset($_POST[$expected]) && isset($_POST[$fromForm])) {
            $_POST[$expected] = $_POST[$fromForm];
        }
        }

        // Helper para convertir fecha YYYY-MM-DD a "DD de Mes de YYYY"
        $formatearFechaLarga = function ($fecha) {
            if (!$fecha) return '';
            $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO',
                    'JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
            $partes = explode('-', $fecha);
            if (count($partes) === 3) {
                $anio = $partes[0];
                $mes = (int)$partes[1];
                $dia = (int)$partes[2];
                $mesNombre = $meses[$mes - 1] ?? '';
                return $dia . ' DE ' . ucfirst($mesNombre) . ' DE ' . $anio;
            }
            return $fecha;
        };

        // Formatear montos numéricos (ej: 240,509.03)
        $formatearMonto = function ($valor) {
            if ($valor === null || $valor === '') return '';
            return number_format((float)$valor, 2, '.', ',');
        };

        // Convertir número a letras (requiere extensión intl habilitada)
        $numeroALetras = function ($numero) {
            if (!class_exists('NumberFormatter')) return $numero;
            $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
            $entero = floor($numero);
            $decimales = round(($numero - $entero) * 100);
            $letras = strtoupper($formatter->format($entero));
            return $numero . " (" . $letras . " PESOS " . str_pad($decimales, 2, '0', STR_PAD_LEFT) . "/100 M.N.)";
        };

        // ================= CLIENTE =================
        $datosCliente = [
            'nombre'       => trim($_POST['nombre_cliente']),
            'nacionalidad' => trim($_POST['nacionalidad_cliente']),
            'fecha'        => $_POST['fecha_nacimiento_cliente'] ?? null,
            'rfc'          => trim($_POST['rfc_cliente']),
            'curp'         => trim($_POST['curp_cliente']),
            'ine'          => trim($_POST['ine_cliente']),
            'estado_civil' => trim($_POST['estado_civil_cliente']),
            'ocupacion'    => trim($_POST['ocupacion_cliente']),
            'telefono'     => trim($_POST['telefono_cliente']),
            'domicilio'    => trim($_POST['domicilio_cliente']),
            'email'        => trim($_POST['email_cliente']),
            'beneficiario' => trim($_POST['beneficiario_cliente']),
            // Edad calculada del cliente
            'edad'         => isset($_POST['cliente_edad']) ? intval($_POST['cliente_edad']) : ''
        ];
        $clienteId = ModeloClientes::mdlAgregarClienteRetId($datosCliente);
        if (!$clienteId) {
            echo 'error_cliente';
            return;
        }

        // ================= CONTRATO =================
        $desarrolloId = intval($_POST['desarrollo_id']);
        $folio = trim($_POST['folio'] ?? '');
        $mensualidades = intval($_POST['mensualidades']);
        $superficie = trim($_POST['superficie']);

        // Fracciones
        $fraccion = '';
        if (!empty($_POST['fracciones'])) {
            $decoded = json_decode($_POST['fracciones'], true);
            if (is_array($decoded)) {
                $fraccion = implode(',', array_filter($decoded));
            } else {
                $fraccion = trim($_POST['fracciones']);
            }
        }

        // Fechas
        $fechaFirma      = $formatearFechaLarga($_POST['fecha_firma'] ?? '');
        $entregaPosecion = $formatearFechaLarga($_POST['entrega_posecion'] ?? '');
        $inicioPagos     = $formatearFechaLarga($_POST['inicio_pagos'] ?? '');
        $habitacional    = trim($_POST['habitacional']);
        $tipoContrato    = trim($_POST['tipo_contrato']);

        $rangoInicio = $formatearFechaLarga($_POST['rango_pago_inicio'] ?? '');
        $rangoFin    = $formatearFechaLarga($_POST['rango_pago_fin'] ?? '');
        $rangoPago   = $rangoInicio && $rangoFin ? "$rangoInicio a $rangoFin" : ($rangoInicio ?: $rangoFin);

        // Montos
        $montoInmueble  = floatval($_POST['monto_inmueble'] ?? 0);
        $enganche       = floatval($_POST['enganche'] ?? 0);
        $saldoPago      = floatval($_POST['saldo_pago'] ?? 0);
        $penalizacion   = floatval($_POST['penalizacion'] ?? 0);
        $pagoMensual    = floatval($_POST['pago_mensual'] ?? 0);

        $vigenciaPagare = $formatearFechaLarga($_POST['vigencia_pagare'] ?? '');

        // Construcción del detalle
        $contratoDetalle = [
            'folio'                       => $folio,
            'mensualidades'               => $mensualidades,
            'superficie'                  => $superficie,
            'fraccion_vendida'            => $fraccion,
            'entrega_posecion'            => $entregaPosecion,
            'fecha_firma_contrato'        => $fechaFirma,
            'habitacional_colindancias'   => $habitacional,
            'inicio_pagos'                => $inicioPagos,
            'tipo_contrato'               => $tipoContrato,
            'monto_precio_inmueble'       => $montoInmueble,
            'monto_precio_inmueble_fixed' => $numeroALetras($montoInmueble),
            'enganche'                    => $enganche,
            'enganche_fixed'              => $numeroALetras($enganche),
            'saldo_pago'                  => $saldoPago,
            'saldo_pago_fixed'            => $numeroALetras($saldoPago),
            'parcialidades_anuales'       => $_POST['parcialidades_anuales'] ?? '',
            'penalizacion_10'             => $penalizacion,
            'penalizacion_10_fixed'       => $numeroALetras($penalizacion),
            'pago_mensual'                => $pagoMensual,
            'pago_mensual_fixed'          => $numeroALetras($pagoMensual),
            'rango_pago'                  => $rangoPago,
            'vigencia_pagare'             => $vigenciaPagare
        ];

        // ================= JSON FINAL =================
        $clienteData    = $datosCliente;
        $desarrolloData = ModeloDesarrollos::mdlMostrarDesarrolloPorId($desarrolloId);

        $jsonData = json_encode([
            'cliente'    => $clienteData,
            'desarrollo' => $desarrolloData,
            'contrato'   => $contratoDetalle
        ], JSON_UNESCAPED_UNICODE);

        $datosContrato = [
            'cliente_id'     => $clienteId,
            'desarrollo_id'  => $desarrolloId,
            'datta_contrato' => $jsonData
        ];

        $res = ModeloContratos::mdlCrearContrato($datosContrato);
        echo $res;
    }*/

}