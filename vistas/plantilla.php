<?php
/**
 * Plantilla principal de la aplicación Argus (MVC).
 * Estructura la cabecera, navegación y vista de contenido según la ruta.
 */
date_default_timezone_set('America/Mexico_City');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 1 Jul 2000 05:00:00 GMT');
// Usamos un nombre personalizado de sesión para evitar colisiones con otras apps
session_name('argus_session');
session_start();

// Generar un token CSRF si no existe en la sesión. Este token se insertará en los formularios y se validará en los controladores.
if (empty($_SESSION['csrf_token'])) {
    // bin2hex(random_bytes()) genera una cadena segura para usar como token
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="vistas/argus_ico.ico" type="image/x-icon">
    <!-- Cambiar título global -->
    <title>Contratos Grupo Argus</title>
    <!-- CSS principales -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="vistas/css/custom.css">

    <!-- Summernote WYSIWYG editor -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css">

    <!-- Cargar SweetAlert2 antes de que se ejecute cualquier script que lo utilice -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    
</head>
<body class="hold-transition sidebar-mini">
<?php
if (isset($_SESSION['iniciarSesion']) && $_SESSION['iniciarSesion'] === 'ok') {
    echo '<div class="wrapper">';
    include 'modulos/cabezote.php';
    include 'modulos/menu.php';
    // Envolvemos el contenido en un content-wrapper para respetar la estructura de AdminLTE
    echo '<div class="content-wrapper">';
    // Determinar la ruta solicitada
    if (isset($_GET['ruta'])) {
        $ruta = $_GET['ruta'];
        $permitidas = ['inicio','clientes','contratos','desarrollos','roles','parametros','crearContrato','salir'];
        if (in_array($ruta, $permitidas)) {
            include 'modulos/' . $ruta . '.php';
        } else {
            include 'modulos/404.php';
        }
    } else {
        include 'modulos/inicio.php';
    }
    echo '</div>'; // cierre de content-wrapper
    include 'modulos/footer.php';
    echo '</div>';
} else {
    include 'modulos/login.php';
}
?>
<!-- JS principales -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js"></script>
<script src="vistas/js/app.js"></script>
</body>
</html>