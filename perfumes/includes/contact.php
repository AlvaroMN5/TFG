<?php
header('Content-Type: application/json');

// 1. Validar y sanitizar los datos primero
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// 2. Validaciones
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Por favor ingresa un email válido']);
    exit;
}

if (strlen($message) < 10) {
    echo json_encode(['success' => false, 'message' => 'El mensaje debe tener al menos 10 caracteres']);
    exit;
}

// 3. Conexión a la base de datos
require_once 'db.php';

try {
    // 4. Insertar en la base de datos
    $stmt = $pdo->prepare("INSERT INTO contact_messages (email, message) VALUES (?, ?)");
    $stmt->execute([$email, $message]);
    
    // 5. Opcional: Enviar email de notificación
    // mail('tu@email.com', 'Nuevo mensaje de contacto', $message, "From: $email");
    
    // 6. Respuesta de éxito
    echo json_encode([
        'success' => true, 
        'message' => 'Mensaje enviado con éxito. Te responderemos pronto.'
    ]);
    
} catch (PDOException $e) {
    // 7. Manejo de errores
    error_log('Error en contact.php: ' . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Ocurrió un error al guardar tu mensaje. Por favor inténtalo más tarde.'
    ]);
}
?>