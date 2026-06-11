<?php
// search.php - View voor de zoekresultatenpagina
// Toont de zoekresultaten voor een opgegeven zoekopdracht:
// - Resultaten als videokaarten in een grid
// - Lege toestand als er niets gevonden is
$videos    = $videos ?? [];
$query     = $query  ?? '';
$pageTitle = 'Search';
require VIEWS_PATH . '/partials/header.php';
?>

<main class="page">
    <div class="container">

        <a class="back-link" href="<?= Helpers::route('video/index') ?>">
            <i class="fa-solid fa-arrow-left"></i> Back to home
        </a>

        <div class="section-head">
            <h2><i class="fa-solid fa-magnifying-glass"></i> Search results</h2>
        </div>

        <?php if ($query === ''): ?>
            <p class="search-summary">Type something in the search bar to find videos.</p>
        <?php else: ?>
            <p class="search-summary">
                <?= count($videos) ?> result<?= count($videos) === 1 ? '' : 's' ?>
                for &ldquo;<b><?= htmlspecialchars($query) ?></b>&rdquo;
            </p>
        <?php endif; ?>

        <?php if ($query !== '' && empty($videos)): ?>
            <div class="empty-state">
                <?php $beeWrap = null; require VIEWS_PATH . '/partials/bee.php'; ?>
                <h3>Nothing found</h3>
                <p>No videos for &ldquo;<?= htmlspecialchars($query) ?>&rdquo;. Try a different word.</p>
                <a class="btn btn-ghost" href="<?= Helpers::route('video/index') ?>">Back to home</a>
            </div>
        <?php elseif (!empty($videos)): ?>
            <div class="video-grid">
                <?php $cardOwner = false; foreach ($videos as $card) { require VIEWS_PATH . '/partials/video-card.php'; } ?>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
