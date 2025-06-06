<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_login(); // Asegura que el usuario está autenticado

// Obtener el ID del usuario desde la sesión
$userId = $_SESSION['user']['id'];

// Consultar los datos del usuario desde la base de datos
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Error: usuario no encontrado.";
    exit;
}

// Obtener historial de pedidos
$orders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$orders->execute([$userId]);
$orders = $orders->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Mi Perfil</h2>
    
    <div class="profile-grid">
        <div class="profile-info">
            <h3>Información Personal</h3>
            <form action="profile.php" method="post">
                <div class="form-group">
                    <label for="username">Nombre de Usuario:</label>
                    <input type="text" id="username" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="full_name">Nombre Completo:</label>
                    <input type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>">
                </div>

                <div class="form-group">
