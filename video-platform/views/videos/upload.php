<?php
// upload.php - Pagina voor het uploaden van een video
// Formulier met: titel, beschrijving, videobestand, thumbnail en categorieen.
// Bestaande categorieen als chips (checkboxes), nieuwe als komma-gescheiden tekst.
// Bij submit verwerkt VideoController het bestand en koppelt CategoryService de categorieen.
$pageTitle = 'Uploaden';
require VIEWS_PATH . '/partials/header.php';
?>

<main class="page">
    <div class="container">

        <a class="back-link" href="<?= route('video/index') ?>">
            <i class="fa-solid fa-arrow-left"></i> Terug naar home
        </a>

        <div class="form-card">
            <h1><i class="fa-solid fa-cloud-arrow-up"></i> Video uploaden</h1>
            <p class="muted">Deel je video met de zwerm. Max 500 MB &mdash; MP4, WebM of OGG.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= route('video/upload') ?>" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Titel</label>
                    <input class="input" type="text" name="title" placeholder="Geef je video een pakkende titel" required>
                </div>

                <div class="form-group">
                    <label>Beschrijving</label>
                    <textarea class="input" name="description" rows="4" placeholder="Waar gaat je video over?"></textarea>
                </div>

                <div class="form-group">
                    <label>Videobestand</label>
                    <div class="filefield">
                        <input type="file" name="video" accept="video/*" required>
                        <div class="filebox">
                            <i class="fa-solid fa-film"></i>
                            <span class="file-name" data-placeholder="Kies een videobestand">Kies een videobestand (MP4, WebM, OGG)</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Thumbnail <small>(optioneel)</small></label>
                    <div class="filefield">
                        <input type="file" name="thumbnail" accept="image/*">
                        <div class="filebox">
                            <i class="fa-solid fa-image"></i>
                            <span class="file-name" data-placeholder="Kies een afbeelding">Kies een afbeelding (JPG, PNG, WebP)</span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($categories)): ?>
                    <div class="form-group">
                        <label>Categorie&euml;n</label>
                        <div class="chip-grid">
                            <?php foreach ($categories as $category): ?>
                                <div class="chip-check">
                                    <input type="checkbox" id="cat-<?= $category['id'] ?>" name="categories[]" value="<?= $category['id'] ?>">
                                    <label for="cat-<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Nieuwe categorie&euml;n toevoegen <small>(komma-gescheiden)</small></label>
                    <input class="input" type="text" name="new_categories" placeholder="bijv. Gaming, Muziek, Vlog">
                </div>

                <button class="btn btn-honey btn-lg" type="submit">
                    <i class="fa-solid fa-upload"></i> Uploaden
                </button>
            </form>
        </div>

    </div>
</main>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
