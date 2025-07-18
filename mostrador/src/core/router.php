<?php
// src/core/router.php

require_once __DIR__ . '/../../config/config.php';

// Ruta limpia sin BASE_URL
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = BASE_URL;
$path = trim(str_replace($base, '', $uri), '/');

// --------------------------
// Rutas administrativas (GET tipo vista)
// --------------------------
$adminRoutes = [
    'admin/dashboard'         => ['controller' => 'AdminController',     'method' => 'dashboard'],
    'admin/usuarios'          => ['controller' => 'AdminController',     'method' => 'users'],
    'admin/articulos'         => ['controller' => 'ArticleController',   'method' => 'index'],
    'admin/productos'         => ['controller' => 'ProductController',   'method' => 'index'],
    'admin/categorias'        => ['controller' => 'CategoryController',  'method' => 'index'],
    'admin/configuraciones'   => ['controller' => 'SettingsController',  'method' => 'index']
];

if (array_key_exists($path, $adminRoutes)) {
    $route = $adminRoutes[$path];
    require_once __DIR__ . "/../controllers/{$route['controller']}.php";
    (new $route['controller'])->{$route['method']}();
    return;
}

// --------------------------
// Acciones GET del panel admin (formularios parciales)
// --------------------------
$getActions = [
    'admin/usuarios/editar-form'        => ['controller' => 'AdminController',    'method' => 'userEditForm'],
    'admin/articulos/editar-form'       => ['controller' => 'ArticleController',  'method' => 'articleEditForm'],
    'admin/productos/editar-form'       => ['controller' => 'ProductController',  'method' => 'productEditForm'],
    'admin/categorias/editar-form'      => ['controller' => 'CategoryController', 'method' => 'categoryEditForm'],
    'admin/configuraciones/editar-form' => ['controller' => 'SettingsController', 'method' => 'settingsEditForm']
];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && array_key_exists($path, $getActions)) {
    $action = $getActions[$path];
    require_once __DIR__ . "/../controllers/{$action['controller']}.php";
    (new $action['controller'])->{$action['method']}();
    return;
}

// --------------------------
// Acciones POST del panel admin
// --------------------------
$postActions = [
    // Usuarios
    'admin/usuarios/crear'     => ['controller' => 'AdminController',    'method' => 'userCreate'],
    'admin/usuarios/editar'    => ['controller' => 'AdminController',    'method' => 'userEdit'],
    'admin/usuarios/delete'    => ['controller' => 'AdminController',    'method' => 'userDelete'],

    // Artículos
    'admin/articulos/crear'    => ['controller' => 'ArticleController',  'method' => 'articleCreate'],
    'admin/articulos/editar'   => ['controller' => 'ArticleController',  'method' => 'articleEdit'],
    'admin/articulos/delete'   => ['controller' => 'ArticleController',  'method' => 'articleDelete'],

    // Productos
    'admin/productos/crear'    => ['controller' => 'ProductController',  'method' => 'productCreate'],
    'admin/productos/editar'   => ['controller' => 'ProductController',  'method' => 'productEdit'],
    'admin/productos/delete'   => ['controller' => 'ProductController',  'method' => 'productDelete'],

    // Categorías
    'admin/categorias/crear'   => ['controller' => 'CategoryController', 'method' => 'categoryCreate'],
    'admin/categorias/editar'  => ['controller' => 'CategoryController', 'method' => 'categoryEdit'],
    'admin/categorias/delete'  => ['controller' => 'CategoryController', 'method' => 'categoryDelete'],

    // Configuraciones
    'admin/configuraciones/crear'   => ['controller' => 'SettingsController', 'method' => 'settingsCreate'],
    'admin/configuraciones/editar'  => ['controller' => 'SettingsController', 'method' => 'settingsEdit'],
    'admin/configuraciones/delete'  => ['controller' => 'SettingsController', 'method' => 'settingsDelete'],

];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && array_key_exists($path, $postActions)) {
    $action = $postActions[$path];
    require_once __DIR__ . "/../controllers/{$action['controller']}.php";
    (new $action['controller'])->{$action['method']}();
    return;
}

// --------------------------
// Frontend público
// --------------------------
switch ($path) {
    case '':
    case '/':
        require_once __DIR__ . '/../views/home/index.php';
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controllers/UserController.php';
            (new UserController())->login();
        } else {
            require_once __DIR__ . '/../views/users/login.php';
        }
        break;

    case 'logout':
        require_once __DIR__ . '/../../logout.php';
        break;

    case 'producto':
    case 'product':
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            require_once __DIR__ . '/../controllers/ProductController.php';
            (new ProductController())->view((int) $_GET['id']);
        } else {
            echo "ID de producto no válido.";
        }
        break;

    case 'articulo':
        require_once __DIR__ . '/../controllers/ArticleController.php';
        (new ArticleController())->publicView();
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}
