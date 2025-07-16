<?php
// src/core/View.php

class View
{
    /**
     * Renderiza una vista con el layout indicado.
     *
     * @param string $vista   Ruta relativa bajo src/views (sin .php). 
     *                        Ej: 'productos/show' o 'admin/dashboard'
     * @param array  $data    Variables a extraer para la vista
     * @param string $layout  'public' para frontend, 'admin' para panel de administración
     */
    public static function render(string $vista, array $data = [], string $layout = 'public'): void
    {
        // Limpia el nombre de la vista para evitar rutas raras
        $vista = trim($vista, '/');

        // Extrae variables para la vista
        extract($data, EXTR_SKIP);

        // Arma la ruta final
        $basePath = __DIR__ . '/../views/';
        $header   = $layout === 'admin' ? 'layouts/admin/admin_header.php' : 'layouts/header.php';
        $footer   = $layout === 'admin' ? 'layouts/admin/admin_footer.php' : 'layouts/footer.php';
        $menu     = $layout === 'admin' ? 'layouts/admin/admin_menu.php' : null;
        $viewPath = $layout === 'admin'
            ? "$basePath/admin/$vista.php"
            : "$basePath/$vista.php";

        // Verifica existencia de la vista antes de cargar
        if (!file_exists($viewPath)) {
            http_response_code(404);
            die("Vista '$vista' no encontrada.");
        }

        require $basePath . $header;
        if ($menu) require $basePath . $menu;
        echo '<div class="main" id="content-area">';
        require $viewPath;
        echo '</div>';
        require $basePath . $footer;
    }
    public static function renderPartial(string $vista, array $data = []): void
{
    $vista = trim($vista, '/');
    extract($data, EXTR_SKIP);

    $basePath = __DIR__ . '/../views/';
    $viewPath = "$basePath/admin/$vista.php";

    if (!file_exists($viewPath)) {
        http_response_code(404);
        die("Vista '$vista' no encontrada.");
    }

    require $viewPath;
}
}
