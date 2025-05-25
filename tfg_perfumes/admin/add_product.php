<?php
require_once __DIR__ . '/../../includes/init.php';

if (!is_admin()) {
    header("Location: " . BASE_URL . "pages/login.php");
    exit;
}

$pageTitle = 'Agregar Producto';
include __DIR__ . '/../../templates/admin/header.php';
?>

<div class="container mt-5">
    <h2>Agregar Nuevo Producto</h2>
    <form action="../process/add_product.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Producto</button>
    </form>
</div>

<?php include __DIR__ . '/../../templates/admin/footer.php'; ?>