<div class="menu admin-menu">
  <ul>
    <li class="menu-item">
      <span class="toggle">Usuarios</span>
      <ul class="submenu">
        <li><a href="<?= BASE_URL ?>admin/usuarios?view=register">➕ Crear usuario</a></li>
        <li><a href="<?= BASE_URL ?>admin/usuarios?view=table">📋 Lista de usuarios</a></li>
      </ul>
    </li>

    <li class="menu-item">
      <span class="toggle">Productos</span>
      <ul class="submenu">
        <li><a href="<?= BASE_URL ?>admin/productos?view=register">📦 Crear producto</a></li>
        <li><a href="<?= BASE_URL ?>admin/productos?view=table">🛒 Lista de productos</a></li>
      </ul>
    </li>

    <li class="menu-item">
      <span class="toggle">Artículos</span>
      <ul class="submenu">
        <li><a href="<?= BASE_URL ?>admin/articulos?view=register">📝 Nuevo artículo</a></li>
        <li><a href="<?= BASE_URL ?>admin/articulos?view=table">📚 Lista de artículos</a></li>
      </ul>
    </li>

    <li class="menu-item">
      <span class="toggle">Categorías</span>
      <ul class="submenu">
        <li><a href="<?= BASE_URL ?>admin/categorias?view=register">🏷️ Nueva categoría</a></li>
        <li><a href="<?= BASE_URL ?>admin/categorias?view=table">📂 Lista de categorías</a></li>
      </ul>
    </li>
  </ul>
</div>