</main>
<script>
  const BASE_URL = "<?= BASE_URL ?>";
</script>

<?php if (isset($requiredScripts)): ?>
  <?php foreach ($requiredScripts as $script): ?>
    <script src="<?= BASE_URL ?>public/js/<?= $script ?>"></script>
  <?php endforeach; ?>
<?php endif; ?>
<script src="<?= BASE_URL ?>public/js/menu.js"></script>


<?php if (!empty($mensaje)): ?>
  <div id="toast-container"></div>
  <script type="module">
    import { showToast } from '<?= BASE_URL ?>assets/js/toast.js';
    showToast("<?= addslashes($mensaje) ?>", "<?= $mensaje_tipo ?? 'success' ?>");
  </script>
<?php endif; ?>
</body>
</html>
