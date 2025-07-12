<?php
// src/controllers/AdminController.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../core/View.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class AdminController
{
    private mysqli $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    // GET /admin/dashboard
    public function dashboard(): void
    {
        $userCount    = $this->conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
        $productCount = $this->conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];

        View::render('dashboard', [
            'meta_title' => 'Panel principal',
            'stats'      => [
                'users'    => $userCount,
                'products' => $productCount,
            ],
        ]);
    }

    // GET /admin/usuarios
    public function usuarios(): void
    {
        $msg     = $_GET['msg'] ?? '';
        $mensaje = match ($msg) {
            'creado'    => "✅ Usuario creado correctamente.",
            'editado'   => "✏️ Usuario editado correctamente.",
            'eliminado' => "🗑️ Usuario eliminado correctamente.",
            default     => ''
        };

        $usuarios = $this->conn
            ->query("SELECT id, nombre, email, role, created_at
                     FROM users
                     ORDER BY created_at DESC");

        // petición AJAX → solo la vista parcial
        if (isset($_GET['ajax'])) {
            extract(compact('usuarios', 'mensaje'), EXTR_SKIP);
            require __DIR__ . '/../views/admin/usuarios.php';
            return;
        }

        // petición normal → layout completo
        View::render('usuarios', [
            'meta_title' => 'Gestión de Usuarios',
            'usuarios'   => $usuarios,
            'mensaje'    => $mensaje,
        ]);
    }

    // POST /admin/usuarios/crear
    public function crearUsuario(): void
    {
        try {
            $nombre   = trim($_POST['nombre']   ?? '');
            $email    = trim($_POST['email']    ?? '');
            $password = $_POST['password']      ?? '';
            $role     = $_POST['role']          ?? '';

            if (!$nombre || !$email || !$password || !$role) {
                throw new Exception("Faltan datos obligatorios.");
            }

            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                throw new Exception("El email ya está registrado.");
            }
            $stmt->close();

            $uuid            = uniqid('user_', true);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT)
                               ?: throw new Exception("Error al hashear la contraseña.");

            $stmt = $this->conn->prepare(
                "INSERT INTO users
                 (uuid, nombre, email, password, role, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, NOW(), NOW())"
            );
            $stmt->bind_param("sssss", $uuid, $nombre, $email, $hashed_password, $role);
            $stmt->execute();

            header("Location: " . BASE_URL . "admin/usuarios?msg=creado");
            exit;
        } catch (Throwable $e) {
            echo "<pre>Error al crear usuario: " . htmlspecialchars($e->getMessage()) . "</pre>";
            exit;
        }
    }

    // POST /admin/usuarios/editar
    public function editarUsuario(): void
    {
        try {
            $id     = intval($_POST['id']     ?? 0);
            $nombre = trim($_POST['nombre']   ?? '');
            $email  = trim($_POST['email']    ?? '');
            $role   = $_POST['role']          ?? '';

            if ($id <= 0 || !$nombre || !$email || !$role) {
                throw new Exception("Datos incompletos.");
            }

            $stmt = $this->conn->prepare(
                "SELECT id FROM users WHERE email = ? AND id != ?"
            );
            $stmt->bind_param("si", $email, $id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                throw new Exception("El email ya está en uso por otro usuario.");
            }
            $stmt->close();

            $stmt = $this->conn->prepare(
                "UPDATE users 
                 SET nombre = ?, email = ?, role = ?, updated_at = NOW() 
                 WHERE id = ?"
            );
            $stmt->bind_param("sssi", $nombre, $email, $role, $id);
            $stmt->execute();

            header("Location: " . BASE_URL . "admin/usuarios?msg=editado");
            exit;
        } catch (Throwable $e) {
            echo "<pre>Error al editar usuario: " . htmlspecialchars($e->getMessage()) . "</pre>";
            exit;
        }
    }

    // GET /admin/usuarios/eliminar
    public function eliminarUsuario(): void
    {
        try {
            $id = intval($_GET['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception("ID no válido.");
            }

            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            header("Location: " . BASE_URL . "admin/usuarios?msg=eliminado");
            exit;
        } catch (Throwable $e) {
            echo "<pre>Error al eliminar usuario: " . htmlspecialchars($e->getMessage()) . "</pre>";
            exit;
        }
    }

    // GET /admin/productos
    public function productos(): void
    {
        $msg     = $_GET['msg'] ?? '';
        $mensaje = match ($msg) {
            'creado'    => "✅ Producto creado correctamente.",
            'editado'   => "✏️ Producto editado correctamente.",
            'eliminado' => "🗑️ Producto eliminado correctamente.",
            default     => ''
        };

        $cats = $this->conn->query("SELECT id, nombre FROM categorias");
        $products = $this->conn->query("
            SELECT p.*, c.nombre AS categoria_nombre
            FROM products p
            JOIN categorias c ON c.id = p.categoria_id
            ORDER BY p.created_at DESC
        ");

        if (isset($_GET['ajax'])) {
            extract(compact('cats', 'products', 'mensaje'), EXTR_SKIP);
            require __DIR__ . '/../views/admin/productos.php';
            return;
        }

        View::render('productos', [
            'meta_title'=> 'Gestión de Productos',
            'cats'      => $cats,
            'products'  => $products,
            'mensaje'   => $mensaje
        ]);
    }
}
