<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $full_name = trim($_POST['full_name']);
    
    $errors = [];
    
    if (empty($username)) $errors[] = "El nombre de usuario es requerido";
    if (empty($email)) $errors[] = "El email es requerido";
    if (empty($password)) $errors[] = "La contraseña es requerida";
    if (strlen($password) < 6) $errors[] = "La contraseña debe tener al menos 6 caracteres";
    
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "El nombre de usuario o email ya está en uso";
    }
    
    if (empty($errors)) {
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

<div class="container">
    <h2>Registro de Usuario</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="alert">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form action="register.php" method="post">
        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" name="username" id="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
        </div>
        
        <div class="form-group">
            <label for="full_name">Nombre Completo:</label>
            <input type="text" name="full_name" id="full_name">
        </div>
        
        <button type="submit" class="form-submit">Registrarse</button>
    </form>
    
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</div>

<?php include 'includes/footer.php'; ?>