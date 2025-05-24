<?php
require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas
$pageTitle = 'Catálogo de Perfumes';

try {
    $stmt = $conn->query("SELECT * FROM products WHERE stock > 0");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error al cargar productos: " . $e->getMessage());
}

require_once __DIR__ . '/../templates/header.php';
?>
<div class="col-md-4 mb-4">
    <div class="card h-100">
        <img src="<?= BASE_URL ?>assets/images/products/<?= htmlspecialchars($product['image']) ?>" 
             class="card-img-top product-image" 
             alt="<?= htmlspecialchars($product['name']) ?>"
             onerror="this.src='<?= BASE_URL ?>assets/images/placeholder.jpg'">
        <!-- Resto del contenido -->
    </div>
</div>
<div class="row">
    <?php foreach ($products as $product): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <img src="<?= BASE_URL ?>assets/images/products/<?= htmlspecialchars($product['image']) ?>" 
                 class="card-img-top" 
                 alt="<?= htmlspecialchars($product['name']) ?>">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                <p class="text-success fw-bold">€<?= number_format($product['price'], 2) ?></p>
            </div>
            <div class="card-footer">
                <a href="<?= BASE_URL ?>pages/product.php?id=<?= $product['id'] ?>" class="btn btn-primary">Ver Detalles</a>
                <button class="btn btn-outline-primary add-to-cart" 
                        data-id="<?= $product['id'] ?>">Añadir al Carrito</button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>