<?php
session_start();
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT email FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            die("El email ya está registrado");
        }

        // Insertar nuevo usuario
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $password]);

        $_SESSION['usuario'] = $email;
        header("Location: ../index.html"); // Redirigir al inicio
        exit();

    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>  