<?php
$page_css = 'index.css';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . './../../core/db.php';
include_once __DIR__ . './../../views/layouts/header.php';
?>

<section class="carrousel">
  <div class="slide">Imagen 1</div>
  <div class="slide">Imagen 2</div>
  <div class="slide">Imagen 3</div>
</section>

<h2>Productos Destacados</h2>
<section class="productos">
  <?php
  $query = "SELECT * FROM products WHERE destacado = 1 LIMIT 6";
  $result = $conn->query($query);

  if ($result && $result->num_rows > 0):
      while ($row = $result->fetch_assoc()):
  ?>
      <a href="<?= BASE_URL ?>product?id=<?= $row['id'] ?>" class="card">
        <img src="<?= BASE_URL ?>public/img/<?= htmlspecialchars($row['imagen']) ?>" alt="<?= htmlspecialchars($row['nombre']) ?>">
        <div class="card-content">
          <h3><?= htmlspecialchars($row['nombre']) ?></h3>
          <p><?= htmlspecialchars($row['descripcion']) ?></p>
        </div>
      </a>
  <?php endwhile; else: ?>
      <p>No hay productos destacados.</p>
  <?php endif; ?>
</section>

<h2>Últimas Noticias</h2>
<section class="articulos">
  <?php
  $query = "SELECT * FROM articles WHERE is_visible = 1 ORDER BY published_at DESC LIMIT 3";
  $result = $conn->query($query);

  if ($result && $result->num_rows > 0):
      while ($a = $result->fetch_assoc()):
  ?>
      <a href="<?= BASE_URL ?>articulo?id=<?= $a['id'] ?>" class="card">
        <div class="card-content">
          <h3><?= htmlspecialchars($a['title']) ?></h3>
          <small><?= date('d M Y', strtotime($a['published_at'])) ?> — <?= htmlspecialchars($a['author']) ?></small>
          <p><?= mb_substr(strip_tags($a['content']), 0, 100) ?>...</p>
          <span class="leer-mas">Leer más</span>
        </div>
      </a>
  <?php endwhile; else: ?>
      <p>No hay artículos disponibles.</p>
  <?php endif; ?>
</section>

<?php include_once __DIR__ . '/../../views/layouts/footer.php'; ?>
