<?php
// index.php (views/videos) - Overzichtspagina met alle videos
// Toont een grid van alle beschikbare videos
// Elke video toont: thumbnail, titel, uploader, aantal views
// Data komt binnen via VideoController als $videos array
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>StreamHive</title>
</head>
<body>

    <h1>Videos</h1>
    <a href="<?= route('video/upload') ?>">Video uploaden</a>
    &nbsp;|&nbsp;
    <a href="<?= route('auth/logout') ?>">Uitloggen</a>

    <form method="GET" action="<?= BASE_URL ?>" style="margin-top: 16px;">
        <input type="hidden" name="route" value="video/search">
        <input type="text" name="query" placeholder="Zoek op titel of beschrijving..." style="width: 300px; padding: 6px;">
        <button type="submit">Zoeken</button>
    </form>

    <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-top: 24px;">

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

</body>
</html>
