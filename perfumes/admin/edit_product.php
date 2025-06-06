<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?");
    $stmt->execute([$name, $price, $stock, $id]);

    header("Location: products.php");
    exit;
} else {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header("Location: products.php");
        exit;
    }
}
?>

<?php include '../includes/admin_header.php'; ?>
<div class="admin-container">
    <h2>Editar Producto</h2>
    <form method="POST" class="admin-form">
        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label for="price">Precio:</label>
        <input type="number" step="0.01" name="price" id="price" value="<?= $product['price'] ?>" required>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" value="<?= $product['stock'] ?>" required>

        <button type="submit" class="admin-btn">Guardar Cambios</button>
    </form>
</div>
<?php include '../includes/admin_footer.php'; ?>
