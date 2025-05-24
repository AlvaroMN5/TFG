<?php
require '../includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/products.php");
    exit;
}

$upload_dir = __DIR__ . '/../../assets/images/products/';
$allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
$max_size = 2 * 1024 * 1024; // 2MB

if (!isset($_FILES['product_image'])) {
    die("No se ha subido ningún archivo");
}

$file = $_FILES['product_image'];

// Validaciones
if ($file['error'] !== UPLOAD_ERR_OK) {
    die("Error al subir el archivo");
}

if (!in_array($file['type'], $allowed_types)) {
    die("Tipo de archivo no permitido");
}

if ($file['size'] > $max_size) {
    die("El archivo es demasiado grande (Máx. 2MB)");
}

// Generar nombre único
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '.' . $extension;
$destination = $upload_dir . $filename;

if (move_uploaded_file($file['tmp_name'], $destination)) {
    // Guardar en base de datos (ejemplo)
    $stmt = $conn->prepare("INSERT INTO products (image) VALUES (?)");
    $stmt->execute([$filename]);
    
    header("Location: ../admin/products.php?success=Imagen subida correctamente");
} else {
    die("Error al guardar el archivo");
}
?>