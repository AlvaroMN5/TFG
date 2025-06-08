<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    header("Location: orders.php");
    exit;
}

// Datos del pedido
$stmt = $pdo->prepare("
  SELECT o.id, o.total, o.status, o.created_at, u.username
  FROM orders o
  JOIN users u ON o.user_id = u.id
  WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    echo "<p>Pedido no encontrado.</p>";
    exit;
}

// Ítems
$stmt2 = $pdo->prepare("
  SELECT oi.quantity, oi.price, p.name
  FROM order_items oi
  JOIN products p ON oi.product_id = p.id
  WHERE oi.order_id = ?
");
$stmt2->execute([$order_id]);
$items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../includes/header.php';
?>

<div class="container">
  <div class="card admin-order-details">
    <h2>Pedido #<?= $order['id'] ?></h2>
    <div class="order-meta">
      <div><strong>Usuario:</strong> <?= htmlspecialchars($order['username']) ?></div>
      <div><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></div>
      <div><strong>Estado:</strong> <?= ucfirst($order['status']) ?></div>
      <div><strong>Total:</strong> $<?= number_format($order['total'],2) ?></div>
    </div>

    <h3>Artículos</h3>
    <table class="admin-table order-table">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Precio unitario</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($items as $it): ?>
          <tr>
            <td><?= htmlspecialchars($it['name']) ?></td>
            <td>$<?= number_format($it['price'],2) ?></td>
            <td><?= (int)$it['quantity'] ?></td>
            <td>$<?= number_format($it['price']*$it['quantity'],2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <p class="back-link"><a href="orders.php" class="btn">← Volver a Pedidos</a></p>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
