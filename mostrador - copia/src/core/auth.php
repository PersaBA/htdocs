<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificación de acceso
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'login');
    exit;
}
?> 