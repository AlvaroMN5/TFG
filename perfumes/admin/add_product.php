<?php
// add_product.php

// 1. Iniciar sesión y permisos
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/db.php';
require_once '../includes/auth.php';
if (!isset($_SESSION['user']['is_admin']) || (int)$_SESSION['user']['is_admin'] !== 1) {
    header("Location: ../index.php");
    exit;
}

// 2. Procesar formulario
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanitizar
    $name        = trim($_POST['name']        ?? '');
    $description = trim($_POST['description'] ?? '');
    $raw_price   = trim($_POST['price']       ?? '');
    $stock       = trim($_POST['stock']       ?? '');

    // Validaciones básicas
    if ($name === '') {
        $errors[] = "El nombre del producto es obligatorio.";
    }
    if ($description === '') {
        $errors[] = "La descripción es obligatoria.";
    }
    // Normalizar precio: aceptar "19,99" o "19.99"
    $normalized = str_replace([',',' '], ['.',''], $raw_price);
    if (!is_numeric($normalized) || $normalized < 0) {
        $errors[] = "Ingresa un precio válido (ej. 19,99).";
    } else {
        // Forzar 2 decimales con punto para MySQL
        $price = number_format((float)$normalized, 2, '.', '');
    }
    if ($stock === '' || !ctype_digit($stock) || (int)$stock < 0) {
        $errors[] = "Ingresa un stock válido (entero ≥ 0).";
    }

    // Manejo de imagen
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../images/products/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif'])) {
            $newName = uniqid('prod_').".$ext";
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir.$newName)) {
                $image = $newName;
            } else {
                $errors[] = "Error al subir la imagen.";
            }
        } else {
            $errors[] = "Sólo se permiten JPG, PNG o GIF.";
        }
    }

    // Insertar si no hay errores
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO products
              (name, description, price, image, stock)
            VALUES (?, ?, ?, ?, ?)
        ");
        $ok = $stmt->execute([
            $name,
            $description,
            $price,
            $image,
            $stock
        ]);
        if ($ok) {
            header("Location: products.php");
            exit;
        } else {
            $errors[] = "Error al guardar el producto.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>Añadir Nuevo Producto</h2>

  <?php if (!empty($errors)): ?>
    <div class="alert error">
      <?php foreach ($errors as $err): ?>
        <p><?= htmlspecialchars($err) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="add_product.php" method="post" enctype="multipart/form-data" class="add-product-form">
    <div class="form-group">
      <label for="name">Nombre:</label>
      <input
        type="text"
        name="name"
        id="name"
        required
        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label for="description">Descripción:</label>
      <textarea
        name="description"
        id="description"
        rows="4"
        required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label for="price">Precio (€):</label>
      <input
        type="text"
        name="price"
        id="price"
        placeholder="ej. 19,99"
        required
        value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label for="stock">Stock:</label>
      <input
        type="number"
        name="stock"
        id="stock"
        min="0"
        required
        value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label for="image">Imagen (opcional):</label>
      <input
        type="file"
        name="image"
        id="image"
        accept="image/*">
    </div>

    <button type="submit" class="btn">Guardar Producto</button>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
