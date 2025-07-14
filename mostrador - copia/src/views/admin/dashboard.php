<!-- src/views/admin/dashboard.php -->

<?php
// Si en el controlador pasas métricas o datos:
// $stats = ['users' => 10, 'products' => 25];
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
