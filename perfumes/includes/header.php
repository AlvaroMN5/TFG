    <?php
// header.php - Versión corregida
require_once __DIR__ . '/config.php';
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo Perfumes - <?php echo $pageTitle ?? 'Inicio'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="images/favicon.ico">
    </head>
    <body>
    <header class="header" id="header">
        <nav class="navbar">
        <div class="logo">
            <img src="images/logo.png" alt="Todo Perfumes">
            <h1>Todo Perfumes</h1>
        </div>
        
       <ul class="nav-menu">
            <li class="nav-item"><a href="<?php echo BASE_URL; ?>index.php" class="nav-link">Inicio</a></li>
            <li class="nav-item"><a href="<?php echo BASE_URL; ?>products.php" class="nav-link">Perfumes</a></li>
            <li class="nav-item"><a href="<?php echo BASE_URL; ?>cart.php" class="nav-link">Mi Cesta</a></li>
            <li class="nav-item"><a href="<?php echo BASE_URL; ?>contact.php" class="nav-link" id="contact-link">Contacto</a></li>
        </ul>
        <div class="nav-buttons">   
            <a href="login.php" class="btn-login">Iniciar Sesión</a>
            <a href="register.php" class="btn-register">Registrarse</a>
        </div>
        
        <button class="hamburger">☰</button>
        </nav>
    </header>
    
    <main class="main-content">

    