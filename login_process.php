<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validación básica
    if (empty($email) || empty($password)) {
        header('Location: login.php?error=Faltan datos');
        exit;
    }

    $stmt = $conn->prepare("SELECT id, nombre, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        header('Location: login.php?error=Usuario no encontrado');
        exit;
    }

    $user = $res->fetch_assoc();

    // Comparación sin hash (modo desarrolelo)
if (password_verify($password, $user['password'])) {
        // Guardar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['role'] = $user['role'];
        header('Location: /admin/dashboard.php');
        exit;
    } else {
        header('Location: login.php?error=Contraseña incorrecta');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}