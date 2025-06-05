<?php
require_once 'includes/db.php';
require_once __DIR__ . '/includes/config.php';
// Parámetros de búsqueda
$search = $_GET['search'] ?? '';
$order = $_GET['order'] ?? 'name';

// Consulta SQL
$sql = "SELECT * FROM products WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (name LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search%";
}

// Ordenación
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
$stmt->execute($params ?? []);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="container">
    <h1>Catálogo de Perfumes</h1>
    
    <!-- Barra de búsqueda -->
    <form method="get" class="search-bar">
        <input type="text" name="search" placeholder="Buscar perfumes..." 
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Buscar</button>
    </form>
    
    <!-- Filtros de ordenación -->
    <div class="sort-options">
        Ordenar por:
        <a href="?search=<?= urlencode($search) ?>&order=name" 
           class="<?= $order == 'name' ? 'active' : '' ?>">Nombre</a>
        <a href="?search=<?= urlencode($search) ?>&order=price_asc"
           class="<?= $order == 'price_asc' ? 'active' : '' ?>">Precio (↑)</a>
        <a href="?search=<?= urlencode($search) ?>&order=price_desc"
           class="<?= $order == 'price_desc' ? 'active' : '' ?>">Precio (↓)</a>
    </div>
    
    <!-- Lista de productos -->
    <div class="product-list">
        <?php if (empty($products)): ?>
            <p>No se encontraron productos.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="images/products/<?= $product['image'] ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="price">$<?= number_format($product['price'], 2) ?></p>
                    <a href="product.php?id=<?= $product['id'] ?>" class="btn">Ver Detalles</a>
                    <a href="cart.php?add=<?= $product['id'] ?>" class="btn">Añadir al Carrito</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>