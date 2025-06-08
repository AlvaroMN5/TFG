<?php
// my_orders.php
require_once __DIR__ . '/includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1) Si no está logueado, lo mandamos a login
if (!isset($_SESSION['user']['id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$userId = (int) $_SESSION['user']['id'];
// $pdo ya viene de config.php

// 2) Obtenemos los pedidos de este usuario
$stmt = $pdo->prepare("
  SELECT id, total, status, created_at
  FROM orders
  WHERE user_id = ?
  ORDER BY created_at DESC
");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <h2>Mis Compras</h2>

  <?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert success">
      <?= htmlspecialchars($_SESSION['success_message']); ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <?php if (empty($orders)): ?>
    <p>No has realizado ningún pedido todavía.</p>
  <?php else: ?>
    <div class="orders-list">
      <?php foreach ($orders as $order): ?>
        <div class="order-card">
          <div class="order-header">
            <span>Pedido #<?= htmlspecialchars($order['id']) ?></span>
            <span><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
          </div>
          <div class="order-details">
            <span>
              Total: <?= number_format($order['total'], 2, ',', '.') ?> €
            </span>
            <span class="status <?= htmlspecialchars($order['status']) ?>">
              <?= ucfirst(htmlspecialchars($order['status'])) ?>
            </span>
          </div>
       <a href="order_details.php?id=<?= $order['id'] ?>" class="btn">Ver Detalles</a>

        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
