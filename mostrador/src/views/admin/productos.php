<?php
// src/views/admin/productos.php
/**
 * Variables disponibles:
 *   - $cats     : mysqli_result
 *   - $products : mysqli_result
 *   - $mensaje  : string
 */
?>

<h1>Gestión de Productos</h1>

<?php if (!empty($mensaje)): ?>
  <div class="alert success" style="margin:1em 0; padding:0.75em; border:1px solid #7db97d; background:#e1f3e1; color:#2d5f2d;">
    <?= htmlspecialchars($mensaje) ?>
  </div>
<?php endif; ?>

<div id="content-area">
  <div style="display:flex; gap:2em; padding:1em 0;">

    <!-- Formulario Crear / Editar -->
    <section style="flex:1; max-width:380px;">
      <h2>Crear / Editar producto</h2>
      <form data-ajax
        action="<?= BASE_URL ?>admin/productos/crear"
        method="POST"
        enctype="multipart/form-data">

        <input type="hidden" name="id" value="">

        <div>
          <label>Nombre</label><br>
          <input type="text" name="nombre" required>
        </div>

        <div>
          <label>Descripción</label><br>
          <textarea name="descripcion" rows="3"></textarea>
        </div>

        <div>
          <label>Precio</label><br>
          <input type="number" name="precio" step="0.01" required>
        </div>

        <div>
          <label>Stock</label><br>
          <input type="number" name="stock" value="0" required>
        </div>

        <div>
          <label>Categoría</label><br>
          <select name="categoria_id" required>
            <option value="">– Seleccionar –</option>
            <?php if ($cats): while ($c = $cats->fetch_assoc()): ?>
              <option value="<?= $c['id'] ?>">
                <?= htmlspecialchars($c['nombre']) ?>
              </option>
            <?php endwhile; endif; ?>
          </select>
        </div>

        <div>
          <label>
            <input type="checkbox" id="chkOferta"> Oferta activa
          </label>
        </div>

        <div id="ofertaFields" style="display:none;">
          <div>
            <label>Monto de oferta</label><br>
            <input type="number" name="oferta_monto" step="0.01">
          </div>
          <div>
            <label>Tipo de oferta</label><br>
            <select name="oferta_tipo">
              <option value="porcentaje">Porcentaje</option>
              <option value="pesos">Pesos</option>
            </select>
          </div>
        </div>

        <div>
          <label>Imagen</label><br>
          <div id="drop-zone"
               style="padding:20px; border:2px dashed #ccc; text-align:center; cursor:pointer;">
            Arrastra o haz clic
          </div>
          <input type="file"
                 name="imagen"
                 id="imgInput"
                 accept="image/jpeg,image/png"
                 style="display:none;">
          <img id="preview"
               src=""
               style="max-width:100%; margin-top:10px; display:block;">
        </div>

        <button type="submit" style="margin-top:0.5em;">Guardar</button>
      </form>
    </section>

    <!-- Tabla de Productos -->
    <section style="flex:2; overflow-x:auto;">
      <h2>Productos registrados</h2>
      <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
        <thead>
          <tr>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Categoría</th>
            <th>Oferta</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($products): while ($p = $products->fetch_assoc()): ?>
            <tr>
              <td style="text-align:center; width:80px;">
                <?php if ($p['imagen']): ?>
                  <img src="<?= BASE_URL ?>img/<?= htmlspecialchars($p['imagen']) ?>"
                       style="max-width:60px;">
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($p['nombre']) ?></td>
              <td>$<?= number_format($p['precio'], 2) ?></td>
              <td><?= $p['stock'] ?></td>
              <td><?= htmlspecialchars($p['categoria_nombre']) ?></td>
              <td>
                <?php if ($p['oferta_activa']): ?>
                  <?= $p['oferta_tipo'] === 'porcentaje'
                      ? $p['oferta_monto'] . '%'
                      : '$' . number_format($p['oferta_monto'], 2) ?>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
              <td>
                <!-- Editar in-place -->
                <a href="#"
                  class="btn-edit"
                  data-product='<?= json_encode($p, JSON_HEX_APOS) ?>'>
                  ✏️
                </a>
                &nbsp;|&nbsp;
                <!-- Eliminar AJAX -->
                <a href="<?= BASE_URL ?>admin/productos/eliminar?id=<?= $p['id'] ?>"
                   data-ajax-delete
                   style="color:red;">
                  🗑️
                </a>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr>
              <td colspan="7" style="text-align:center; color:#a00;">
                ⚠ No hay productos
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

  </div>
</div>

<!-- Incluimos al final tu script unificado -->
<script src="<?= BASE_URL ?>public/js/admin.js"></script>
