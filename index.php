<?php
/*
 * Archivo de entrada principal para la aplicación Argus en arquitectura MVC.
 * Aquí se cargan los controladores y modelos necesarios y se invoca
 * al controlador de plantilla para mostrar la vista correspondiente.
 */
require_once 'controladores/plantilla.controlador.php';
require_once 'controladores/usuarios.controlador.php';
require_once 'controladores/clientes.controlador.php';
require_once 'controladores/desarrollos.controlador.php';
require_once 'controladores/contratos.controlador.php';
require_once 'controladores/parametros.controlador.php';

require_once 'modelos/conexion.php';
require_once 'modelos/usuarios.modelo.php';
require_once 'modelos/clientes.modelo.php';
require_once 'modelos/desarrollos.modelo.php';
require_once 'modelos/contratos.modelo.php';
require_once 'modelos/variables.modelo.php';
require_once 'modelos/plantillas.modelo.php';

// Iniciar la plantilla
$plantilla = new ControladorPlantilla();
$plantilla->ctrPlantilla();