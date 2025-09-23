<?php
/**
 * Servicio AJAX para convertir un número a su representación en letras con el
 * formato monetario requerido. Este endpoint recibe un valor numérico a
 * través de POST (parámetro "num") y devuelve una cadena en el formato
 * "<numero> (LETRAS PESOS 00/100 M.N.)". Utiliza la extensión intl si está
 * disponible; de lo contrario, devuelve simplemente el número sin formato.
 */

header('Content-Type: text/plain; charset=UTF-8');

// Obtener el número desde la petición POST
$num = isset($_POST['num']) ? floatval($_POST['num']) : 0;

// Función para convertir número a letras utilizando NumberFormatter.
// Si la extensión intl no está habilitada, devuelve el número tal cual.
function numeroALetras($numero)
{
    // Verificar si la clase existe (extensión intl habilitada)
    if (class_exists('NumberFormatter')) {
        try {
            $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
            $letras = mb_strtoupper($formatter->format($numero));
            // Asegurar que la parte decimal siempre sea "00/100"
            return $numero . ' (' . $letras . ' PESOS 00/100 M.N.)';
        } catch (Exception $e) {
            // En caso de error con NumberFormatter, regresar número
            return (string)$numero;
        }
    }
    // Fallback simple: devolver el número como cadena
    return (string)$numero;
}

echo numeroALetras($num);