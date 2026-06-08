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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .like-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: 2px solid #ccc;
            border-radius: 20px;
            background: white;
            cursor: pointer;
            font-size: 15px;
            color: #555;
        }
        .like-btn.liked {
            border-color: #1877f2;
            color: #1877f2;
        }
        .like-btn i {
            font-size: 17px;
        }
    </style>
</head>
<body>

    <a href="<?= route('video/index') ?>">
        &larr; Terug
    </a>

    <h1><?= htmlspecialchars($video['title']) ?></h1>

    <video width="800" controls>
        <source src="<?= UPLOADS_URL ?>/videos/<?= htmlspecialchars($video['filename']) ?>"
                type="video/mp4">
        Je browser ondersteunt geen video.
    </video>

    <p><?= $video['views'] ?> views</p>
    <p><?= htmlspecialchars($video['description']) ?></p>

    <form method="POST" action="<?= route('like/toggle') ?>">
        <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
        <button type="submit" class="like-btn <?= $userLiked ? 'liked' : '' ?>">
            <i class="<?= $userLiked ? 'fa-solid' : 'fa-regular' ?> fa-thumbs-up"></i>
            <?= $likeCount ?> <?= $likeCount === 1 ? 'like' : 'likes' ?>
        </button>
    </form>

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
        <form method="POST" action="<?= route('comment/post') ?>">
            <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
            <textarea name="content" rows="3" placeholder="Schrijf een reactie..." required></textarea><br>
            <button type="submit">Plaatsen</button>
        </form>

</body>
</html>
