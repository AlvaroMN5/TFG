<?php
require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'] ?? null;

if (!$productId) {
    echo json_encode(['success' => false, 'error' => 'ID de producto no válido']);
    exit;
}

// Verificar si el producto existe
try {
    $stmt = $conn->prepare("SELECT id FROM products WHERE id = ? AND stock > 0");
    $stmt->execute([$productId]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'error' => 'Producto no disponible']);
        exit;
    }

    // Añadir al carrito
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = 0;
    }
    $_SESSION['cart'][$productId] += 1;

    // Calcular total de items
    $cartCount = array_sum($_SESSION['cart']);

    echo json_encode([
        'success' => true,
        'cart_count' => $cartCount
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos']);
}
?>