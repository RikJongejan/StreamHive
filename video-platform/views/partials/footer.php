<?php
// footer.php - Gedeelde paginafooter
// Sluit de HTML af en laadt scripts:
// - Toont de sitewide footer tenzij $hideNav op true staat
$hideNav = $hideNav ?? false;
?>
    <?php if (!$hideNav): ?>
    <footer class="site-footer">
        <div class="container">
            <a class="brand" href="<?= route('video/index') ?>">
                <img src="<?= ASSETS_URL ?>/images/logo.png" alt="StreamHive logo"
                     onerror="this.onerror=null;this.src='<?= ASSETS_URL ?>/images/logo.svg'">
                <span><span class="b-stream">Stream</span><span class="b-hive">Hive</span></span>
            </a>
            <span>&copy; <?= date('Y') ?> StreamHive &mdash; Watch &middot; Share &middot; Connect</span>
        </div>
    </footer>
    <?php endif; ?>

    <script src="<?= ASSETS_URL ?>/js/main.js"></script>
</body>
</html>
