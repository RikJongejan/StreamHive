<?php
// upload.php - Pagina voor het uploaden van een video
// Formulier met: titel, beschrijving, videobestand, thumbnail, categorieen
// Bestaande categorieen als checkboxes, nieuwe categorieen als komma-gescheiden tekstveld
// Bij submit verwerkt VideoController het bestand en koppelt CategoryService de categorieen
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Video uploaden - StreamHive</title>
</head>
<body>
    <a href="<?= route('video/index') ?>">&larr; Terug</a>

    <h1>Video uploaden</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= route('video/upload') ?>" enctype="multipart/form-data">

        <label>Titel</label><br>
        <input type="text" name="title" required><br><br>

        <label>Beschrijving</label><br>
        <textarea name="description" rows="4"></textarea><br><br>

        <label>Videobestand</label><br>
        <input type="file" name="video" accept="video/*" required><br><br>

        <label>Thumbnail</label><br>
        <input type="file" name="thumbnail" accept="image/*"><br><br>

        <label>Categorie&euml;n</label><br>

        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <label style="margin-right: 12px;">
                    <input type="checkbox" name="categories[]" value="<?= $category['id'] ?>">
                    <?= htmlspecialchars($category['name']) ?>
                </label>
            <?php endforeach; ?>
            <br><br>
        <?php endif; ?>

        <label>Nieuwe categorie&euml;n toevoegen <small>(komma-gescheiden)</small></label><br>
        <input type="text" name="new_categories" placeholder="bijv. Gaming, Muziek, Vlog"><br><br>

        <button type="submit">Uploaden</button>
    </form>

</body>
</html>
