<?php
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit;
}
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function handle_product_upload($file, $upload_dir) {
    // ... (implementa la lógica de subida de archivos)
}