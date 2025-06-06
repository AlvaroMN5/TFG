<?php
// orders.php (en la raíz: /proyecto/orders.php)

require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Solo usuarios logueados (no importa si son admin o no; este listado mostrará solo sus compras propias)
require_login();

// Obtiene el ID del usuario de la sesión
$userId = $_SESSION['user']['id'];

// Consulta los pedidos de este usuario
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Mis Compras</h2>

    <?php if (empty($orders)): ?>
        <p>No has realizado ninguna compra aún.</p>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <span>Pedido #<?= htmlspecialchars($order['id']) ?></span>
                        <span><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                    </div>
                    <div class="order-details">
                        <span>Total: $<?= number_format($order['total'], 2) ?></span>
                        <span class="status <?= htmlspecialchars($order['status']) ?>">
                            <?= ucfirst(htmlspecialchars($order['status'])) ?>
                        </span>
                    </div>
                    <a href="order_details.php?id=<?= htmlspecialchars($order['id']) ?>" class="btn">
                        Ver Detalles
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
