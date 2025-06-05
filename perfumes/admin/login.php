<?php
session_start(); // Añadido: Inicio de sesión obligatorio
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $errors = [];
    
    if (empty($username)) $errors[] = "El nombre de usuario es requerido";
    if (empty($password)) $errors[] = "La contraseña es requerida";
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = ($user['is_admin'] ?? 0) == 1;
            
            // Redirección inteligente según tipo de usuario
            if ($_SESSION['is_admin']) {
                header("Location: admin/dashboard.php"); // Redirige a panel admin
            } else {
                header("Location: profile.php"); // Redirige a perfil para usuarios normales
            }
            exit;
        } else {
            $errors[] = "Nombre de usuario o contraseña incorrectos";
        }
    }
}

// Mostrar mensajes flash de éxito/error (añadido)
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $errors[] = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Iniciar Sesión</h2>
    
    <?php if (!empty($success_message)): ?>
        <div class="alert success">
            <p><?php echo $success_message; ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" name="username" id="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
        </div>
        
        <button type="submit" class="form-submit">Iniciar Sesión</button>
    </form>
    
    <div class="login-links">
        <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
        <p><a href="forgot-password.php">¿Olvidaste tu contraseña?</a></p> <!-- Opcional -->
    </div>
</div>

<?php include 'includes/footer.php'; ?>