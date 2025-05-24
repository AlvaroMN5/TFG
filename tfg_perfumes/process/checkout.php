<?php
require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas

if (!isset($_SESSION['user_id']) {
    header("Location: " . BASE_URL . "pages/login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: " . BASE_URL . "pages/cart.php");
    exit;
}

$pageTitle = 'Finalizar Compra';
include '../templates/header.php';

// Calcular total
$total = 0;
$cart_items = [];
$product_ids = array_keys($_SESSION['cart']);

if (!empty($product_ids)) {
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($product_ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $quantity = $_SESSION['cart'][$product['id']];
        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;
        $cart_items[] = [
            'product' => $product,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Datos de Envío</h4>
            </div>
            <div class="card-body">
                <form id="checkout-form">
                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>
                    <div class="mb-3">
                        <label for="zip_code" class="form-label">Código Postal</label>
                        <input type="text" class="form-control" id="zip_code" name="zip_code" required>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Resumen del Pedido</h4>
            </div>
            <div class="card-body">
                <?php foreach ($cart_items as $item): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span><?= htmlspecialchars($item['product']['name']) ?> x<?= $item['quantity'] ?></span>
                    <span>€<?= number_format($item['subtotal'], 2) ?></span>
                </div>
                <?php endforeach; ?>
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total:</span>
                    <span>€<?= number_format($total, 2) ?></span>
                </div>
                <button id="pay-button" class="btn btn-success w-100 mt-3">Pagar con Tarjeta</button>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('pk_test_tu_clave_publica_de_stripe');
const payButton = document.getElementById('pay-button');

payButton.addEventListener('click', async () => {
    const response = await fetch('<?= BASE_URL ?>process/create_payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            amount: <?= $total * 100 ?>, // Stripe usa centavos
            currency: 'eur',
            metadata: {
                user_id: <?= $_SESSION['user_id'] ?>
            }
        })
    });
    
    const session = await response.json();
    await stripe.redirectToCheckout({ sessionId: session.id });
});
</script>

<?php include '../templates/footer.php'; ?>