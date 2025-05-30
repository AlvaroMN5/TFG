<?php
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
?>

<?php include 'includes/header.php'; ?>

<div class="container product-page">
    <div class="product-grid">
        <div class="product-image">
            <img src="images/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
        </div>
        
        <div class="product-info">
            <h1><?= $product['name'] ?></h1>
            <div class="price">$<?= number_format($product['price'], 2) ?></div>
            
            <div class="product-meta">
                <span>Disponibilidad: <?= $product['stock'] > 0 ? 'En stock' : 'Agotado' ?></span>
            </div>
            
            <div class="product-description">
                <p><?= nl2br($product['description']) ?></p>
            </div>
            
            <?php if ($product['stock'] > 0): ?>
                <form action="cart.php" method="get" class="add-to-cart">
                    <input type="hidden" name="add" value="<?= $product['id'] ?>">
                    <button type="submit" class="btn">Añadir al Carrito</button>
                </form>
            <?php else: ?>
                <button class="btn" disabled>Producto Agotado</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>