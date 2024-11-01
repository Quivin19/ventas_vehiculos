<?php
// Encabezado para permitir solicitudes de cualquier origen.
header('Access-Control-Allow-Origin: *');
// Configuración de la zona horaria.
date_default_timezone_set('America/El_Salvador');

// Constantes para las credenciales de conexión con la base de datos del proyecto de vehículos.
define('SERVER', 'localhost');
define('DATABASE', 'vehicle_management'); 
define('USERNAME', 'root');
define('PASSWORD', '');
