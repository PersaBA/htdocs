<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../config/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'admin/dashboard');
    exit;
}

$page_css = 'login.css';
include __DIR__ . '/../layouts/header.php';
?>

<div class="login-box">
    <h1>LOGIN</h1>

    <?php if (isset($_GET['error'])): ?>
        <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>login" method="POST" autocomplete="off">
        <h3>Email</h3>
        <input type="email" name="email" required>

        <h3>Contraseña</h3>
        <input type="password" name="password" required>

        <button type="submit">Ingresar</button>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
