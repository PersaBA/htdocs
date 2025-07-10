<?php
include_once __DIR__ . '/../../../includes/db.php';

// Validar datos del formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    // Validación básica
    if ($nombre === '' || $email === '' || $password === '' || $role === '') {
        die("Faltan datos obligatorios.");
    }

    // Verificar si el email ya existe
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        die("El email ya está registrado.");
    }
    $stmt->close();

    // Generar UUID (puede cambiarse por tu sistema si usás uno)
    $uuid = uniqid('user_', true);

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO users (uuid, nombre, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sssss", $uuid, $nombre, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        header("Location: /admin/dashboard.php?vista=usuarios&msg=creado");
        exit();
    } else {
        echo "Error al crear usuario: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Método no permitido.";
}
