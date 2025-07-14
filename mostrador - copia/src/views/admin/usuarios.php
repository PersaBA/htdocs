<?php
// src/views/admin/usuarios.php
/**
 * Variables disponibles:
 *   - $usuarios : mysqli_result
 *   - $mensaje  : string
 */
?>

<h1>Gestión de Usuarios</h1>

<?php if (!empty($mensaje)): ?>
  <div class="alert success"
       style="margin:1em 0; padding:0.75em;
              border:1px solid #7db97d;
              background:#e1f3e1; color:#2d5f2d;">
    <?= htmlspecialchars($mensaje) ?>
  </div>
<?php endif; ?>

<div style="display:flex; gap:2em; padding:1em 0;">

  <section style="flex:1;">
    <h2>Crear nuevo usuario</h2>
    <form data-ajax
          action="<?= BASE_URL ?>admin/usuarios/crear"
          method="POST">
      <div>
        <label>Nombre</label><br>
        <input type="text" name="nombre" required>
      </div>
      <div>
        <label>Email</label><br>
        <input type="email" name="email" required>
      </div>
      <div>
        <label>Contraseña</label><br>
        <input type="password" name="password" required>
      </div>
      <div>
        <label>Rol</label><br>
        <select name="role" required>
          <option value="admin">Admin</option>
          <option value="dueno">Dueño</option>
        </select>
      </div>
      <button type="submit" style="margin-top:0.5em;">Crear usuario</button>
    </form>
  </section>

  <section style="flex:2; overflow-x:auto;">
    <h2>Usuarios registrados</h2>
    <table border="1" cellpadding="8" cellspacing="0"
           style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th>Nombre</th><th>Email</th><th>Rol</th><th>Creado</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($u = $usuarios->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($u['nombre']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td><?= htmlspecialchars($u['created_at'] ?? '') ?></td>
            <td>
              <a href="<?= BASE_URL ?>admin/usuarios/editar?id=<?= $u['id'] ?>">✏️</a>
              &nbsp;
              <a data-ajax-delete
                 href="<?= BASE_URL ?>admin/usuarios/eliminar?id=<?= $u['id'] ?>"
                 style="color:red;">
                🗑️
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </section>

</div>
