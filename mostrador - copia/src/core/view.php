<?php
// src/core/View.php


class View
{
    public static function render(string $vista, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        require __DIR__ . '/../views/layouts/admin/admin_header.php';
        require __DIR__ . '/../views/layouts/admin/admin_menu.php';
        require __DIR__ . '/../views/admin/' . $vista . '.php';
        require __DIR__ . '/../views/layouts/admin/admin_footer.php';
    }
}