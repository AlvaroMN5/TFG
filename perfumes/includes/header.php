<?php
require_once __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo Perfumes - <?= htmlspecialchars($pageTitle ?? 'Inicio') ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>css/estilos.css">
  <link rel="icon" href="<?= BASE_URL ?>images/products/logo.png">
</head>
<body>
<header class="header" id="header">
  <nav class="navbar">
    <div class="logo">
      <img src="<?= BASE_URL ?>images/products/logo.png" alt="Todo Perfumes">
     
    </div>
    
    <ul class="nav-menu">
      <li class="nav-item"><a href="<?= BASE_URL ?>index.php" class="nav-link">Inicio</a></li>
      <li class="nav-item"><a href="<?= BASE_URL ?>products.php" class="nav-link">Perfumes</a></li>
      <li class="nav-item"><a href="<?= BASE_URL ?>cart.php" class="nav-link">Mi Cesta</a></li>
      <li class="nav-item"><a href="<?= BASE_URL ?>contact.php" class="nav-link">Contacto</a></li>
    </ul>

    <div class="nav-buttons">
      <?php if (isset($_SESSION['user'])): ?>
        <div class="user-menu">
          <div class="user-avatar" onclick="toggleUserDropdown()">
            <img src="<?= BASE_URL ?>images/products/avatar.png" alt="Avatar">
            <span class="user-name"><?= htmlspecialchars($_SESSION['user']['username']) ?></span>
            <span class="chevron">▾</span>
          </div>
          <div class="user-dropdown" id="userDropdown">
            <a href="<?= BASE_URL ?>profile.php">Mi Perfil</a>
            <a href="<?= BASE_URL ?>orders.php">Mis Compras</a>
            <a href="<?= BASE_URL ?>logout.php">Cerrar Sesión</a>
          </div>
        </div>
      <?php else: ?>
        <a href="<?= BASE_URL ?>login.php" class="btn-login">Iniciar Sesión</a>
        <a href="<?= BASE_URL ?>register.php" class="btn-register">Registrarse</a>
      <?php endif; ?>
    </div>

    <button class="hamburger">☰</button>
  </nav>
</header>

<main class="main-content">

<!-- Cargamos nuestro JS personalizado que contiene:
     - toggleUserDropdown()
     - animaciones fade-in
     - zoom en imágenes de productos -->
<script src="<?= BASE_URL ?>assets/js/main_custom.js" defer></script>
