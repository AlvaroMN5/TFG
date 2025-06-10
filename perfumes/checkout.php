
<?php
// checkout.php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login(); // fuerza login

// 1) Si no es POST, muestro el formulario:
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    include __DIR__ . '/includes/header.php';
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

    <?php
    include __DIR__ . '/includes/footer.php';
    exit;
}

// 2) Lógica de POST: procesar pedido
session_start();
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    $_SESSION['error_message'] = "Tu carrito está vacío.";
    header("Location: cart.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$total = 0;

// calcular total
foreach ($cart as $id => $qty) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $price = (float)$stmt->fetchColumn();
    $total += $price * $qty;
}

try {
    $pdo->beginTransaction();

    // insertar en orders
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'completed')");
    $stmt->execute([$userId, $total]);
    $orderId = $pdo->lastInsertId();

    // insertar cada línea
    $stmtItem = $pdo->prepare(
      "INSERT INTO order_items (order_id, product_id, quantity, price)
       VALUES (?, ?, ?, ?)"
    );
    foreach ($cart as $id => $qty) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $price = (float)$stmt->fetchColumn();
        $stmtItem->execute([$orderId, $id, $qty, $price]);
    }

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error_message'] = "Error al procesar tu pago. Inténtalo de nuevo.";
    header("Location: cart.php");
    exit;
}

// 3) Vaciar carrito y redirigir a “Mis Compras”
unset($_SESSION['cart']);
$_SESSION['success_message'] = "✅ Pedido #{$orderId} registrado correctamente.";
header("Location: my_orders.php");
exit;
?>
