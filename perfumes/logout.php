<?php
// logout.php
session_start();

// 1) Guardamos el carrito si existe
$savedCart = $_SESSION['cart'] ?? [];

// 2) Limpiamos todo lo demás (pero NO destruimos la sesión entera)
session_unset();

// 3) Regeneramos el ID de sesión por seguridad
session_regenerate_id(true);

// 4) Restauramos el carrito
if (!empty($savedCart)) {
    $_SESSION['cart'] = $savedCart;
}

// 5) Redirigimos al login
header("Location: login.php");
exit;
