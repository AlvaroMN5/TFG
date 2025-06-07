<?php
// 1) Iniciar sesión (importante para que $_SESSION esté disponible)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2) Importar configuración de BD y de autenticación
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 3) Validar que el usuario sea administrador
//    Aquí modificamos para leer el flag is_admin dentro de $_SESSION['user']
if (
    !isset($_SESSION['user']['is_admin']) ||
    (int)$_SESSION['user']['is_admin'] !== 1
) {
    header("Location: ../index.php");
    exit;
}

// 4) Procesar el formulario cuando el método sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanitizar datos obligatorios
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $stock       = trim($_POST['stock'] ?? '');

    // Validar que los campos recibidos no estén vacíos
    $errors = [];
    if ($name === '') {
        $errors[] = "El nombre del producto es obligatorio.";
    }
    if ($description === '') {
        $errors[] = "La descripción es obligatoria.";
    }
    if ($price === '' || !is_numeric($price) || $price < 0) {
        $errors[] = "Ingresa un precio válido.";
    }
    if ($stock === '' || !is_numeric($stock) || $stock < 0) {
        $errors[] = "Ingresa un stock válido.";
    }

    // 5) Manejo de la imagen (opcional: puede quedar en blanco si no se sube)
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir  = "../images/products/";
        // Asegúrate de que la carpeta exista con permisos de escritura
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Generar un nombre único para evitar sobreescrituras
        $filename    = basename($_FILES["image"]["name"]);
        $extension   = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $nuevo_nombre = uniqid('prod_') . "." . $extension;
        $target_file = $target_dir . $nuevo_nombre;

        // Validar extensión
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $nuevo_nombre;
            } else {
                $errors[] = "Error al mover la imagen subida.";
            }
        } else {
            $errors[] = "Solo se permiten imágenes JPG, PNG o GIF.";
        }
    }

    // 6) Si no hay errores, insertamos en la BD
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO products
                (name, description, price, image, stock)
            VALUES
                (?, ?, ?, ?, ?)
        ");

        $ok = $stmt->execute([
            $name,
            $description,
            $price,
            $image,
            $stock
        ]);

        if ($ok) {
            // Redirigir al listado de productos en el panel admin
            header("Location: products.php");
            exit;
        } else {
            $errors[] = "Hubo un error al guardar el producto. Intenta de nuevo.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Añadir Nuevo Producto</h2>

    <!-- Mostrar errores si existen -->
    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <?php foreach ($errors as $err): ?>
                <p><?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text"
                   name="name"
                   id="name"
                   required
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea name="description"
                      id="description"
                      rows="4"
                      required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Precio:</label>
            <input type="number"
                   step="0.01"
                   name="price"
                   id="price"
                   required
                   value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number"
                   name="stock"
                   id="stock"
                   required
                   value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="image">Imagen (opcional):</label>
            <input type="file"
                   name="image"
                   id="image"
                   accept="image/*">
        </div>

        <button type="submit" class="btn">Guardar Producto</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
