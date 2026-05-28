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

</body>
</html>
