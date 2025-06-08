<?php
// order_details.php (en la raíz del proyecto)

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

// 1) Validar que venga un ID
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$orderId) {
    header("Location: my_orders.php");
    exit;
}

// 2) Obtener pedido, comprobando que pertenece al usuario
$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("
    SELECT o.id, o.total, o.status, o.created_at, u.username
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    // No existe o no es de este usuario
    header("Location: my_orders.php");
    exit;
}

// 3) Obtener los artículos de este pedido
$stmt2 = $pdo->prepare("
    SELECT p.name, oi.price, oi.quantity
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt2->execute([$orderId]);
$items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>

<div class="container">
  <h2>Detalle Pedido #<?= htmlspecialchars($order['id']) ?></h2>
  
  <div class="order-meta">
    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
    <p><strong>Estado:</strong> <?= ucfirst(htmlspecialchars($order['status'])) ?></p>
    <p><strong>Total:</strong> <?= number_format($order['total'],2,',','.') ?> €</p>
  </div>
  
  <h3>Artículos</h3>
  <table class="cart-table">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Precio unitario</th>
        <th>Cantidad</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($items as $it): 
          $subtotal = $it['price'] * $it['quantity'];
      ?>
      <tr>
        <td><?= htmlspecialchars($it['name']) ?></td>
        <td><?= number_format($it['price'],2,',','.') ?> €</td>
        <td><?= (int)$it['quantity'] ?></td>
        <td><?= number_format($subtotal,2,',','.') ?> €</td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  
  <p><a href="my_orders.php" class="btn">← Volver a Mis Compras</a></p>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
