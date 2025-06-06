<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: products.php");
    exit;
} catch (PDOException $e) {
    if ($e->getCode() == '23000') {
        $error = "No se puede eliminar el producto porque está vinculado a uno o más pedidos.";
    } else {
        $error = "Error al eliminar el producto: " . $e->getMessage();
    }
}
?>

<?php include '../includes/admin_header.php'; ?>
<div class="admin-container">
    <h2 class="error-title">Error al eliminar producto</h2>
    <div class="admin-error"><?= htmlspecialchars($error) ?></div>
    <a href="products.php" class="admin-btn">Volver a la lista de productos</a>
</div>
<?php include '../includes/admin_footer.php'; ?>
