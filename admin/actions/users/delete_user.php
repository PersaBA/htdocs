<?php
include_once __DIR__ . '/../../../includes/db.php';

// Verificamos que haya un ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID no válido.");
}

$id = intval($_GET['id']);

// Evitar eliminarse a uno mismo si quisieras proteger eso:
// session_start();
// if ($_SESSION['user_id'] == $id) { die("No podés eliminarte a vos mismo."); }

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: /admin/dashboard.php?vista=usuarios&msg=eliminado");
    exit();
} else {
    echo "Error al eliminar usuario: " . $stmt->error;
}

$stmt->close();
