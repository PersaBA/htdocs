<form method="POST" action="<?= BASE_URL ?>admin/productos/editar" data-ajax-form enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?= $product['id'] ?>">

  <label>Nombre</label>
  <input type="text" name="nombre" value="<?= htmlspecialchars($product['nombre']) ?>" required>

  <label>Descripción</label>
  <textarea name="descripcion" required><?= htmlspecialchars($product['descripcion']) ?></textarea>

  <label>Precio</label>
  <input type="number" name="precio" value="<?= htmlspecialchars($product['precio']) ?>" step="0.01" required>

  <label>Stock</label>
  <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>

  <label>Imagen actual</label>
  <?php if (!empty($product['imagen'])): ?>
    <img src="<?= BASE_URL ?>uploads/<?= $product['imagen'] ?>" alt="Imagen actual" style="max-width:120px;">
  <?php endif; ?>
  <input type="file" name="imagen" accept="image/*">

<label for="categoria_id">Categoría</label>
<select name="categoria_id" id="categoria_id" required>
  <?php foreach ($cats as $cat): ?>
    <option 
      value="<?= $cat['id'] ?>" 
      <?= ($cat['id'] == $product['categoria_id']) ? 'selected' : '' ?>>
      <?= htmlspecialchars($cat['nombre']) ?>
    </option>
  <?php endforeach; ?>
</select>

  <label>¿Destacado?</label>
  <select name="destacado">
    <option value="1" <?= $product['destacado'] ? 'selected' : '' ?>>Sí</option>
    <option value="0" <?= !$product['destacado'] ? 'selected' : '' ?>>No</option>
  </select>

  <fieldset>
    <legend>Oferta</legend>

    <label>¿Activa?</label>
    <select name="oferta_activa">
      <option value="1" <?= $product['oferta_activa'] ? 'selected' : '' ?>>Sí</option>
      <option value="0" <?= !$product['oferta_activa'] ? 'selected' : '' ?>>No</option>
    </select>

    <label>Monto de oferta</label>
    <input type="number" name="oferta_monto" value="<?= htmlspecialchars($product['oferta_monto']) ?>" step="0.01">

    <label>Tipo de oferta</label>
    <select name="oferta_tipo">
      <option value="porcentaje" <?= $product['oferta_tipo'] === 'porcentaje' ? 'selected' : '' ?>>%</option>
      <option value="fijo" <?= $product['oferta_tipo'] === 'fijo' ? 'selected' : '' ?>>Monto fijo</option>
    </select>
  </fieldset>

  <button type="submit">💾 Guardar cambios</button>
</form>
