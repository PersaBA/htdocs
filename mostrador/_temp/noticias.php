<?php
include 'db.php';
$page_css = 'noticias.css';
include 'includes/header.php';

$sql = "SELECT id, titulo, contenido FROM articles ORDER BY publicado_en DESC";
$result = $conn->query($sql);
?>

<h2>Últimas Noticias</h2>
<div class="noticias-grid">
<?php while ($row = $result->fetch_assoc()): ?>
    <a href="article.php?id=<?= $row['id'] ?>" class="noticia-card">
        <h3><?= htmlspecialchars($row['titulo']) ?></h3>
        <p><?= mb_strimwidth(strip_tags($row['contenido']), 0, 160, '...') ?></p>
    </a>
<?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>
