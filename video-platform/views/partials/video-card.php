<?php
// video-card.php - Herbruikbare videokaart partial
// Toont een enkele videokaart in een grid:
// - Thumbnail of standaardafbeelding
// - Titel, uploader, weergaven en tijdstip
// - Verwijderknop als $cardOwner op true staat
$cardOwner       = $cardOwner ?? false;
$uploaderName    = $card['uploader'] ?? '';
$uploaderInitial = $uploaderName !== '' ? strtoupper(substr($uploaderName, 0, 1)) : '?';
?>
<div class="video-card">
    <div class="thumb">
        <a class="thumb-link" href="<?= route('video/show', ['id' => $card['id']]) ?>">
            <?php if (!empty($card['thumbnail'])): ?>
                <img src="<?= UPLOADS_URL ?>/thumbnails/<?= htmlspecialchars($card['thumbnail']) ?>"
                     alt="<?= htmlspecialchars($card['title']) ?>" loading="lazy">
            <?php else: ?>
                <div class="no-thumb"><i class="fa-solid fa-film"></i></div>
            <?php endif; ?>
            <div class="play-overlay"><i class="fa-solid fa-circle-play"></i></div>
        </a>

        <span class="views-badge"><i class="fa-solid fa-eye"></i> <?= formatCount((int) $card['views']) ?></span>

        <?php if ($cardOwner): ?>
            <div class="owner-actions">
                <form method="POST" action="<?= route('video/delete') ?>"
                      data-confirm="Are you sure you want to delete this video?">
                    <input type="hidden" name="video_id" value="<?= $card['id'] ?>">
                    <button type="submit" class="icon-btn danger" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <div class="video-meta">
        <a href="<?= route('video/show', ['id' => $card['id']]) ?>">
            <h3><?= htmlspecialchars($card['title']) ?></h3>
        </a>
        <a class="uploader" href="<?= route('user/profile', ['id' => $card['user_id']]) ?>">
            <span class="avatar-mini"><?= htmlspecialchars($uploaderInitial) ?></span>
            <span><?= htmlspecialchars($uploaderName) ?></span>
            <?php if (!empty($card['created_at'])): ?>
                <span class="dot"></span>
                <span><?= timeAgo($card['created_at']) ?></span>
            <?php endif; ?>
        </a>
    </div>
</div>
