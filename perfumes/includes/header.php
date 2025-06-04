<?php
session_start(); // Siempre al inicio para manejar sesiones
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Perfumes</title>
    <link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/tema_clar.css">

</head>
<body>
    <header>
        <div class="container">
            <div class="logo">PerfumeShop</div>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="products.php">Perfumes</a></li>
                    <li><a href="cart.php">Carrito</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                            <li><a href="admin/" class="admin-link">Panel Admin</a></li>
                        <?php endif; ?>
                        <li><a href="profile.php">Mi Cuenta</a></li>
                        <li><a href="logout.php">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Iniciar Sesión</a></li>
                        <li><a href="register.php">Registrarse</a></li>
                    <?php endif; ?>
                    <!-- Botón de contacto visible para todos -->
                    <li><a href="#" id="contactBtn">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">