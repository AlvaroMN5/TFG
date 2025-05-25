<?php
session_start();


require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas

// Debugging
echo "<pre>";
echo "SESSION: "; print_r($_SESSION);
echo "BASE_URL: " . BASE_URL;
echo "POST data: "; print_r($_POST);
echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "pages/login.php");
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: " . BASE_URL);
    } else {
        $_SESSION['error'] = "Credenciales incorrectas";
        header("Location: " . BASE_URL . "pages/login.php");
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error en el sistema";
    header("Location: " . BASE_URL . "pages/login.php");
}
exit;
?>