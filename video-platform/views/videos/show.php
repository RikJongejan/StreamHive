<?php
// show.php - Pagina voor het bekijken van een video
// Toont: de video zelf, titel, beschrijving, uploader
// Ook: aantal likes, like-knop, reacties, abonneerknop
// Data komt binnen via VideoController als $video array
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($video['title']) ?> - StreamHive</title>
</head>
<body>

    <a href="/GitHub/StreamHive/video-platform/app/controllers/VideoController.php?action=index">
        &larr; Terug
    </a>

    <h1><?= htmlspecialchars($video['title']) ?></h1>

    <video width="800" controls>
        <source src="/GitHub/StreamHive/video-platform/uploads/videos/<?= htmlspecialchars($video['filename']) ?>"
                type="video/mp4">
        Je browser ondersteunt geen video.
    </video>

    <p><?= $video['views'] ?> views</p>
    <p><?= htmlspecialchars($video['description']) ?></p>

    <hr>

    <h2>Reacties</h2>

    <?php foreach ($comments as $comment): ?>
        <p>
            <strong><?= htmlspecialchars($comment['username']) ?></strong>
            &mdash;
            <?= htmlspecialchars($comment['content']) ?>
            <small><?= $comment['created_at'] ?></small>
        </p>
        <?php endforeach; ?>

        <?php if (empty($comments)): ?>
            <p>Nog geen reacties.</p>
        <?php endif; ?>

        <h3>Reactie plaatsen</h3>
        <form method="POST" action="/GitHub/StreamHive/video-platform/app/controllers/CommentController.php?action=post">
            <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
            <textarea name="content" rows="3" placeholder="Schrijf een reactie..." required></textarea><br>
            <button type="submit">Plaatsen</button>
        </form>

</body>
</html>
