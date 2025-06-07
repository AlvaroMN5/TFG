<?php
// checkout.php

// 1) Incluimos configuración y abrimos sesión
require_once __DIR__ . '/includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2) Si el usuario no está logueado, lo mandamos a login.php
if (!isset($_SESSION['user']['id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit;
}

// 3) Conexión PDO (ya está definido en config.php como $pdo)
global $pdo;

// 4) Si se envía el formulario (método POST), procesamos el pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 4.a) Tomamos el carrito de sesión
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        $_SESSION['error_message'] = "Tu carrito está vacío.";
        header("Location: " . BASE_URL . "cart.php");
        exit;
    }

    $userId = (int) $_SESSION['user']['id'];

    // 4.b) Calculamos el total y preparamos los datos de cada ítem
    $total = 0;
    $itemsData = []; 
    $stmtProd = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    foreach ($cart as $item) {
        $prodId   = (int) $item['id'];
        $quantity = (int) $item['quantity'];

        // Obtenemos el precio actual del producto
        $stmtProd->execute([$prodId]);
        $row = $stmtProd->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            // Si el producto no existe, lo saltamos
            continue;
        }
        $price = (float) $row['price'];
        $subtotal = $price * $quantity;
        $total += $subtotal;

        $itemsData[] = [
            'product_id' => $prodId,
            'quantity'   => $quantity,
            'price'      => $price
        ];
    }

    if (empty($itemsData)) {
        $_SESSION['error_message'] = "Uno o más productos del carrito no son válidos.";
        header("Location: " . BASE_URL . "cart.php");
        exit;
    }

    // 4.c) Insertamos en la tabla orders + order_items en una transacción
    try {
        // Iniciamos transacción
        $pdo->beginTransaction();

        // Insertar en orders
        $stmtOrder = $pdo->prepare("
            INSERT INTO orders (user_id, total, status)
            VALUES (?, ?, 'completed')
        ");
        $stmtOrder->execute([$userId, $total]);
        $orderId = $pdo->lastInsertId();

        // Insertar cada ítem en order_items
        $stmtItem = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        foreach ($itemsData as $row) {
            $stmtItem->execute([
                $orderId,
                $row['product_id'],
                $row['quantity'],
                $row['price']
            ]);
        }

        // Confirmamos la transacción
        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error procesando el pago (checkout): " . $e->getMessage());
        $_SESSION['error_message'] = "Ocurrió un error al procesar tu pedido. Intenta más tarde.";
        header("Location: " . BASE_URL . "cart.php");
        exit;
    }

    // 4.d) Vaciamos el carrito de la sesión
    unset($_SESSION['cart']);

    // 4.e) Guardamos mensaje de éxito y redirigimos a “Mis Compras”
    $_SESSION['success_message'] = "✅ Pago exitoso. Tu pedido #{$orderId} se registró correctamente.";
    header("Location: " . BASE_URL . "my_orders.php");
    exit;
}

// 5) Si el request es GET, mostramos el formulario de pago
require_once __DIR__ . '/includes/header.php';
?>

<section class="checkout-section">
  <h1>Pago Seguro</h1>
  <form id="payment-form" action="<?php echo BASE_URL; ?>checkout.php" method="post">
    <div class="form-group">
      <label for="card_name">Nombre en la Tarjeta</label>
      <input type="text"
             id="card_name"
             name="card_name"
             placeholder="Como aparece en la tarjeta"
             required>
    </div>

    <div class="form-group">
      <label for="card_number">Número de Tarjeta</label>
      <input type="text"
             id="card_number"
             name="card_number"
             placeholder="0000 0000 0000 0000"
             maxlength="19"
             pattern="[\d ]{19}"
             required>
    </div>

    <div class="form-row">
      <div class="form-group small">
        <label for="exp_month">Mes Vto.</label>
        <input type="text"
               id="exp_month"
               name="exp_month"
               placeholder="MM"
               maxlength="2"
               pattern="\d{2}"
               required>
      </div>
      <div class="form-group small">
        <label for="exp_year">Año Vto.</label>
        <input type="text"
               id="exp_year"
               name="exp_year"
               placeholder="AA"
               maxlength="2"
               pattern="\d{2}"
               required>
      </div>
      <div class="form-group small">
        <label for="cvv">CVV</label>
        <input type="password"
               id="cvv"
               name="cvv"
               placeholder="123"
               maxlength="3"
               pattern="\d{3}"
               required>
      </div>
    </div>

    <div class="form-group">
      <label for="address">Dirección de Facturación</label>
      <input type="text"
             id="address"
             name="address"
             placeholder="Calle, número, ciudad, CP"
             required>
    </div>

    <button type="submit" class="btn btn-primary btn-pay">Procesar Pago</button>
  </form>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("payment-form");
  form.addEventListener("submit", function(e) {
    // No es necesario prevenir nada aquí,
    // porque el procesado real ocurre en el servidor PHP (POST).
  });
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
