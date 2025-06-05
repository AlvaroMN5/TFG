<?php
require_once '../includes/auth.php';
require_admin();

// Obtener todos los usuarios
require_once '../includes/db.php';
$stmt = $pdo->query("SELECT id, username, email, created_at FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/admin_header.php'; ?>

<div class="admin-container">
    <h2>Gestión de Usuarios</h2>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['created_at'] ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn-small">Editar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/admin_footer.php'; ?>