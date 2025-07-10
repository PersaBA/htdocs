<?php
$page_css = 'admin.css';
$page2_css = 'gestion.css';
include '../includes/admin/auth.php';
include '../includes/admin/header.php';
include '../includes/admin/menu.php';
?>
<!-- Visualizador de includes -->
<div id="viewscreen" class="viewscreen">
    <?php include 'include/panel.php'?>
</div>

<script>
function cargarVista(vista) {
    fetch(`include/${vista}.php`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('viewscreen').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('viewscreen').innerHTML = '<p>Error al cargar la vista.</p>';
        });
}
</script>
</main>
</body>
</html>
