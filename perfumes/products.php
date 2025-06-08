<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Parámetros de búsqueda
$search = $_GET['search'] ?? '';
$order  = $_GET['order']  ?? 'name';

// Construcción de la consulta
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
if (!empty($search)) {
    $sql .= " AND (name LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search%";
}
switch ($order) {
    case 'price_asc':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY price DESC";
        break;
    default:
        $sql .= " ORDER BY name";
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="container">
    <h1 class="page-title">Catálogo de Perfumes</h1>

    <!-- Barra de búsqueda -->
    <form method="get" class="search-bar">
        <input type="text"
               name="search"
               placeholder="Buscar perfumes..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Buscar</button>
    </form>

    <!-- Filtros de ordenación -->
    <div class="sort-options">
        <span>Ordenar por:</span>
        <a href="?search=<?= urlencode($search) ?>&order=name"
           class="<?= $order === 'name' ? 'active' : '' ?>">
           Nombre
        </a>
        <a href="?search=<?= urlencode($search) ?>&order=price_asc"
           class="<?= $order === 'price_asc' ? 'active' : '' ?>">
           Precio (↑)
        </a>
        <a href="?search=<?= urlencode($search) ?>&order=price_desc"
           class="<?= $order === 'price_desc' ? 'active' : '' ?>">
           Precio (↓)
        </a>
    </div>

    <!-- Lista de Productos -->
    <div class="product-list">
        <?php if (empty($products)): ?>
            <p>No se encontraron productos.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card-wrapper">
                  <!-- Enlace a la página de detalle -->
                  <a href="product.php?id=<?= (int)$product['id'] ?>" class="product-card-link">
                    <div class="product-card fade-in">
                      <img src="<?= BASE_URL ?>images/products/<?= htmlspecialchars($product['image']) ?>"
                           alt="<?= htmlspecialchars($product['name']) ?>">
                      <h3><?= htmlspecialchars($product['name']) ?></h3>
                      <p class="price">
                        <?= '€ ' . number_format($product['price'], 2, ',', '.') ?>
                      </p>
                    </div>
                  </a>

                  <!-- Botón “Añadir al Carrito” -->
                  <form action="cart.php" method="get" class="add-cart-form">
                    <input type="hidden" name="add" value="<?= (int)$product['id'] ?>">
                    <button type="submit" class="btn btn-add-cart">Añadir al Carrito</button>
                  </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
