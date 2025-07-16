<div id="toast-container"></div>
<script src="<?= BASE_URL ?>public/js/admin/ajax-reload.js"></script>
<script src="<?= BASE_URL ?>public/js/admin/oferta-toggle.js"></script>
<script src="<?= BASE_URL ?>public/js/admin/drag-drop.js"></script>
<script src="<?= BASE_URL ?>public/js/admin/ajax-form.js"></script>
<script src="<?= BASE_URL ?>public/js/admin/edit-category.js"></script>
<script src="<?= BASE_URL ?>public/js/admin/ajax-delete.js"></script>
<script src="<?= BASE_URL ?>public/js/menu.js"></script>

<?php if (!empty($mensaje)): ?>
  <script type="module">
    import { showToast } from '<?= BASE_URL ?>assets/js/toast.js';
    showToast("<?= addslashes($mensaje) ?>", "<?= $mensaje_tipo ?? 'success' ?>");
  </script>
<?php endif; ?>
</body>
</html>
