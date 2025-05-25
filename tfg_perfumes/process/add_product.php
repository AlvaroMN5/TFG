<?php
require_once __DIR__ . '/../includes/init.php';

if (!is_admin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "pages/login.php");
    exit;
}

// Configuración de subida
$upload_dir = __DIR__ . '/../../assets/images/products/';
$allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
$max_size = 2 * 1024 * 1024; // 2MB

try {
    // Validar imagen
    if (!isset($_FILES['image']['error']) {
        throw new Exception('No se subió ninguna imagen');
    }

    $file = $_FILES['image'];
    
    // Verificar errores de subida
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir el archivo: ' . $file['error']);
    }

    // Validar tipo y tamaño
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Tipo de archivo no permitido');
    }

    if ($file['size'] > $max_size) {
        throw new Exception('El archivo es demasiado grande (Máx. 2MB)');
    }

    // Generar nombre único
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('prod_') . '.' . $extension;
    $destination = $upload_dir . $filename;

    // Mover archivo
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('Error al guardar la imagen');
    }

    // Insertar en BD
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['name'],
        $_POST['description'],
        $_POST['price'],
        $_POST['stock'],
        $filename
    ]);

    header("Location: " . BASE_URL . "admin/products.php?success=Producto agregado correctamente");
    
} catch (Exception $e) {
    error_log('Error al agregar producto: ' . $e->getMessage());
    header("Location: " . BASE_URL . "admin/add_product.php?error=" . urlencode($e->getMessage()));
}