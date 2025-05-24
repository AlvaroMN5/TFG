<?php
require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas

// Verificar si es administrador
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$pageTitle = 'Panel de Administración';
include '../templates/admin_header.php';

// Estadísticas rápidas
try {
    $usersCount = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $productsCount = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $ordersCount = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
} catch (PDOException $e) {
    die("Error al cargar estadísticas: " . $e->getMessage());
}
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Usuarios</h5>
                <p class="card-text display-4"><?= $usersCount ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Productos</h5>
                <p class="card-text display-4"><?= $productsCount ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Pedidos</h5>
                <p class="card-text display-4"><?= $ordersCount ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <a href="<?= BASE_URL ?>admin/products.php" class="btn btn-outline-primary mb-2">Gestionar Productos</a>
                <a href="<?= BASE_URL ?>admin/users.php" class="btn btn-outline-secondary mb-2">Gestionar Usuarios</a>
                <a href="<?= BASE_URL ?>admin/orders.php" class="btn btn-outline-success mb-2">Ver Pedidos</a>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/admin_footer.php'; ?>