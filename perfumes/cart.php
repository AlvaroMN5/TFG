<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_login();

// Inicializar carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Añadir producto al carrito
if (isset($_GET['add'])) {
    $product_id = (int)$_GET['add'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header("Location: cart.php");
    exit;
}

// Eliminar producto del carrito
if (isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header("Location: cart.php");
    exit;
}

// Obtener detalles de los productos en el carrito
$cart_items = [];
$total = 0;

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        $product['quantity'] = $quantity;
        $product['subtotal'] = $product['price'] * $quantity;
        $total += $product['subtotal'];
        $cart_items[] = $product;
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Tu Carrito de Compras</h2>
    
    <?php if (empty($cart_items)): ?>
        <p>Tu carrito está vacío</p>
        <a href="index.php" class="btn">Seguir Comprando</a>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= number_format($item['subtotal'], 2) ?></td>
                    <td>
                        <a href="cart.php?remove=<?= $item['id'] ?>" class="btn">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3"><strong>Total</strong></td>
                    <td colspan="2">$<?= number_format($total, 2) ?></td>
                </tr>
            </tbody>
        </table>
        
        <div class="cart-actions">
            <a href="index.php" class="btn">Seguir Comprando</a>
            <a href="checkout.php" class="btn">Proceder al Pago</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>