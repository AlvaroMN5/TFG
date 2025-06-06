<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$username, $email, $hashed_password, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $id]);
    }

    header("Location: users.php");
    exit;
} else {
    $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: users.php");
        exit;
    }
}
?>

<?php include '../includes/admin_header.php'; ?>
<div class="admin-container">
    <h2>Editar Usuario</h2>
    <form method="POST" class="admin-form">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label for="password">Contraseña (dejar vacío para no cambiarla):</label>
        <input type="password" name="password" id="password">

        <button type="submit" class="admin-btn">Guardar Cambios</button>
    </form>
</div>
<?php include '../includes/admin_footer.php'; ?>
