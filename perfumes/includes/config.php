<?php
// Configuración básica
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'perfume_shop');

// Ruta relativa desde la raíz del servidor 
define('BASE_PATH', '/prueba_php/proyecto/');

// URL completa (http://) para enlaces en HTML, CSS y JS
define('BASE_URL', 'http://localhost/prueba_php/proyecto/');

// Configuración de PayPal (simulada)
define('PAYPAL_EMAIL', 'tucorreo@business.example.com');

// Conexión a la base de datos
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: No se pudo conectar. " . $e->getMessage());
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
