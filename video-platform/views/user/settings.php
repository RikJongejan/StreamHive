<?php
// settings.php - Instellingenpagina van de ingelogde gebruiker
// Hier past de gebruiker zijn naam, bio en avatar aan.
// Alleen toegankelijk voor de eigenaar; submit gaat naar UserController::update().
// Data komt binnen als $user (en bij een fout $error).
$avatarInitial = strtoupper(substr($user['username'], 0, 1));
require VIEWS_PATH . '/partials/header.php';
?>

<main class="page">
    <div class="container">

        <a class="back-link" href="<?= route('user/profile') ?>">
            <i class="fa-solid fa-arrow-left"></i> Terug naar mijn kanaal
        </a>

        <div class="settings-card">
            <h1><i class="fa-solid fa-gear"></i> Instellingen</h1>
            <p class="muted">Pas hoe je kanaal eruitziet aan.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= route('user/update') ?>" enctype="multipart/form-data">

                <div class="form-group" style="display:flex;align-items:center;gap:16px;">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img class="profile-avatar" style="width:72px;height:72px;font-size:1.6rem;"
                             src="<?= UPLOADS_URL ?>/avatars/<?= htmlspecialchars($user['profile_image']) ?>" alt="">
                    <?php else: ?>
                        <div class="profile-avatar" style="width:72px;height:72px;font-size:1.6rem;"><?= htmlspecialchars($avatarInitial) ?></div>
                    <?php endif; ?>
                    <div class="filefield" style="flex:1;">
                        <input type="file" name="avatar" accept="image/*">
                        <div class="filebox">
                            <i class="fa-solid fa-image"></i>
                            <span class="file-name" data-placeholder="Nieuwe avatar kiezen">Nieuwe avatar kiezen (JPG, PNG, WebP)</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Gebruikersnaam</label>
                    <input class="input" type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label>E-mailadres</label>
                    <input class="input" type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Bio</label>
                    <textarea class="input" name="bio" rows="4" placeholder="Vertel iets over je kanaal..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>

                <button class="btn btn-honey btn-lg" type="submit">
                    <i class="fa-solid fa-floppy-disk"></i> Opslaan
                </button>
            </form>
        </div>

    </div>
</main>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
