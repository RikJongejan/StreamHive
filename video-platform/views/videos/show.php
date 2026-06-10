<?php
// show.php - View voor de videopagina
// Toont een volledige videopagina met:
// - Videospeler en metadata (titel, uploader, weergaven, likes)
// - Abonneerknop en verwijderknop voor eigen video's
// - Reactiesectie met plaatsformulier
// - Aanbevolen video's in de zijbalk
$video           = $video           ?? [];
$comments        = $comments        ?? [];
$likeCount       = $likeCount       ?? 0;
$userLiked       = $userLiked       ?? false;
$subscriberCount = $subscriberCount ?? 0;
$userSubscribed  = $userSubscribed  ?? false;
$categories      = $categories      ?? [];
$videoCategories = $videoCategories ?? [];
$recommended     = $recommended     ?? [];
$uploaderName    = $uploaderName    ?? '';
$uploaderInitial = $uploaderInitial ?? '?';
$isOwnVideo      = $isOwnVideo      ?? false;
$myInitial       = $myInitial       ?? '?';
$pageTitle = $video['title'] ?? '';
require VIEWS_PATH . '/partials/header.php';
?>

<main class="page">
    <div class="container watch-wide">

        <a class="back-link" href="<?= route('video/index') ?>">
            <i class="fa-solid fa-arrow-left"></i> Back to home
        </a>

        <div class="watch-layout">

            <!-- ============ LINKS: speler + info ============ -->
            <div class="watch-main">

                <div class="player-wrap">
                    <video controls preload="metadata" poster="<?= !empty($video['thumbnail']) ? UPLOADS_URL . '/thumbnails/' . htmlspecialchars($video['thumbnail']) : '' ?>">
                        <source src="<?= UPLOADS_URL ?>/videos/<?= htmlspecialchars($video['filename']) ?>" type="video/mp4">
                        Your browser does not support video.
                    </video>
                </div>

                <h1><?= htmlspecialchars($video['title']) ?></h1>

                <div class="watch-bar">
                    <div class="uploader-block">
                        <a href="<?= route('user/profile', ['id' => $video['user_id']]) ?>">
                            <span class="avatar-mini"><?= htmlspecialchars($uploaderInitial) ?></span>
                        </a>
                        <div>
                            <a class="u-name" href="<?= route('user/profile', ['id' => $video['user_id']]) ?>">
                                <?= htmlspecialchars($uploaderName) ?>
                            </a>
                            <div class="u-subs"><?= formatCount($subscriberCount) ?> subscribers</div>
                        </div>

                        <?php if (!$isOwnVideo): ?>
                            <form method="POST" action="<?= route('subscription/toggle') ?>" style="margin-left:8px;">
                                <input type="hidden" name="leader_id" value="<?= $video['user_id'] ?>">
                                <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                                <button type="submit" class="sub-btn <?= $userSubscribed ? 'subscribed' : '' ?>">
                                    <i class="fa-solid <?= $userSubscribed ? 'fa-check' : 'fa-bell' ?>"></i>
                                    <?= $userSubscribed ? 'Subscribed' : 'Subscribe' ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div class="watch-actions">
                        <form class="like-form" method="POST" action="<?= route('like/toggle') ?>">
                            <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                            <button type="submit" class="like-btn <?= $userLiked ? 'liked' : '' ?>">
                                <i class="<?= $userLiked ? 'fa-solid' : 'fa-regular' ?> fa-thumbs-up"></i>
                                <?= formatCount($likeCount) ?>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="video-desc">
                    <div class="desc-meta">
                        <span><i class="fa-solid fa-eye"></i> <?= formatCount((int) $video['views']) ?> views</span>
                        <span><i class="fa-solid fa-clock"></i> <?= timeAgo($video['created_at']) ?></span>
                        <span><i class="fa-solid fa-thumbs-up"></i> <?= formatCount($likeCount) ?> likes</span>
                    </div>

                    <?php if (!empty($video['description'])): ?>
                        <p class="desc-body"><?= nl2br(htmlspecialchars($video['description'])) ?></p>
                    <?php else: ?>
                        <p class="desc-body"><em>No description.</em></p>
                    <?php endif; ?>

                    <?php if (!empty($videoCategories)): ?>
                        <div class="cat-tags">
                            <?php foreach ($categories as $category): ?>
                                <?php if (in_array($category['id'], $videoCategories)): ?>
                                    <span class="cat-tag"><?= htmlspecialchars($category['name']) ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <section class="comments">
                    <h2><i class="fa-solid fa-comments"></i> <?= count($comments) ?> comment<?= count($comments) === 1 ? '' : 's' ?></h2>

                    <form class="comment-form" method="POST" action="<?= route('comment/post') ?>">
                        <span class="avatar-mini"><?= htmlspecialchars($myInitial) ?></span>
                        <div class="cf-body">
                            <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                            <textarea class="input" name="content" rows="2" placeholder="Add a comment..." required></textarea>
                            <div class="cf-actions">
                                <button type="submit" class="btn btn-honey">
                                    <i class="fa-solid fa-paper-plane"></i> Post
                                </button>
                            </div>
                        </div>
                    </form>

                    <?php if (empty($comments)): ?>
                        <p style="color: var(--text-mute);">No comments yet. Be the first!</p>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <?php $commentInitial = strtoupper(substr($comment['username'] ?? '?', 0, 1)); ?>
                            <div class="comment">
                                <span class="avatar-mini"><?= htmlspecialchars($commentInitial) ?></span>
                                <div>
                                    <div class="c-head">
                                        <span class="c-name"><?= htmlspecialchars($comment['username']) ?></span>
                                        <span class="c-time"><?= timeAgo($comment['created_at']) ?></span>
                                    </div>
                                    <div class="c-text"><?= nl2br(htmlspecialchars($comment['content'])) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>

            </div>

            <!-- ============ RIGHT: recommended ============ -->
            <aside class="watch-sidebar">
                <h2><i class="fa-solid fa-layer-group"></i> Recommended</h2>

                <?php if (empty($recommended)): ?>
                    <p style="color: var(--text-mute);">No other videos yet.</p>
                <?php else: ?>
                    <?php foreach ($recommended as $rec): ?>
                        <a class="rec-card" href="<?= route('video/show', ['id' => $rec['id']]) ?>">
                            <div class="rec-thumb">
                                <?php if (!empty($rec['thumbnail'])): ?>
                                    <img src="<?= UPLOADS_URL ?>/thumbnails/<?= htmlspecialchars($rec['thumbnail']) ?>"
                                         alt="<?= htmlspecialchars($rec['title']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="no-thumb"><i class="fa-solid fa-film"></i></div>
                                <?php endif; ?>
                                <span class="views-badge"><i class="fa-solid fa-eye"></i> <?= formatCount((int) $rec['views']) ?></span>
                            </div>
                            <div class="rec-info">
                                <h4><?= htmlspecialchars($rec['title']) ?></h4>
                                <span><?= htmlspecialchars($rec['uploader'] ?? '') ?></span>
                                <span><?= formatCount((int) $rec['views']) ?> views &middot; <?= timeAgo($rec['created_at']) ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </aside>

        </div>
    </div>
</main>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
