<?php
$host = "localhost";
$dbname = "perfumeriaa";
$username = "root";  // Usuario de tu base de datos
$password = "";      // Contraseña (vacía en XAMPP por defecto)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>