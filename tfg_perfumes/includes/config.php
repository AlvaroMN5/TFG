<?php
// Verifica si las constantes ya están definidas antes de declararlas
if (!defined('BASE_URL')) {
    // Configuración de rutas
    $base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('%20', ' ', dirname($_SERVER['SCRIPT_NAME']));
    define('BASE_URL', rtrim($base_url, '/') . '/');
    
    // Configuración de la base de datos
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'tfg_perfumes');
    
    // Configuración del sitio
    define('SITE_NAME', 'Tienda de Perfumes TFG');
    
    // Control de stock (añade esto)
    define('HAS_STOCK', false);
}
?>