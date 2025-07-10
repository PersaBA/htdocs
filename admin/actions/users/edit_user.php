<?php
include_once __DIR__ . '/../../../includes/db.php';

// Validar datos enviados
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'user';

    if ($id <= 0 || $nombre === '' || $email === '' || $role === '') {
        die("Datos incompletos.");
    }

    // Verificar si el email ya existe en otro usuario
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        die("El email ya está en uso por otro usuario.");
    }
    $stmt->close();

    // Actualizar usuario
    $stmt = $conn->prepare("UPDATE users SET nombre = ?, email = ?, role = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("sssi", $nombre, $email, $role, $id);

    if ($stmt->execute()) {
        header("Location: /admin/dashboard.php?vista=usuarios&msg=editado");
        exit();
    } else {
        echo "Error al actualizar usuario: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Método no permitido.";
}
