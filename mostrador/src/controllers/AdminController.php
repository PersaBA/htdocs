<?php
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

    public function dashboard(): void
    {
        $userCount    = $this->conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
        $productCount = $this->conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];

        View::render('dashboard', [
            'meta_title' => 'Dashboard',
            'stats'      => [
                'users'    => $userCount,
                'products' => $productCount,
            ]
        ], 'admin');
    }

    public function users(): void
    {
        $view    = $_GET['view'] ?? 'table';
        $msg     = $_GET['msg']  ?? '';
        $isAjax  = ($_GET['ajax'] ?? '') === '1';

        $message = match ($msg) {
            'created' => '✅ Usuario creado correctamente.',
            'edited'  => '✏️ Usuario actualizado.',
            'deleted' => '🗑️ Usuario eliminado.',
            default   => ''
        };

        switch ($view) {
            case 'register':
                $viewName = 'users/register';
                $data = ['message' => $message];
                break;

            case 'table':
            default:
                $users = $this->conn->query("
                    SELECT id, nombre, email, role, created_at
                    FROM users
                    ORDER BY created_at DESC
                ");
                $viewName = 'users/table';
                $data = ['users' => $users, 'message' => $message];
                break;
        }

        if ($isAjax) {
            View::renderPartial($viewName, $data);
        } else {
            View::render($viewName, $data, 'admin');
        }
    }

    public function userCreate(): void
    {
        try {
            $name     = trim($_POST['nombre'] ?? '');
            $email    = trim($_POST['email']  ?? '');
            $password = $_POST['password']    ?? '';
            $role     = $_POST['role']        ?? '';

            if (!$name || !$email || !$password || !$role) {
                throw new Exception("Faltan campos requeridos.");
            }

            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                throw new Exception("El correo ya está registrado.");
            }
            $stmt->close();

            $uuid = uniqid('user_', true);
            $hash = password_hash($password, PASSWORD_DEFAULT)
                ?: throw new Exception("Error al encriptar la contraseña.");

            $stmt = $this->conn->prepare("
                INSERT INTO users (uuid, nombre, email, password, role, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->bind_param("sssss", $uuid, $name, $email, $hash, $role);
            $stmt->execute();

            if (($_GET['ajax'] ?? '') === '1') {
                $users = $this->conn->query("
                    SELECT id, nombre, email, role, created_at
                    FROM users
                    ORDER BY created_at DESC
                ");
                View::renderPartial('users/table', [
                    'users'   => $users,
                    'message' => '✅ Usuario creado correctamente.'
                ]);
                exit;
            }

            header("Location: " . BASE_URL . "admin/usuarios?view=table&msg=created");
            exit;
        } catch (Throwable $e) {
            echo "<pre>❌ Error al crear usuario: " . htmlspecialchars($e->getMessage()) . "</pre>";
            exit;
        }
    }

    public function userEdit(): void
    {
        try {
            $id    = intval($_POST['id'] ?? 0);
            $name  = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role  = $_POST['role'] ?? '';

            if ($id <= 0 || !$name || !$email || !$role) {
                throw new Exception("Datos incompletos.");
            }

            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                throw new Exception("El correo ya está en uso.");
            }
            $stmt->close();

            $stmt = $this->conn->prepare("
                UPDATE users SET nombre = ?, email = ?, role = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->bind_param("sssi", $name, $email, $role, $id);
            $stmt->execute();

            if (($_GET['ajax'] ?? '') === '1') {
                $users = $this->conn->query("
                    SELECT id, nombre, email, role, created_at
                    FROM users
                    ORDER BY created_at DESC
                ");
                View::renderPartial('users/table', [
                    'users'   => $users,
                    'message' => '✏️ Usuario actualizado.'
                ]);
                exit;
            }

            header("Location: " . BASE_URL . "admin/usuarios?view=table&msg=edited");
            exit;
        } catch (Throwable $e) {
            echo "<pre>❌ Error al editar usuario: " . htmlspecialchars($e->getMessage()) . "</pre>";
            exit;
        }
    }

    public function userDelete(): void
    {
        try {
            $id = intval($_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception("ID inválido.");
            }

            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if (($_GET['ajax'] ?? '') === '1') {
                $users = $this->conn->query("
                    SELECT id, nombre, email, role, created_at
                    FROM users
                    ORDER BY created_at DESC
                ");
                View::renderPartial('users/table', [
                    'users'   => $users,
                    'message' => '🗑️ Usuario eliminado correctamente.'
                ]);
                exit;
            }

            header("Location: " . BASE_URL . "admin/usuarios?msg=deleted");
            exit;
        } catch (Throwable $e) {
            echo "<pre>❌ Error al eliminar usuario: " . htmlspecialchars($e->getMessage()) . "</pre>";
            exit;
        }
    }

    public function products(): void
    {
        $msg     = $_GET['msg'] ?? '';
        $isAjax  = ($_GET['ajax'] ?? '') === '1';

        $message = match ($msg) {
            'created' => '✅ Producto creado con éxito.',
            'edited'  => '✏️ Producto actualizado.',
            'deleted' => '🗑️ Producto eliminado.',
            default   => ''
        };

        $categories = $this->conn->query("SELECT id, nombre FROM categorias");
        $products   = $this->conn->query("
            SELECT p.*, c.nombre AS categoria_nombre
            FROM products p
            JOIN categorias c ON c.id = p.categoria_id
            ORDER BY p.created_at DESC
        ");

        $data = [
            'categories' => $categories,
            'products'   => $products,
            'message'    => $message
        ];

        if ($isAjax) {
            View::renderPartial('products', $data);
        } else {
            View::render('products', array_merge($data, [
                'meta_title' => 'Gestión de productos'
            ]), 'admin');
        }
    }
}
