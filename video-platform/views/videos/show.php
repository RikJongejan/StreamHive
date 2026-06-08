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
        .sub-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            background: #222;
            color: white;
            cursor: pointer;
            font-size: 15px;
        }
        .sub-btn.subscribed {
            background: #e0e0e0;
            color: #222;
        }
        .category-tag {
            display: inline-block;
            padding: 3px 10px;
            background: #f0f0f0;
            border-radius: 12px;
            font-size: 13px;
            color: #444;
            margin-right: 6px;
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

    <?php if (!empty($videoCategories)): ?>
        <div style="margin-bottom: 8px;">
            <?php foreach ($categories as $category): ?>
                <?php if (in_array($category['id'], $videoCategories)): ?>
                    <span class="category-tag"><?= htmlspecialchars($category['name']) ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; align-items: center; gap: 12px; margin: 12px 0;">

        <form method="POST" action="<?= route('like/toggle') ?>">
            <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
            <button type="submit" class="like-btn <?= $userLiked ? 'liked' : '' ?>">
                <i class="<?= $userLiked ? 'fa-solid' : 'fa-regular' ?> fa-thumbs-up"></i>
                <?= $likeCount ?> <?= $likeCount === 1 ? 'like' : 'likes' ?>
            </button>
        </form>

        <?php if ((int) $video['user_id'] !== (int) $_SESSION['user_id']): ?>
            <form method="POST" action="<?= route('subscription/toggle') ?>">
                <input type="hidden" name="leader_id" value="<?= $video['user_id'] ?>">
                <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                <button type="submit" class="sub-btn <?= $userSubscribed ? 'subscribed' : '' ?>">
                    <?= $userSubscribed ? 'Geabonneerd' : 'Abonneren' ?>
                    <span style="font-size:12px;">(<?= $subscriberCount ?>)</span>
                </button>
            </form>
        <?php endif; ?>

    </div>

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
