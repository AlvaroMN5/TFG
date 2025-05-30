<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_login();

$user = current_user();

// Actualizar perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    
    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, address = ?, email = ? WHERE id = ?");
    $stmt->execute([$full_name, $address, $email, $_SESSION['user_id']]);
    
    $_SESSION['success_message'] = "Perfil actualizado correctamente";
    header("Location: profile.php");
    exit;
}

// Obtener historial de pedidos
$orders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$orders->execute([$_SESSION['user_id']]);
$orders = $orders->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Mi Perfil</h2>
    
    <div class="profile-grid">
        <div class="profile-info">
            <h3>Información Personal</h3>
            <form action="profile.php" method="post">
                <div class="form-group">
                    <label for="username">Nombre de Usuario:</label>
                    <input type="text" id="username" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="full_name">Nombre Completo:</label>
                    <input type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Dirección:</label>
                    <textarea name="address" id="address" rows="4"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                </div>
                
                <button type="submit" class="btn">Actualizar Perfil</button>
            </form>
            
            <div class="profile-actions">
                <a href="change_password.php" class="btn">Cambiar Contraseña</a>
            </div>
        </div>
        
        <div class="order-history">
            <h3>Mis Pedidos</h3>
            
            <?php if (empty($orders)): ?>
                <p>No has realizado ningún pedido todavía.</p>
            <?php else: ?>
                <div class="orders-list">
                    <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span>Pedido #<?= $order['id'] ?></span>
                            <span><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                        </div>
                        <div class="order-details">
                            <span>Total: $<?= number_format($order['total'], 2) ?></span>
                            <span class="status <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                        </div>
                        <a href="order_details.php?id=<?= $order['id'] ?>" class="btn">Ver Detalles</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>