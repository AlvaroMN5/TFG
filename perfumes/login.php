<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Al usar header("Location: ..."), necesitamos buffer de salida
ob_start();

// Importar configuración general (incluye session_start)
require_once 'includes/config.php';
require_once 'includes/functions.php'; // Si tienes funciones adicionales

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '') {
        $errors[] = "El nombre de usuario es requerido";
    }
    if ($password === '') {
        $errors[] = "La contraseña es requerida";
    }

    if (empty($errors)) {
        // Buscar usuario por username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Almacenar datos de usuario en sesión
            $_SESSION['user'] = [
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'] ?? '',
                'is_admin' => (int) ($user['is_admin'] ?? 0)
            ];

            // Redirigir: si es admin, va al Dashboard; si no, a la página de inicio
            if ($_SESSION['user']['is_admin'] === 1) {
                header("Location: " . BASE_URL . "admin/dashboard.php");
            } else {
                header("Location: " . BASE_URL . "index.php");
            }
            exit;
        } else {
            $errors[] = "Nombre de usuario o contraseña incorrectos";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<!-- --------------------------------------------------
     Aquí comienza la “tarjeta” centrada para el login
   -------------------------------------------------- -->
<div class="login-section">
  <h2>Iniciar Sesión</h2>

  <?php if (!empty($errors)): ?>
    <div class="alert error">
      <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="login.php" method="post">
    <div class="form-group">
      <label for="username">Usuario:</label>
      <input type="text"
             name="username"
             id="username"
             required
             value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label for="password">Contraseña:</label>
      <input type="password"
             name="password"
             id="password"
             required>
    </div>
    <button type="submit" class="btn">Iniciar Sesión</button>
  </form>

  <div class="login-links">
    <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
    <p><a href="forgot-password.php">¿Olvidaste tu contraseña?</a></p>
  </div>
</div>
<!-- --------------------------------------------------
     Aquí finaliza la “tarjeta” de login
   -------------------------------------------------- -->

<?php include 'includes/footer.php'; ?>
<?php ob_end_flush(); ?>
