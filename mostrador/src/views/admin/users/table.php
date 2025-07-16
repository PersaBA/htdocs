<?php
// src/views/admin/users/table.php
?>

<h2 class="table-title">👥 Usuarios registrados</h2>

<div class="table-block">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Nombre completo</th>
        <th>Correo electrónico</th>
        <th>Rol</th>
        <th>Fecha de creación</th>
        <th class="col-actions">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($users->num_rows): ?>
        <?php while ($u = $users->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($u['nombre']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
            <td class="col-actions">
              <a href="#"
                class="btn-edit"
                data-edit
                data-type="usuarios"
                data-id="<?= $u['id'] ?>"
                title="Editar usuario">
                ✏️
              </a>
              
              <a href="#"
                class="btn-delete"
                data-ajax-delete
                data-url="<?= BASE_URL ?>admin/usuarios/delete"
                data-id="<?= $u['id'] ?>"
                data-confirm="¿Eliminar este usuario?"
                title="Eliminar usuario">
                🗑️
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">⚠️ No hay usuarios registrados</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
  const BASE_URL = "<?= BASE_URL ?>";
</script>