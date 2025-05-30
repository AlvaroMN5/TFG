<?php
// Mostrar mensajes flash
function display_flash_message() {
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert success">'.$_SESSION['success_message'].'</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert error">'.$_SESSION['error_message'].'</div>';
        unset($_SESSION['error_message']);
    }
}

// Obtener datos del usuario actual
function current_user() {
    global $pdo;
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}
?>