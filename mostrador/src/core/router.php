<?php
// src/core/router.php

// Cargar configuración global
require_once __DIR__ . '/../../config/config.php';

// Obtener la ruta limpia (sin base URL)
$uri   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base  = BASE_URL;
$path  = trim(str_replace($base, '', $uri), '/');

switch ($path) {

    /*--------------------------------------------------
      Home
    ---------------------------------------------------*/
    case '':
    case '/':
        require_once __DIR__ . '/../views/home/index.php';
        break;

    /*--------------------------------------------------
      Autenticación
    ---------------------------------------------------*/
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controllers/UserController.php';
            (new UserController())->login();
        } else {
            require_once __DIR__ . '/../views/users/login.php';
        }
        break;

    case 'logout':
        // Destruir sesión y redirigir
        require_once __DIR__ . '/../../logout.php';
        break;

    /*--------------------------------------------------
      Admin — Dashboard (Panel principal)
    ---------------------------------------------------*/
case 'admin/dashboard':
    require_once __DIR__ . '/../controllers/AdminController.php';
    (new AdminController())->dashboard();
    break;

    /*--------------------------------------------------
      Admin — Gestión de Usuarios
    ---------------------------------------------------*/
    case 'admin/usuarios':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->usuarios();
        break;

    case 'admin/usuarios/crear':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->crearUsuario();
        break;

    case 'admin/usuarios/editar':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->editarUsuario();
        break;

    case 'admin/usuarios/eliminar':
        require_once __DIR__ . '/../controllers/AdminController.php';
        (new AdminController())->eliminarUsuario();
        break;

    /*--------------------------------------------------
      Admin — Gestión de Productos
    ---------------------------------------------------*/
    case 'admin/productos':
        require_once __DIR__ . '/../controllers/ProductController.php';
        (new ProductController())->index();
        break;

    case 'admin/productos/crear':
        require_once __DIR__ . '/../controllers/ProductController.php';
        (new ProductController())->crear();
        break;

    case 'admin/productos/editar':
        require_once __DIR__ . '/../controllers/ProductController.php';
        (new ProductController())->editar();
        break;

    case 'admin/productos/eliminar':
        require_once __DIR__ . '/../controllers/ProductController.php';
        (new ProductController())->eliminar();
        break;

    /*--------------------------------------------------
      Admin — Gestión de Categorías
    ---------------------------------------------------*/
    case 'admin/categorias':
        require_once __DIR__ . '/../controllers/CategoryController.php';
        (new CategoryController())->index();
        break;

    case 'admin/categorias/crear':
        require_once __DIR__ . '/../controllers/CategoryController.php';
        (new CategoryController())->crear();
        break;

    case 'admin/categorias/editar':
        require_once __DIR__ . '/../controllers/CategoryController.php';
        (new CategoryController())->editar();
        break;

    case 'admin/categorias/eliminar':
        require_once __DIR__ . '/../controllers/CategoryController.php';
        (new CategoryController())->eliminar();
        break;

    /*--------------------------------------------------
      Admin — Gestión de Artículos
    ---------------------------------------------------*/
    case 'admin/articulos':
        require_once __DIR__ . '/../controllers/ArticleController.php';
        (new ArticleController())->index();
        break;

    case 'admin/articulos/crear':
        require_once __DIR__ . '/../controllers/ArticleController.php';
        (new ArticleController())->crear();
        break;

    case 'admin/articulos/editar':
        require_once __DIR__ . '/../controllers/ArticleController.php';
        (new ArticleController())->editar();
        break;

    case 'admin/articulos/eliminar':
        require_once __DIR__ . '/../controllers/ArticleController.php';
        (new ArticleController())->eliminar();
        break;

    /*--------------------------------------------------
      Admin — Métodos de Pago
    ---------------------------------------------------*/
    case 'admin/pagos':
        require_once __DIR__ . '/../controllers/PaymentController.php';
        (new PaymentController())->index();
        break;

    case 'admin/pagos/crear':
        require_once __DIR__ . '/../controllers/PaymentController.php';
        (new PaymentController())->crear();
        break;

    case 'admin/pagos/editar':
        require_once __DIR__ . '/../controllers/PaymentController.php';
        (new PaymentController())->editar();
        break;

    case 'admin/pagos/eliminar':
        require_once __DIR__ . '/../controllers/PaymentController.php';
        (new PaymentController())->eliminar();
        break;

    /*--------------------------------------------------
      Front — Ver producto individual
    ---------------------------------------------------*/
case 'product':
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        require_once __DIR__ . '/../controllers/ProductController.php';
        (new ProductController())->ver((int) $_GET['id']);
    } else {
        echo "ID de producto no válido.";
    }
    break;

    /*--------------------------------------------------
      Ruta no encontrada (404)
    ---------------------------------------------------*/
    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}
