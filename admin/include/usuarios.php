<?php
include_once __DIR__ . '/../../includes/db.php';

// Mostrar mensajes de resultado si existen
$msg = $_GET['msg'] ?? '';
$mensaje = '';

switch ($msg) {
    case 'creado':
        $mensaje = "✅ Usuario creado correctamente.";
        break;
    case 'editado':
        $mensaje = "✏️ Usuario editado correctamente.";
        break;
    case 'eliminado':
        $mensaje = "🗑️ Usuario eliminado correctamente.";
        break;
}
?>

<?php if ($mensaje): ?>
    <div style="margin: 10px 20px; padding: 10px; background-color: #e1f3e1; border: 1px solid #7db97d; color: #2d5f2d;">
        <?= htmlspecialchars($mensaje) ?>
    </div>
<?php endif; ?>



<?php
// Obtener usuarios de la base
$sql = "SELECT id, nombre, email, role FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div style="display: flex; gap: 20px; align-items: flex-start; padding: 20px;">
    
    <!-- FORMULARIO DE CREACIÓN -->
    <div style="flex: 1;">
        <h2>Crear nuevo usuario</h2>
        <form action="/admin/actions/users/create_user.php" method="POST">
            <label>Nombre:</label><br>
            <input type="text" name="nombre" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Contraseña:</label><br>
            <input type="password" name="password" required><br><br>

            <label>Rol:</label><br>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="dueno">Dueño</option>
            </select><br><br>

            <button type="submit">Crear usuario</button>
        </form>
    </div>

    <!-- LISTADO DE USUARIOS -->
    <div style="flex: 2;">
        <h2>Usuarios registrados</h2>
        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <form action="/admin/actions/users/edit_user.php" method="POST">
                            <td>
                                <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                            </td>
                            <td>
                                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </td>
                            <td>
                                <select name="role">
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="dueno" <?= $user['role'] === 'dueno' ? 'selected' : '' ?>>Dueño</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit">Guardar</button>
                                <a href="/admin/actions/users/delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
