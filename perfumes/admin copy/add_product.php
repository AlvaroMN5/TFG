<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // Manejo de la imagen
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/products/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Validar tipo de archivo
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["image"]["name"]);
            }
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, stock) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $image, $stock]);
    
    header("Location: products.php");
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Añadir Nuevo Producto</h2>
    
    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" id="name" required>
        </div>
        
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea name="description" id="description" rows="4" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="price">Precio:</label>
            <input type="number" step="0.01" name="price" id="price" required>
        </div>
        
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" required>
        </div>
        
        <div class="form-group">
            <label for="image">Imagen:</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>
        
        <button type="submit" class="btn">Guardar Producto</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>