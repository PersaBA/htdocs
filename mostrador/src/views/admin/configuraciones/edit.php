
<?php 
require_once __DIR__ . '/../../../core/auth.php'; 
?>

<form method="POST"
      action="<?= BASE_URL ?>admin/configuraciones/editar"
      data-ajax-form
      data-ajax
      class="form-block">

  <h2 class="form-title">✏️ Editar configuración</h2>
  <input type="hidden" name="id" value="<?= $config['id'] ?>">

  <div class="form-group">
    <label for="settings-clave">Clave</label>
    <input type="text" id="settings-clave" name="clave"
           value="<?= htmlspecialchars($config['clave']) ?>"
           class="form-input" required>
  </div>

  <div class="form-group">
    <label for="settings-valor">Valor</label>
    <textarea id="settings-valor" name="valor"
              class="form-input" rows="4" required><?= htmlspecialchars($config['valor']) ?></textarea>
  </div>

  <div class="form-group">
    <label for="settings-tipo">Tipo</label>
    <select name="tipo" id="settings-tipo" class="form-input" required>
      <?php
        $tipos = ['texto', 'color', 'enlace', 'booleano', 'email', 'numero', 'json'];
        foreach ($tipos as $tipo):
          $selected = ($tipo === $config['tipo']) ? 'selected' : '';
          echo "<option value=\"$tipo\" $selected>" . ucfirst($tipo) . "</option>";
        endforeach;
      ?>
    </select>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn-primary">💾 Guardar cambios</button>
  </div>
</form>
