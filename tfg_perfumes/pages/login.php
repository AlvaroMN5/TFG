<?php
require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas
// Debugging
echo "<pre>";
echo "SESSION: "; print_r($_SESSION);
echo "BASE_URL: " . BASE_URL;
echo "POST data: "; print_r($_POST);
echo "</pre>";

// Si el usuario ya está logueado, redirigir
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL);
    exit;
}

$pageTitle = 'Iniciar Sesión';
include __DIR__ . '/../templates/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center">Iniciar Sesión</h2>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <form action="<?= BASE_URL ?>process/login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>
                <div class="mt-3 text-center">
                   
                    <a href="<?= BASE_URL ?>pages/register.php" class="btn btn-link">¿No tienes cuenta? Regístrate</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>