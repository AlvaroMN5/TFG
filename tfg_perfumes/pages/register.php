<?php
require_once __DIR__ . '/../includes/init.php';  // Usa __DIR__ para rutas absolutas

if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL);
    exit;
}

$pageTitle = 'Registro';
include '../templates/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center">Crear Cuenta</h2>
                <?php if (isset($_SESSION['register_error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['register_error']; unset($_SESSION['register_error']); ?></div>
                <?php endif; ?>
                <form action="<?= BASE_URL ?>process/register.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="<?= BASE_URL ?>pages/login.php">¿Ya tienes cuenta? Inicia Sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>