<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$product_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: index.php");
    exit;
}

include 'includes/header.php';
?>
<div class="product-page">
  <div class="product-grid">
    <!-- Columna izquierda: imagen -->
    <div class="product-image">
      <img src="<?= BASE_URL ?>images/products/<?= htmlspecialchars($product['image']) ?>"
           alt="<?= htmlspecialchars($product['name']) ?>">
    </div>

    <!-- Columna derecha: info -->
    <div class="product-info">
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <div class="price">
        <?= number_format($product['price'], 2, ',', '.') ?> €
      </div>
      <div class="product-meta">
        <span>
          <strong>Disponibilidad:</strong>
          <?= $product['stock'] > 0 ? 'En stock' : 'Agotado' ?>
        </span>
      </div>
      <div class="product-description">
        <?= nl2br(htmlspecialchars($product['description'])) ?>
      </div>

      <?php if ($product['stock'] > 0): ?>
        <form action="cart.php" method="get" class="add-to-cart">
          <input type="hidden" name="add" value="<?= $product['id'] ?>">
          <button type="submit" class="btn btn-primary">Añadir al Carrito</button>
        </form>
      <?php else: ?>
        <button class="btn btn-secondary" disabled>Agotado</button>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
