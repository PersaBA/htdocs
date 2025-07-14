<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $meta_title ?? 'Panel Admin' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- estilos públicos -->
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">

  <!-- estilos admin -->
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/admin/header.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/admin/menu.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/admin/admin.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/admin/gestion.css">
</head>
<body>
  <header>
    <h1>Panel de Administración</h1>
    <nav>
      <a href="<?= BASE_URL ?>admin"><p>User</p></a>
      <a href="<?= BASE_URL ?>admin/dashboard"><p>Config</p></a>
      <a href="<?= BASE_URL ?>logout"><p>Log Out</p></a>
    </nav>
  </header>

  <!-- abrimos el main flex: menú + contenido -->
  <main class="dashboard-layout">