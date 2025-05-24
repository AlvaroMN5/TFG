<?php
require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

header('Content-Type: application/json');

try {
    // 1. Crear el pedido en tu base de datos
    $conn->beginTransaction();
    
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$_SESSION['user_id'], $_POST['amount'] / 100]);
    $order_id = $conn->lastInsertId();
    
    // 2. Añadir items del pedido
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $price = $stmt->fetchColumn();
        
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $quantity, $price]);
        
        // Actualizar stock
        $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $product_id]);
    }
    
    $conn->commit();
    
    // 3. Simular respuesta de pasarela de pago (en producción usarías Stripe/API real)
    echo json_encode([
        'id' => 'simulated_payment_' . uniqid(),
        'success' => true
    ]);
    
    // Limpiar carrito
    unset($_SESSION['cart']);
    
} catch (Exception $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>