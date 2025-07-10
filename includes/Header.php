
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Props Fotográficos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
    <?php
    if (isset($page_css)) {
        echo '<link rel="stylesheet" href="css/' . htmlspecialchars($page_css) . '">';
    }
        if (isset($page2_css)) {
        echo '<link rel="stylesheet" href="css/' . htmlspecialchars($page2_css) . '">';
    }
        if (isset($page3_css)) {
        echo '<link rel="stylesheet" href="css/' . htmlspecialchars($page3_css) . '">';
    }
    ?>
</head>
<body>
<header>
    <h1>Props Fotográficos</h1>
    <nav>
        <a href="index.php"> <p>Inicio</p></a>
        <a href="dashboard.php"><p>Productos</p></a>
        <a href="login.php"><p>Contacto</p></a>
    </nav>
</header>
<main>