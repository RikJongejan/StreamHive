<?php
// search.php - View voor de zoekresultatenpagina
// Toont de zoekresultaten voor een opgegeven zoekopdracht:
// - Resultaten als videokaarten in een grid
// - Lege toestand als er niets gevonden is
$videos    = $videos ?? [];
$query     = $query  ?? '';
$pageTitle = 'Zoeken';
require VIEWS_PATH . '/partials/header.php';
?>

<main class="page">
    <div class="container">

        <a class="back-link" href="<?= route('video/index') ?>">
            <i class="fa-solid fa-arrow-left"></i> Terug naar home
        </a>

        <div class="section-head">
            <h2><i class="fa-solid fa-magnifying-glass"></i> Zoekresultaten</h2>
        </div>

        <?php if ($query === ''): ?>
            <p class="search-summary">Typ iets in de zoekbalk om video's te vinden.</p>
        <?php else: ?>
            <p class="search-summary">
                <?= count($videos) ?> resultaat<?= count($videos) === 1 ? '' : 'en' ?>
                voor &ldquo;<b><?= htmlspecialchars($query) ?></b>&rdquo;
            </p>
        <?php endif; ?>

        <?php if ($query !== '' && empty($videos)): ?>
            <div class="empty-state">
                <?php $beeWrap = null; require VIEWS_PATH . '/partials/bee.php'; ?>
                <h3>Niets gevonden</h3>
                <p>Geen video's voor &ldquo;<?= htmlspecialchars($query) ?>&rdquo;. Probeer een ander woord.</p>
                <a class="btn btn-ghost" href="<?= route('video/index') ?>">Terug naar home</a>
            </div>
        <?php elseif (!empty($videos)): ?>
            <div class="video-grid">
                <?php $cardOwner = false; foreach ($videos as $card) { require VIEWS_PATH . '/partials/video-card.php'; } ?>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
