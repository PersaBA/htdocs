<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: admin/dashboard.php');
    exit;
}

$page_css = 'login.css';
include 'includes/header.php';
?>

<div class="login-box">
    <h1>LOGIN</h1>

    <?php if (isset($_GET['error'])): ?>
        <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form action="login_process.php" method="POST" autocomplete="off">
        <h3>Email</h3>
        <input type="email" name="email" required>

        <h3>Contraseña</h3>
        <input type="password" name="password" required>

        <button type="submit">Ingresar</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
