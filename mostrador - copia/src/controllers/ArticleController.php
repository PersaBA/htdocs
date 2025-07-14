<?php
// src/controllers/ArticleController.php

class ArticleController
{
    public function index(): void
    {
        // Vista de artículos aún sin implementar
        echo "<pre>Artículos en construcción...</pre>";
    }

    public function crear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "<pre>El método crear() requiere solicitud POST.</pre>";
            return;
        }

        // Lógica de creación en construcción
    }

    public function editar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "<pre>El método editar() requiere solicitud POST.</pre>";
            return;
        }

        // Lógica de edición en construcción
    }

    public function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo "<pre>El método eliminar() requiere solicitud GET con parámetro id.</pre>";
            return;
        }

        // Lógica de eliminación en construcción
    }
}
