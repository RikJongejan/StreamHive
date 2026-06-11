<?php
// upload.php - View voor het uploadformulier
// Toont het formulier voor het uploaden van een nieuwe video:
// - Titel, beschrijving en videobestand
// - Optionele thumbnail
// - Categorieën aanvinken of nieuwe aanmaken
$categories = $categories ?? [];
$error      = $error      ?? '';
$pageTitle = 'Upload';
require VIEWS_PATH . '/partials/header.php';
?>

<main class="page">
    <div class="container">

        <a class="back-link" href="<?= Helpers::route('video/index') ?>">
            <i class="fa-solid fa-arrow-left"></i> Back to home
        </a>

        <div class="form-card">
            <h1><i class="fa-solid fa-cloud-arrow-up"></i> Upload a video</h1>
            <p class="muted">Share your video with the swarm. Max 500 MB &mdash; MP4, WebM or OGG.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= Helpers::route('video/upload') ?>" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Title</label>
                    <input class="input" type="text" name="title" placeholder="Give your video a catchy title" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea class="input" name="description" rows="4" placeholder="What is your video about?"></textarea>
                </div>

                <div class="form-group">
                    <label>Video file</label>
                    <div class="filefield">
                        <input type="file" name="video" accept="video/*" required>
                        <div class="filebox">
                            <i class="fa-solid fa-film"></i>
                            <span class="file-name" data-placeholder="Choose a video file">Choose a video file (MP4, WebM, OGG)</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Thumbnail <small>(optional)</small></label>
                    <div class="filefield">
                        <input type="file" name="thumbnail" accept="image/*">
                        <div class="filebox">
                            <i class="fa-solid fa-image"></i>
                            <span class="file-name" data-placeholder="Choose an image">Choose an image (JPG, PNG, WebP)</span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($categories)): ?>
                    <div class="form-group">
                        <label>Categories</label>
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
                    <label>Add new categories <small>(comma-separated)</small></label>
                    <input class="input" type="text" name="new_categories" placeholder="e.g. Gaming, Music, Vlog">
                </div>

                <button class="btn btn-honey btn-lg" type="submit">
                    <i class="fa-solid fa-upload"></i> Upload
                </button>
            </form>
        </div>

    </div>
</main>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
