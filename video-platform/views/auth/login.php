<?php
// login.php - Inlogpagina
// Toont het inlogformulier (e-mail + wachtwoord) in een gecentreerde kaart.
// $hideNav = true verbergt de navbar/footer voor een schone auth-look.
// Bij submit verwerkt AuthController de gegevens; bij fout staat $error gevuld.
$pageTitle = 'Inloggen';
$hideNav   = true;
require VIEWS_PATH . '/partials/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">

        <div class="auth-logo">
            <img src="<?= ASSETS_URL ?>/images/logo.png" alt="StreamHive"
                 onerror="this.onerror=null;this.src='<?= ASSETS_URL ?>/images/logo.svg'">
        </div>

        <h1>Welkom terug</h1>
        <p class="sub">Log in en duik weer in de korf</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= route('auth/login') ?>">
            <div class="form-group">
                <label>E-mailadres</label>
                <input class="input" type="email" name="email" placeholder="jij@email.com" required autofocus>
            </div>

            <div class="form-group">
                <label>Wachtwoord</label>
                <input class="input" type="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
            </div>

            <button class="btn btn-honey btn-block btn-lg" type="submit">
                <i class="fa-solid fa-right-to-bracket"></i> Inloggen
            </button>
        </form>

        <div class="link">
            Nog geen account? <a href="<?= route('auth/register') ?>">Word lid van de Hive</a>
        </div>

    </div>
</div>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
