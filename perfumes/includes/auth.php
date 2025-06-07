<?php
// Asegura que la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Requiere login para acceder
function require_login() {
    if (!isset($_SESSION['user'])) {
        header("Location: /prueba_php/proyecto/login.php");
        exit;
    }
}

// Requiere que el usuario sea administrador
function require_admin() {
    require_login();
   if (!isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] !== 1) {
    header("Location: ../index.php");
    exit;
}

}

// Cierre de sesión (opcional, si quieres incluirlo aquí)
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: /prueba_php/proyecto/login.php");
    exit;
}
?>
