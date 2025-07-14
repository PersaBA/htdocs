<?php
include 'db.php';
$page_css = 'article.css';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = "SELECT titulo, contenido, publicado_en FROM articles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Noticia no encontrada.</p>";
    include 'includes/footer.php';
    exit;
}

$article = $result->fetch_assoc();
?>

<article class="noticia-detalle">
    <h2><?= htmlspecialchars($article['titulo']) ?></h2>
    <p class="fecha"><?= date('d/m/Y H:i', strtotime($article['publicado_en'])) ?></p>
    <div class="contenido"><?= nl2br(htmlspecialchars($article['contenido'])) ?></div>
    <a href="noticias.php" class="btn-volver">← Volver a noticias</a>
</article>

<?php include 'includes/footer.php'; ?>
