<?php
// contact.php - Versión definitiva

// 1. Verificar la ubicación correcta de config.php
$configPath = __DIR__ . '/includes/config.php';

if (!file_exists($configPath)) {
    die("Error: Archivo de configuración no encontrado en: " . $configPath);
}

require_once $configPath;

// 2. Función para mostrar el formulario
function mostrarFormulario() {
    global $configPath;
    require_once __DIR__ . '/includes/header.php';
    ?>
    <section class="contact-section">
        <h1>Contacto</h1>
        <form id="contact-form" method="POST" action="<?php echo htmlspecialchars(BASE_URL . 'contact.php'); ?>">
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
    
    <script src="<?php echo BASE_URL; ?>js/contact.js"></script>
    <?php
    require_once __DIR__ . '/includes/footer.php';
}

// 3. Procesar el formulario
function procesarFormulario() {
    header('Content-Type: application/json');
    
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Email inválido']);
        exit;
    }

    if (strlen($message) < 10) {
        echo json_encode(['success' => false, 'message' => 'Mensaje demasiado corto']);
        exit;
    }

    try {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO contact_messages (email, message) VALUES (?, ?)");
        $stmt->execute([$email, $message]);
        echo json_encode(['success' => true, 'message' => 'Mensaje enviado']);
    } catch (PDOException $e) {
        error_log('Error en contact.php: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al guardar']);
    }
}

// 4. Lógica principal
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    mostrarFormulario();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    procesarFormulario();
} else {
    header("HTTP/1.0 405 Method Not Allowed");
    exit;
}
?>