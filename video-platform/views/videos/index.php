<?php
// index.php - View voor de video-overzichtspagina (home)
// Toont alle video's in een grid met optioneel categoriefilter:
// - Categoriebalk bovenaan om te filteren
// - Videokaarten via de video-card partial
// - Lege toestand met de bee-animatie als er geen video's zijn
$videos         = $videos         ?? [];
$categories     = $categories     ?? [];
$activeCategory = $activeCategory ?? 0;
$pageTitle = 'Home';
require VIEWS_PATH . '/partials/header.php';
?>

<main class="page">
    <div class="container">

        <section class="hero">
            <div class="hero-text">
                <h1>Welkom in de <span class="accent">Hive</span></h1>
                <p>Ontdek, deel en verbind. Duizenden video's, allemaal op één plek &mdash; net zo druk als een bijenkorf.</p>
                <div class="hero-actions">
                    <a class="btn btn-honey btn-lg" href="<?= route('video/upload') ?>">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload je video
                    </a>
                    <a class="btn btn-ghost btn-lg" href="<?= route('user/profile') ?>">
                        <i class="fa-solid fa-user"></i> Mijn kanaal
                    </a>
                </div>
            </div>
            <img class="hero-art" src="<?= ASSETS_URL ?>/images/logo.png" alt=""
                 onerror="this.onerror=null;this.src='<?= ASSETS_URL ?>/images/logo.svg'">
        </section>

        <?php if (!empty($categories)): ?>
            <div class="cat-bar">
                <a class="cat-chip-link <?= $activeCategory === 0 ? 'active' : '' ?>" href="<?= route('video/index') ?>">
                    <i class="fa-solid fa-border-all"></i> Alles
                </a>
                <?php foreach ($categories as $category): ?>
                    <a class="cat-chip-link <?= $activeCategory === (int) $category['id'] ? 'active' : '' ?>"
                       href="<?= route('video/index', ['cat' => $category['id']]) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="section-head">
            <h2>
                <i class="fa-solid fa-fire"></i>
                <?php if ($activeCategory > 0): ?>
                    Categorie
                <?php else: ?>
                    Nieuwste video's
                <?php endif; ?>
            </h2>
        </div>

        <?php if (empty($videos)): ?>
            <div class="empty-state">
                <?php $beeWrap = null; require VIEWS_PATH . '/partials/bee.php'; ?>
                <h3><?= $activeCategory > 0 ? 'Nog geen video\'s in deze categorie' : 'Nog geen video\'s in de korf' ?></h3>
                <p>Wees de eerste die iets deelt met de zwerm.</p>
                <a class="btn btn-honey" href="<?= route('video/upload') ?>">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload je eerste video
                </a>
            </div>
        <?php else: ?>
            <div class="video-grid">
                <?php $cardOwner = false; foreach ($videos as $card) { require VIEWS_PATH . '/partials/video-card.php'; } ?>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
