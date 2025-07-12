<?php
// Parámetros de conexión - ajustá según tu configuración local
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'tienda_props';

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>