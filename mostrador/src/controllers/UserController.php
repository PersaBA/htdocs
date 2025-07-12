<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../core/db.php';

class UserController
{
    public function login(): void
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                header('Location: ' . BASE_URL . 'login?error=Faltan datos');
                exit;
            }

            global $conn;
            $stmt = $conn->prepare("SELECT id, nombre, email, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows === 0) {
                header('Location: ' . BASE_URL . 'login?error=Usuario no encontrado');
                exit;
            }

            $user = $res->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['role'] = $user['role'];

                header('Location: ' . BASE_URL . 'admin/dashboard');
                exit;
            } else {
                header('Location: ' . BASE_URL . 'login?error=Contraseña incorrecta');
                exit;
            }
        } else {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }
}
