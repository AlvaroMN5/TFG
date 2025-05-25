<?php
require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "pages/register.php");
    exit;
}

$name = trim($_POST['name']);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validaciones
if (empty($name) || empty($email) || empty($password)) {
    $_SESSION['register_error'] = "Todos los campos son obligatorios";
    header("Location: " . BASE_URL . "pages/register.php");
    exit;
}

if ($password !== $confirm_password) {
    $_SESSION['register_error'] = "Las contraseñas no coinciden";
    header("Location: " . BASE_URL . "pages/register.php");
    exit;
}

if (strlen($password) < 6) {
    $_SESSION['register_error'] = "La contraseña debe tener al menos 6 caracteres";
    header("Location: " . BASE_URL . "pages/register.php");
    exit;
}

try {
    // Verificar si el email ya existe
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['register_error'] = "El email ya está registrado";
        header("Location: " . BASE_URL . "pages/register.php");
        exit;
    }

    // Crear el usuario
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, 0)");

    $stmt->execute([$name, $email, $hashed_password]);

    // Iniciar sesión automáticamente
    $user_id = $conn->lastInsertId();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = 'user';

    header("Location: " . BASE_URL);
    
}  catch (PDOException $e) {
    echo "Error al registrar el usuario: " . $e->getMessage();
    exit;
}

?>