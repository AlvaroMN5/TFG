<?php
// admin/edit_product.php

require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = (int) $_GET['id'];
$errors = [];

// 1) Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $raw_price = trim($_POST['price'] ?? '');
    $stock = trim($_POST['stock'] ?? '');

    // Validaciones
    if ($name === '') {
        $errors[] = "El nombre no puede estar vacío.";
    }

    // Normalizar precio: aceptar "19,99" o "19.99"
    $normalized = str_replace([',',' '], ['.',''], $raw_price);
    if (!is_numeric($normalized) || $normalized < 0) {
        $errors[] = "Ingresa un precio válido (ej. 19,99).";
    } else {
        $price = number_format((float)$normalized, 2, '.', '');
    }

    if ($stock === '' || !ctype_digit($stock) || (int)$stock < 0) {
        $errors[] = "Ingresa un stock válido (entero ≥ 0).";
    }

    // Si pasa validación, actualizamos
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE products
               SET name  = ?,
                   price = ?,
                   stock = ?
             WHERE id    = ?
        ");
        $stmt->execute([$name, $price, $stock, $id]);
        header("Location: products.php");
        exit;
    }
}

// 2) Si no es POST o hay errores, cargamos datos actuales
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    header("Location: products.php");
    exit;
}

// Prepara valores para el form
$fill_name  = htmlspecialchars($product['name']);
$fill_price = number_format((float)$product['price'], 2, ',', ''); // mostrar con coma
$fill_stock = (int)$product['stock'];

?>
<?php include '../includes/admin_header.php'; ?>

<div class="admin-container">
  <h2>Editar Producto</h2>

  <?php if (!empty($errors)): ?>
    <div class="alert error">
      <?php foreach ($errors as $err): ?>
        <p><?= htmlspecialchars($err) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="admin-form">
    <div class="form-group">
      <label for="name">Nombre:</label>
      <input
        type="text"
        name="name"
        id="name"
        required
        value="<?= $fill_name ?>">
    </div>

    <div class="form-group">
      <label for="price">Precio (€):</label>
      <input
        type="text"
        name="price"
        id="price"
        placeholder="ej. 19,99"
        required
        value="<?= $fill_price ?>">
    </div>

    <div class="form-group">
      <label for="stock">Stock:</label>
      <input
        type="number"
        name="stock"
        id="stock"
        min="0"
        required
        value="<?= $fill_stock ?>">
    </div>

    <button type="submit" class="admin-btn">Guardar Cambios</button>
  </form>
</div>

<?php include '../includes/admin_footer.php'; ?>
