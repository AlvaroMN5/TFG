<?php
// Iniciar sesión
session_start();

// Incluir archivos esenciales
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';
require_once 'auth.php';

// Conexión a la base de datos
$db = new Database();
$conn = $db->getConnection();

// Inicializar carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

