<?php
// search.php - Zoekresultaten pagina
// Toont videos die overeenkomen met de zoekopdracht
// Data komt binnen via VideoController als $videos array en $query string
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Zoekresultaten - StreamHive</title>
</head>
<body>

    <a href="<?= route('video/index') ?>">&larr; Terug</a>

    <h1>Zoekresultaten voor: "<?= htmlspecialchars($query) ?>"</h1>

    <form method="GET" action="<?= route('video/search') ?>" style="margin-bottom: 24px;">
        <input type="text" name="query" value="<?= htmlspecialchars($query) ?>" placeholder="Zoek op titel of beschrijving..." style="width: 300px; padding: 6px;">
        <button type="submit">Zoeken</button>
    </form>

    <?php if (empty($videos)): ?>
        <p>Geen videos gevonden voor "<?= htmlspecialchars($query) ?>".</p>
    <?php else: ?>
        <p><?= count($videos) ?> video('s) gevonden.</p>

        <div style="display: flex; flex-wrap: wrap; gap: 16px;">
            <?php foreach ($videos as $video): ?>
                <a href="<?= route('video/show', ['id' => $video['id']]) ?>"
                   style="text-decoration: none; color: inherit; width: 200px;">

                    <?php if (!empty($video['thumbnail'])): ?>
                        <img src="<?= UPLOADS_URL ?>/thumbnails/<?= htmlspecialchars($video['thumbnail']) ?>"
                             style="width: 200px; height: 120px; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 200px; height: 120px; background: #333; display: flex; align-items: center; justify-content: center; color: white;">
                            Geen thumbnail
                        </div>
                    <?php endif; ?>

                    <p><strong><?= htmlspecialchars($video['title']) ?></strong></p>
                    <p><?= $video['views'] ?> views</p>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</body>
</html>
