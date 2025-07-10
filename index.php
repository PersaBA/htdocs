<?php 
$page_css = 'index.css';
$page2_css = 'card.css';
include 'includes/db.php';
include 'includes/header.php'; 
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
        while($row = $result->fetch_assoc()):
    ?>

<a href="product.php?id=<?= $row['id'] ?>" class="card-producto">
    <img src="img/<?= htmlspecialchars($row['imagen']) ?>" alt="<?= htmlspecialchars($row['nombre']) ?>">
    <div class="card-content">
        <h3><?= htmlspecialchars($row['nombre']) ?></h3>
        <p><?= htmlspecialchars($row['descripcion']) ?></p>
    </div>
</a>
</div>
    <?php endwhile; else: ?>
        <p>No hay productos destacados.</p>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
