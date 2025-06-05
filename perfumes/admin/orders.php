<?php
require_once '../includes/auth.php';
require_admin();

// Obtener todos los pedidos
require_once '../includes/db.php';
$stmt = $pdo->query("SELECT o.id, u.username, o.total, o.created_at 
                     FROM orders o JOIN users u ON o.user_id = u.id");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/admin_header.php'; ?>

<div class="admin-container">
    <h2>Gestión de Pedidos</h2>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Usuario</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['username']) ?></td>
                <td>$<?= number_format($order['total'], 2) ?></td>
                <td><?= $order['created_at'] ?></td>
                <td>
                    <a href="order_details.php?id=<?= $order['id'] ?>" class="btn-small">Ver</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/admin_footer.php'; ?>