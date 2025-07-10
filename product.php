<?php
// Incluir conexión a la base de datos
include 'includes/db.php';

// Definir CSS específico para esta página (si usás includes/header.php que lo usa)
$page_css = 'product.css';

// Incluir header con estructura HTML y CSS
include 'includes/header.php';

// Verificar que se haya pasado un id válido por GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Producto no válido.</p>";
    include 'includes/footer.php';
    exit; // Terminar script si no hay id válido
}

$id = (int) $_GET['id'];
$sql = "SELECT p.*, c.nombre AS categoria_nombre
        FROM products p
        LEFT JOIN categories c ON p.categoria_id = c.id
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "<p>Error en la consulta.</p>";
    include 'includes/footer.php';
    exit;
}

// Vincular parámetros
$stmt->bind_param("i", $id);

// Ejecutar consulta
$stmt->execute();

// Obtener resultado
$result = $stmt->get_result();

// Verificar si encontró producto
if ($result->num_rows === 0) {
    echo "<p>Producto no encontrado.</p>";
    include 'includes/footer.php';
    exit;
}


// Obtener datos del producto
$producto = $result->fetch_assoc();

?>

<!-- Mostrar información del producto -->
<article class="product-detail parent">
    <div class="div1">
        <a href="index.php" class="btn-volver ">← Volver al listado</a>
    </div>
    <h2 class="div2"><?= htmlspecialchars($producto['nombre']) ?></h2>
    <div class="div3">
        <img src="img/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
    </div>
    <div class="div4">
        <p><span>Precio:</span> $<?= number_format($producto['precio'], 2, ',', '.') ?></p>
        <p><span>Categoría:</span> <?= htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría') ?></p>
        <p> <span>Descripción:</span> <?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
    </div>
    <div class="div5">
        <a href="consulta.php?id=<?= $producto['id'] ?>" class="btn btn-primary">
        <div class="btn-consulta">
            Consultar</a>
        </div>
        </a>
    </div>
</article>


<?php
// Incluir footer que cierra HTML y puede cargar JS
include 'includes/footer.php';
?>