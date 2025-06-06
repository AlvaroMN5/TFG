<?php
// Mostrar errores en desarrollo (opcional)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión si no está (para mensajes flash, si usas)
session_start();

// Conexión a BD, etc.
require_once 'includes/config.php';
require_once 'includes/functions.php';

$errors = [];

// Procesar registro (este código puede variar según tu lógica actual)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');

    if ($username === '')  $errors[] = "El nombre de usuario es requerido";
    if ($email === '')     $errors[] = "El email es requerido";
    if ($password === '')  $errors[] = "La contraseña es requerida";
    if (strlen($password) < 6) $errors[] = "La contraseña debe tener al menos 6 caracteres";

    // Verificar si usuario o email ya existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "El nombre de usuario o email ya está en uso";
    }

    if (empty($errors)) {
        // Insertar nuevo usuario
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt->execute([$username, $email, $hashed_password, $full_name])) {
            $_SESSION['success_message'] = "Registro exitoso. Por favor inicia sesión.";
            header("Location: login.php");
            exit;
        } else {
            $errors[] = "Error al registrar el usuario. Inténtalo de nuevo.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<!-- --------------------------------------------
     Tarjeta “Registro de Usuario” (misma forma que contacto/login)
   -------------------------------------------- -->
<div class="register-section">
  <h2>Registro de Usuario</h2>

  <?php if (!empty($errors)): ?>
    <div class="alert error">
      <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form action="register.php" method="post">
    <div class="form-group">
      <label for="username">Nombre de Usuario:</label>
      <input type="text"
             name="username"
             id="username"
             required
             value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email"
             name="email"
             id="email"
             required
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>

    <div class="form-group">
      <label for="password">Contraseña:</label>
      <input type="password"
             name="password"
             id="password"
             required>
    </div>

    <div class="form-group">
      <label for="full_name">Nombre Completo:</label>
      <input type="text"
             name="full_name"
             id="full_name"
             value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
    </div>

    <button type="submit" class="btn">Registrarse</button>
  </form>

  <div class="login-links">
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
  </div>
</div>
<!-- --------------------------------------------
     Fin de tarjeta “Registro de Usuario”
   -------------------------------------------- -->

<?php include 'includes/footer.php'; ?>
