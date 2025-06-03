<?php
header('Content-Type: application/json');

// Validar y sanitizar datos
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Validaciones
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Por favor ingresa un email válido']);
    exit;
}

if (strlen($message) < 10) {
    echo json_encode(['success' => false, 'message' => 'El mensaje debe tener al menos 10 caracteres']);
    exit;
}

// Conexión a la base de datos
require_once 'db.php';

try {
    $stmt = $pdo->prepare("INSERT INTO contact_messages (email, message) VALUES (?, ?)");
    $stmt->execute([$email, $message]);
    
    echo json_encode(['success' => true, 'message' => 'Mensaje enviado con éxito']);
    
} catch (PDOException $e) {
    error_log('Error en contact.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al guardar el mensaje']);
}