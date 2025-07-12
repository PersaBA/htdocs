<?php
$page_css = 'product.css';
require_once __DIR__ . '/../../../config/config.php';
include __DIR__ . '/../layouts/header.php';
?>

<article class="product-detail parent">
    <div class="div1">
        <a href="<?= BASE_URL ?>" class="btn-volver">← Volver al listado</a>
    </div>
    <h2 class="div2"><?= htmlspecialchars($producto['nombre']) ?></h2>
    <div class="div3">
        <img src="<?= BASE_URL ?>public/img/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
    </div>
    <div class="div4">
        <p><span>Precio:</span> $<?= number_format($producto['precio'], 2, ',', '.') ?></p>
        <p><span>Categoría:</span> <?= htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría') ?></p>
        <p><span>Descripción:</span> <?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
    </div>
    <div class="div5">
        <a href="<?= BASE_URL ?>consulta?id=<?= $producto['id'] ?>" class="btn btn-primary">
            <div class="btn-consulta">Consultar</div>
        </a>
    </div>
</article>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
