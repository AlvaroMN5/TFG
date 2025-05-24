<?php
require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "pages/login.php");
    exit;
}

$pageTitle = 'Mi Cuenta';
include '../templates/header.php';

// Obtener pedidos del usuario
try {
    $stmt = $conn->prepare("
        SELECT o.id, o.total, o.created_at, o.status 
        FROM orders o 
        WHERE o.user_id = ? 
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar pedidos: " . $e->getMessage());
}
?>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Mi Cuenta</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item active">Mis Pedidos</li>
                    <li class="list-group-item">Mis Datos</li>
                    <li class="list-group-item">Cambiar Contraseña</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <h2>Mis Pedidos</h2>
        
        <?php if (empty($orders)): ?>
            <div class="alert alert-info">No has realizado ningún pedido aún.</div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Pedido</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                        <td>€<?= number_format($order['total'], 2) ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $order['status'] === 'completed' ? 'success' : 
                                ($order['status'] === 'pending' ? 'warning' : 'danger') 
                            ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>pages/order_details.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-primary">Ver Detalles</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include '../templates/footer.php'; ?>