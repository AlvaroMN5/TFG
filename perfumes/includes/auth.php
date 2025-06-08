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

// Después:
if (isset($_GET['logout'])) {
    // 1. Si existe carrito, lo guardamos en una variable temporal
    $savedCart = $_SESSION['cart'] ?? [];

    // 2. Limpiamos sólo los datos de usuario (no destruimos toda la sesión)
    unset($_SESSION['user'], $_SESSION['is_admin'], $_SESSION['user_id']);
    
    // 3. Volvemos a poner el carrito en la sesión
    $_SESSION['cart'] = $savedCart;

    // 4. Opcional: regenerar ID de sesión para más seguridad
    session_regenerate_id(true);

    // 5. Redirigir al login
    header("Location: ../login.php");
    exit;
}

?>
