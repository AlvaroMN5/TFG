<?php
session_start();

// Eliminar todas las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Redirigir al inicio o login
header("Location: ../index.php"); // Cambia si tu página de inicio tiene otra ruta
exit;
