<?php
// src/controllers/ProductController.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/View.php';

class ProductController
{
    private mysqli $conn;
    private string $uploadDir;
    private array $allowedExts  = ['jpg', 'jpeg', 'png'];
    private int   $maxFileSize = 2 * 1024 * 1024; // 2 MB

    public function __construct()
    {
        global $conn;
        $this->conn      = $conn;
        $this->uploadDir = __DIR__ . '/../../public/img/';
    }

    // GET /admin/productos
    public function index(): void
    {
        // Mensaje flash
        $msg     = $_GET['msg'] ?? '';
        $mensaje = match ($msg) {
            'creado'    => "✅ Producto creado correctamente.",
            'editado'   => "✏️ Producto editado correctamente.",
            'eliminado' => "🗑️ Producto eliminado correctamente.",
            default     => ''
        };

        // Consultas
        $cats = $this->conn->query("SELECT id, nombre FROM categories");
        if (!$cats) {
            http_response_code(500);
            die("Error al obtener categorías: " . $this->conn->error);
        }

        $products = $this->conn->query("
            SELECT p.*, c.nombre AS categoria_nombre
            FROM products p
            LEFT JOIN categories c ON p.categoria_id = c.id
            ORDER BY p.creado_en DESC
        ");
        if (!$products) {
            http_response_code(500);
            die("Error al obtener productos: " . $this->conn->error);
        }

        // Si es petición AJAX, devolvemos solo la sección de productos
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            View::render('productos', [
                'cats'     => $cats,
                'products' => $products,
                'mensaje'  => $mensaje
            ]);
            exit;
        }

        // Vista normal completa
        View::render('productos', [
            'cats'     => $cats,
            'products' => $products,
            'mensaje'  => $mensaje
        ]);
    }

    // POST /admin/productos/crear
    public function crear(): void
    {
        // Recoger y validar datos
        $nombre    = trim($_POST['nombre'] ?? '');
        $desc      = trim($_POST['descripcion'] ?? '');
        $precio    = floatval($_POST['precio'] ?? 0);
        $stock     = intval($_POST['stock'] ?? 0);
        $catId     = intval($_POST['categoria_id'] ?? 0);
        $ofertaAct = isset($_POST['oferta_activa']) ? 1 : 0;
        $ofertaMon = $ofertaAct ? floatval($_POST['oferta_monto'] ?? 0) : null;
        $ofertaTip = $ofertaAct ? ($_POST['oferta_tipo'] ?? null) : null;

        if (!$nombre || !$precio || !$catId) {
            http_response_code(400);
            die("Faltan datos obligatorios.");
        }

        // Procesar imagen (opcional)
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
                die("La imagen excede 2 MB.");
            }

            $imagenName = uniqid('prd_') . '.' . $ext;
            move_uploaded_file(
                $_FILES['imagen']['tmp_name'],
                $this->uploadDir . $imagenName
            );
        }

        // 3) Insertar en la base
        $stmt = $this->conn->prepare(
            "INSERT INTO products
             (id, nombre, descripcion, precio, stock, imagen,
              categoria_id, oferta_activa, oferta_monto, oferta_tipo,
              creado_en)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );
        if (!$stmt) {
            http_response_code(500);
            die("Error en prepare(): " . $this->conn->error);
        }

        $uuid = uniqid('prd_', true);
        $stmt->bind_param(
            "sssdisiids",
            $uuid,
            $nombre,
            $desc,
            $precio,
            $stock,
            $imagenName,
            $catId,
            $ofertaAct,
            $ofertaMon,
            $ofertaTip
        );

        if (!$stmt->execute()) {
            http_response_code(500);
            die("Error al crear producto: " . $stmt->error);
        }

        // Respuesta AJAX vs redirección normal
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->index();
            exit;
        }

        header("Location: " . BASE_URL . "admin/productos?msg=creado");
        exit;
    }

    // POST /admin/productos/editar
    public function editar(): void
    {
        // Aquí añadirías tu lógica de edición (validate, update, AJAX/redirección)
        echo "<pre>El método editar() aún no está implementado.</pre>";
    }

    // GET /admin/productos/eliminar?id=…
    public function eliminar(): void
    {
        $id = intval($_GET['id'] ?? 0);
        if (!$id) {
            http_response_code(400);
            die("ID no válido.");
        }

        // Borro imagen
        $old = $this->conn
            ->query("SELECT imagen FROM products WHERE id = $id")
            ->fetch_assoc()['imagen'];
        if ($old && file_exists($this->uploadDir . $old)) {
            unlink($this->uploadDir . $old);
        }

        // Borro producto
        $this->conn->query("DELETE FROM products WHERE id = $id");

        // Respuesta AJAX vs redirección
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->index();
            exit;
        }

        header("Location: " . BASE_URL . "admin/productos?msg=eliminado");
        exit;
    }

    // GET /product?id=…
    public function ver(int $id): void
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

            View::render('productos/show', ['producto' => $producto]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo "<pre>Error al cargar producto: " . htmlspecialchars($e->getMessage()) . "</pre>";
        }
    }
}
