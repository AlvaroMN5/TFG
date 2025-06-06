<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';


// Solo administradores pueden acceder
if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] !== 1) {
    header("Location: ../login.php");
    exit;
}

// Obtener estadísticas
$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$products_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$orders_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(total) FROM orders WHERE status = 'completed'")->fetchColumn();
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Panel de Administración</h2>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Usuarios</h3>
            <p><?= $users_count ?></p>
        </div>
        <div class="stat-card">
            <h3>Productos</h3>
            <p><?= $products_count ?></p>
        </div>
        <div class="stat-card">
            <h3>Pedidos</h3>
            <p><?= $orders_count ?></p>
        </div>
        <div class="stat-card">
            <h3>Ingresos</h3>
            <p>$<?= number_format($revenue, 2) ?></p>
        </div>
    </div>
    
    <div class="admin-links">
        <a href="products.php" class="btn">Gestionar Productos</a>
        <a href="users.php" class="btn">Gestionar Usuarios</a>
        <a href="orders.php" class="btn">Ver Pedidos</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
