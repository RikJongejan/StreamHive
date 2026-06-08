<?php
// footer.php - Onderste gedeelte van elke pagina
// Sluit de body en html en laadt main.js in.
// De footerbalk wordt verborgen op de auth-pagina's ($hideNav waar).
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
