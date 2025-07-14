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
        // Extrae variables para la vista
        extract($data, EXTR_SKIP);

        if ($layout === 'admin') {
            // Layout administración
            require __DIR__ . '/../views/layouts/admin/admin_header.php';
            require __DIR__ . '/../views/layouts/admin/admin_menu.php';
            require __DIR__ . '/../views/admin/' . $vista . '.php';
            require __DIR__ . '/../views/layouts/admin/admin_footer.php';
        } else {
            // Layout público (por defecto)
            require __DIR__ . '/../views/layouts/header.php';
            require __DIR__ . '/../views/' . $vista . '.php';
            require __DIR__ . '/../views/layouts/footer.php';
        }
    }
}
