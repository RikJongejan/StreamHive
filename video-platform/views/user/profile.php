<?php
// profile.php - Profielpagina (kanaal) van een gebruiker
// Toont: avatar, naam, bio, statistieken en tabbladen met video's en abonnees.
// Op het eigen kanaal verschijnt ook een tab "Abonnementen" en verwijderknoppen.
// Data komt binnen via UserController:
//   $profileUser, $isOwn, $videos, $subscriberCount, $subscribers,
//   $userSubscribed, $subscriptions
$avatarInitial = strtoupper(substr($profileUser['username'], 0, 1));
require VIEWS_PATH . '/partials/header.php';
?>

<main class="page">
    <div class="container">

        <div class="profile-banner">
            <?php if (!empty($profileUser['profile_image'])): ?>
                <img class="profile-avatar" src="<?= UPLOADS_URL ?>/avatars/<?= htmlspecialchars($profileUser['profile_image']) ?>" alt="">
            <?php else: ?>
                <div class="profile-avatar"><?= htmlspecialchars($avatarInitial) ?></div>
            <?php endif; ?>

            <div class="profile-info">
                <h1>
                    <?= htmlspecialchars($profileUser['username']) ?>
                    <?php if ($isOwn): ?><i class="fa-solid fa-crown" style="color:var(--honey);font-size:1.1rem;" title="Dit ben jij"></i><?php endif; ?>
                </h1>

                <?php if (!empty($profileUser['bio'])): ?>
                    <p class="bio"><?= nl2br(htmlspecialchars($profileUser['bio'])) ?></p>
                <?php elseif ($isOwn): ?>
                    <p class="bio"><em>Nog geen bio. Voeg er een toe via Profiel bewerken.</em></p>
                <?php endif; ?>

                <div class="profile-stats">
                    <div class="stat"><b><?= count($videos) ?></b><span>Video's</span></div>
                    <div class="stat"><b><?= formatCount($subscriberCount) ?></b><span>Abonnees</span></div>
                    <?php if ($isOwn): ?>
                        <div class="stat"><b><?= count($subscriptions) ?></b><span>Abonnementen</span></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="profile-actions">
                <?php if ($isOwn): ?>
                    <a class="btn btn-ghost" href="<?= route('user/settings') ?>"><i class="fa-solid fa-pen"></i> Profiel bewerken</a>
                    <a class="btn btn-honey" href="<?= route('video/upload') ?>"><i class="fa-solid fa-cloud-arrow-up"></i> Upload</a>
                <?php else: ?>
                    <form method="POST" action="<?= route('subscription/toggle') ?>">
                        <input type="hidden" name="leader_id" value="<?= $profileUser['id'] ?>">
                        <button type="submit" class="sub-btn <?= $userSubscribed ? 'subscribed' : '' ?>">
                            <i class="fa-solid <?= $userSubscribed ? 'fa-check' : 'fa-bell' ?>"></i>
                            <?= $userSubscribed ? 'Geabonneerd' : 'Abonneren' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="tabs">
            <button class="tab active" data-tab="tab-videos"><i class="fa-solid fa-clapperboard"></i> Video's</button>
            <button class="tab" data-tab="tab-subs"><i class="fa-solid fa-users"></i> Abonnees</button>
            <?php if ($isOwn): ?>
                <button class="tab" data-tab="tab-following"><i class="fa-solid fa-heart"></i> Abonnementen</button>
            <?php endif; ?>
        </div>

        <!-- Tab: video's -->
        <div id="tab-videos" class="tab-panel active">
            <?php if (empty($videos)): ?>
                <div class="empty-state">
                    <?php $beeWrap = null; require VIEWS_PATH . '/partials/bee.php'; ?>
                    <h3><?= $isOwn ? 'Jij hebt' : htmlspecialchars($profileUser['username']) . ' heeft' ?> nog geen video's</h3>
                    <?php if ($isOwn): ?>
                        <p>Upload je eerste video en vul je kanaal.</p>
                        <a class="btn btn-honey" href="<?= route('video/upload') ?>">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Upload nu
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="video-grid">
                    <?php $cardOwner = $isOwn; foreach ($videos as $card) { require VIEWS_PATH . '/partials/video-card.php'; } ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tab: abonnees -->
        <div id="tab-subs" class="tab-panel">
            <?php if (empty($subscribers)): ?>
                <p style="color:var(--text-mute);padding:20px 0;">Nog geen abonnees.</p>
            <?php else: ?>
                <div class="sub-list">
                    <?php foreach ($subscribers as $sub): ?>
                        <a class="sub-chip" href="<?= route('user/profile', ['id' => $sub['id']]) ?>">
                            <span class="avatar-mini"><?= htmlspecialchars(strtoupper(substr($sub['username'], 0, 1))) ?></span>
                            <div>
                                <b><?= htmlspecialchars($sub['username']) ?></b><br>
                                <span>Bekijk kanaal</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tab: abonnementen (alleen eigen kanaal) -->
        <?php if ($isOwn): ?>
            <div id="tab-following" class="tab-panel">
                <?php if (empty($subscriptions)): ?>
                    <p style="color:var(--text-mute);padding:20px 0;">Je bent nog nergens op geabonneerd.</p>
                <?php else: ?>
                    <div class="sub-list">
                        <?php foreach ($subscriptions as $sub): ?>
                            <a class="sub-chip" href="<?= route('user/profile', ['id' => $sub['id']]) ?>">
                                <span class="avatar-mini"><?= htmlspecialchars(strtoupper(substr($sub['username'], 0, 1))) ?></span>
                                <div>
                                    <b><?= htmlspecialchars($sub['username']) ?></b><br>
                                    <span>Bekijk kanaal</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
