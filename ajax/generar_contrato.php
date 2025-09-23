<?php
/**
 * Endpoint AJAX para la generación de contratos.
 *
 * Este archivo actúa como punto de entrada para la creación de documentos a partir
 * de contratos existentes. Recupera el ID de contrato desde la URL (GET o POST),
 * invoca el método del controlador que se encarga de generar los archivos DOCX y
 * PDF y, finalmente, devuelve un JSON con el estado del proceso y la ruta del
 * archivo ZIP resultante.
 */

// Incluir controladores y modelos necesarios. Se utilizan rutas relativas
// porque este archivo vive dentro de la carpeta ajax.
require_once dirname(__DIR__) . '/controladores/contratos.controlador.php';
require_once dirname(__DIR__) . '/modelos/contratos.modelo.php';
require_once dirname(__DIR__) . '/modelos/plantillas.modelo.php';
require_once dirname(__DIR__) . '/modelos/clientes.modelo.php';
require_once dirname(__DIR__) . '/modelos/desarrollos.modelo.php';
require_once dirname(__DIR__) . '/modelos/conexion.php';

// Cargar el autoload de Composer para utilizar bibliotecas externas como PHPWord y Dompdf.
// Se intenta localizar vendor/autoload.php en varias ubicaciones relativas.
// Si ninguna coincide, se asume que las clases no estarán disponibles y se producirá un error.
$possibleAutoloads = [
    dirname(__DIR__) . '/vendor/autoload.php',        // /app_Argus_cp/vendor/autoload.php
    dirname(__DIR__, 2) . '/vendor/autoload.php',     // ../../vendor/autoload.php (cuando vendor está fuera de app)
    __DIR__ . '/../vendor/autoload.php',              // /ajax/../vendor/autoload.php
];
foreach ($possibleAutoloads as $auto) {
    if (file_exists($auto)) {
        require_once $auto;
        break;
    }
}

// Invocar el método del controlador para generar el documento. Este método
// imprime la respuesta en formato JSON. Si se desea modificar la lógica de
// entrada o salida, hacerlo en ctrGenerarDocumento().
ControladorContratos::ctrGenerarDocumento();