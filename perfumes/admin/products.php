<?php
require_once '../includes/auth.php';
require_admin();

// Obtener todos los productos
require_once '../includes/db.php';
$stmt = $pdo->query("SELECT id, name, price, stock FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/admin_header.php'; ?>

<div class="admin-container">
    <h2>Gestión de Productos</h2>
    
    <div class="admin-actions">
        <a href="add_product.php" class="admin-btn">Añadir Producto</a>
    </div>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
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
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>$<?= number_format($product['price'], 2) ?></td>
                <td><?= $product['stock'] ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn-small">Editar</a>
                    <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn-small delete">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/admin_footer.php'; ?>