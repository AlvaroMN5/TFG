<?php
require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}

$pageTitle = 'Gestión de Productos';
include '../templates/admin_header.php';

// Obtener todos los productos
try {
    $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar productos: " . $e->getMessage());
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Productos</h2>
    <a href="<?= BASE_URL ?>admin/add_product.php" class="btn btn-success">Añadir Producto</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><img src="<?= BASE_URL ?>assets/images/products/<?= $product['image'] ?>" width="50"></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td>€<?= number_format($product['price'], 2) ?></td>
            <td><?= $product['stock'] ?></td>
            <td>
                <a href="<?= BASE_URL ?>admin/edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                <a href="<?= BASE_URL ?>process/delete_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../templates/admin_footer.php'; ?>