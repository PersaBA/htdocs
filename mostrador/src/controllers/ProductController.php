<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/View.php';

class ProductController
{
    private mysqli $conn;
    private string $uploadDir;
    private array $allowedExts  = ['jpg', 'jpeg', 'png'];
    private int $maxFileSize    = 2 * 1024 * 1024; // 2 MB

    public function __construct()
    {
        global $conn;
        $this->conn      = $conn;
        $this->uploadDir = __DIR__ . '/../../public/img/';
    }

    public function index(): void
    {
        $view   = $_GET['view'] ?? 'table';
        $msg    = $_GET['msg']  ?? '';
        $isAjax = ($_GET['ajax'] ?? '') === '1';

        $message = match ($msg) {
            'created' => "✅ Producto creado correctamente.",
            'edited'  => "✏️ Producto editado correctamente.",
            'deleted' => "🗑️ Producto eliminado correctamente.",
            default   => ''
        };

        $cats = $this->conn->query("SELECT id, nombre FROM categories");

        switch ($view) {
            case 'register':
                $vista = 'productos/register';
                $data  = ['message' => $message, 'cats' => $cats];
                break;

            case 'table':
            default:
                $products = $this->conn->query("
                    SELECT p.*, c.nombre AS categoria_nombre
                    FROM products p
                    LEFT JOIN categories c ON p.categoria_id = c.id
                    ORDER BY p.creado_en DESC
                ");
                $vista = 'productos/table';
                $data  = ['message' => $message, 'cats' => $cats, 'products' => $products];
                break;
        }

        if ($isAjax) {
            View::renderPartial($vista, $data);
        } else {
            View::render($vista, $data, 'admin');
        }
    }

    public function productCreate(): void
    {
        $nombre    = trim($_POST['nombre'] ?? '');
        $desc      = trim($_POST['descripcion'] ?? '');
        $precio    = floatval($_POST['precio'] ?? 0);
        $stock     = intval($_POST['stock'] ?? 0);
        $catId     = intval($_POST['categoria_id'] ?? 0);
        $ofertaAct = isset($_POST['oferta_activa']) ? 1 : 0;
        $ofertaMon = $ofertaAct ? floatval($_POST['oferta_monto'] ?? 0) : null;
        $ofertaTip = $ofertaAct ? ($_POST['oferta_tipo'] ?? null) : null;
        $destacado = isset($_POST['destacado']) ? 1 : 0;

        if (!$nombre || !$precio || !$catId) {
            http_response_code(400);
            die("Faltan datos obligatorios.");
        }

        $imagenName = null;
        if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $finfo = pathinfo($_FILES['imagen']['name']);
            $ext   = strtolower($finfo['extension'] ?? '');
            $size  = $_FILES['imagen']['size'];

            if (!in_array($ext, $this->allowedExts)) {
                http_response_code(400);
                die("Extensión de imagen no permitida.");
            }
            if ($size > $this->maxFileSize) {
                http_response_code(400);
                die("La imagen excede el límite de tamaño.");
            }

            $imagenName = uniqid('prd_') . '.' . $ext;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $this->uploadDir . $imagenName);
        }

        $uuid = uniqid('prd_', true);
        $stmt = $this->conn->prepare("
            INSERT INTO products
            (id, nombre, descripcion, precio, stock, imagen, categoria_id, oferta_activa, oferta_monto, oferta_tipo, destacado, creado_en)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param(
            "sssdisiidsi",
            $uuid,
            $nombre,
            $desc,
            $precio,
            $stock,
            $imagenName,
            $catId,
            $ofertaAct,
            $ofertaMon,
            $ofertaTip,
            $destacado
        );

        if (!$stmt->execute()) {
            http_response_code(500);
            die("Error al crear producto: " . $stmt->error);
        }

        if ($_GET['ajax'] ?? '' === '1') {
            $_GET['view'] = 'table';
            $_GET['msg']  = 'created';
            $_GET['ajax'] = '1';
            $this->index();
            exit;
        }

        header("Location: " . BASE_URL . "admin/products?msg=created");
        exit;
    }

    public function productDelete(): void
    {
        $id = trim($_POST['id'] ?? '');
        if (!$id) {
            http_response_code(400);
            die("ID no válido.");
        }

        $old = $this->conn->query("SELECT imagen FROM products WHERE id = '{$id}'")->fetch_assoc()['imagen'];
        if ($old && file_exists($this->uploadDir . $old)) {
            unlink($this->uploadDir . $old);
        }

        $this->conn->query("DELETE FROM products WHERE id = '{$id}'");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['ajax'] ?? '') === '1') {
            $_GET['view'] = 'table';
            $_GET['msg']  = 'deleted';
            $_GET['ajax'] = '1';
            $this->index();
            exit;
        }

        header("Location: " . BASE_URL . "admin/products?msg=deleted");
        exit;
    }

    public function view(int $id): void
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT p.*, c.nombre AS categoria_nombre
                FROM products p
                LEFT JOIN categories c ON p.categoria_id = c.id
                WHERE p.id = ?
            ");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $producto = $stmt->get_result()->fetch_assoc();

            if (!$producto) {
                throw new Exception("Producto no encontrado.");
            }

            View::render('productos/show', [
                'producto' => $producto,
                'page_css' => 'product.css'
            ]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo "<pre>Error al cargar producto: " . htmlspecialchars($e->getMessage()) . "</pre>";
        }
    }

    public function productEdit(): void
    {
        echo "<pre>A implementar: edición de producto vía UPDATE</pre>";
    }
}
