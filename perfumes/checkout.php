<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_login();

// Si el carrito está vacío, redirigir
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Procesar el pago
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Crear el pedido
    $user_id = $_SESSION['user_id'];
    $total = 0;
    
    // Calcular total
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $total += $product['price'] * $quantity;
    }
    
    // Insertar pedido
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();
    
    // Insertar items del pedido
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $quantity, $product['price']]);
        
        // Actualizar stock
        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $product_id]);
    }
    
    // Vaciar carrito
    $_SESSION['cart'] = [];
    $_SESSION['success_message'] = "¡Pedido realizado con éxito! Número de pedido: #".$order_id;
    header("Location: profile.php");
    exit;
}

// Obtener datos del usuario
$user = current_user();
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Finalizar Compra</h2>
    
    <div class="checkout-grid">
        <div class="checkout-form">
            <h3>Datos de Envío</h3>
            <form action="checkout.php" method="post">
                <div class="form-group">
                    <label for="full_name">Nombre Completo:</label>
                    <input type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Dirección:</label>
                    <textarea name="address" id="address" rows="4" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                </div>
                
                <h3>Método de Pago</h3>
                <div class="payment-method">
                    <label>
                        <input type="radio" name="payment_method" value="paypal" checked> PayPal
                    </label>
                    <label>
                        <input type="radio" name="payment_method" value="credit_card"> Tarjeta de Crédito
                    </label>
                </div>
                
                <button type="submit" class="btn">Confirmar Pedido</button>
            </form>
        </div>
        
        <div class="order-summary">
            <h3>Resumen del Pedido</h3>
            <ul>
                <?php 
                $total = 0;
                foreach ($_SESSION['cart'] as $product_id => $quantity): 
                    $stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
                    $stmt->execute([$product_id]);
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    $subtotal = $product['price'] * $quantity;
                    $total += $subtotal;
                ?>
                    <li>
                        <?= $product['name'] ?> x <?= $quantity ?>
                        <span>$<?= number_format($subtotal, 2) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="order-total">
                <strong>Total:</strong>
                <span>$<?= number_format($total, 2) ?></span>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>