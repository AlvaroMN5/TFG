<?php
if (!defined('BASE_URL')) {
    // BASE_URL dinámica según dominio y subcarpeta
    $base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('%20', ' ', dirname($_SERVER['SCRIPT_NAME']));
    

    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'tfg_perfumes');
define('BASE_URL', '/prueba_php/tfg_perfumes/');

    define('SITE_NAME', 'Tienda de Perfumes TFG');
    define('HAS_STOCK', false);
}
?>
