<?php
// contact.php

// Si es una solicitud GET, mostrar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Incluye el header
    require_once 'includes/header.php';
    ?>
    
    <section class="contact-section">
        <h1>Contacto</h1>
        <form id="contact-form" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="message">Mensaje:</label>
                <textarea id="message" name="message" required minlength="10"></textarea>
            </div>
            
            <button type="submit" class="btn">Enviar Mensaje</button>
        </form>
        <div id="response-message"></div>
    </section>
    
    <script src="js/contact.js"></script>
    
    <?php
    // Incluye el footer
    require_once 'includes/footer.php';
    exit;
}

// Si es POST, procesar el formulario
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