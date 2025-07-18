<!-- src/views/admin/dashboard.php -->

<?php

require_once __DIR__ . '/../../core/auth.php'; 

?>

<h1>Panel principal</h1>

<p>Bienvenido al sistema de administración. Aquí tienes un resumen rápido:</p>

<div class="dashboard-cards">
  <div class="card">
    <h2>Usuarios</h2>
    <p><?= $stats['users'] ?? '—' ?></p>
  </div>

  <div class="card">
    <h2>Productos</h2>
    <p><?= $stats['products'] ?? '—' ?></p>
  </div>

</div>
