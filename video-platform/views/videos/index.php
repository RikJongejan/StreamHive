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
                <h1>Welcome to the <span class="accent">Hive</span></h1>
                <p>Discover, share, and connect. Thousands of videos, all in one place &mdash; as busy as a beehive.</p>
                <div class="hero-actions">
                    <a class="btn btn-honey btn-lg" href="<?= Helpers::route('video/upload') ?>">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload your video
                    </a>
                    <a class="btn btn-ghost btn-lg" href="<?= Helpers::route('user/profile') ?>">
                        <i class="fa-solid fa-user"></i> My channel
                    </a>
                </div>
            </div>
            <img class="hero-art" src="<?= ASSETS_URL ?>/images/logo.png" alt=""
                 onerror="this.onerror=null;this.src='<?= ASSETS_URL ?>/images/logo.svg'">
        </section>

        <?php if (!empty($categories)): ?>
            <div class="cat-bar">
                <a class="cat-chip-link <?= $activeCategory === 0 ? 'active' : '' ?>" href="<?= Helpers::route('video/index') ?>">
                    <i class="fa-solid fa-border-all"></i> All
                </a>
                <?php foreach ($categories as $category): ?>
                    <a class="cat-chip-link <?= $activeCategory === (int) $category['id'] ? 'active' : '' ?>"
                       href="<?= Helpers::route('video/index', ['cat' => $category['id']]) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="section-head">
            <h2>
                <i class="fa-solid fa-fire"></i>
                <?php if ($activeCategory > 0): ?>
                    Category
                <?php else: ?>
                    Latest videos
                <?php endif; ?>
            </h2>
        </div>

        <?php if (empty($videos)): ?>
            <div class="empty-state">
                <?php $beeWrap = null; require VIEWS_PATH . '/partials/bee.php'; ?>
                <h3><?= $activeCategory > 0 ? 'No videos in this category yet' : 'No videos in the hive yet' ?></h3>
                <p>Be the first to share something with the swarm.</p>
                <a class="btn btn-honey" href="<?= Helpers::route('video/upload') ?>">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload your first video
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
