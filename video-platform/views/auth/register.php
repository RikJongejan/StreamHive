<?php
// register.php - Registratiepagina
// Toont het registratieformulier voor nieuwe gebruikers in een gecentreerde kaart.
// Velden: e-mail, gebruikersnaam, wachtwoord, wachtwoord bevestigen.
// Bij submit verwerkt AuthController de gegevens; bij fout staat $error gevuld.
$pageTitle = 'Registreren';
$hideNav   = true;
require VIEWS_PATH . '/partials/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">

        <div class="auth-logo">
            <img src="<?= ASSETS_URL ?>/images/logo.png" alt="StreamHive"
                 onerror="this.onerror=null;this.src='<?= ASSETS_URL ?>/images/logo.svg'">
        </div>

        <h1>Word lid van de Hive</h1>
        <p class="sub">Maak een account en begin met delen</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= route('auth/register') ?>">
            <div class="form-group">
                <label>E-mailadres</label>
                <input class="input" type="email" name="email" placeholder="jij@email.com" required autofocus>
            </div>

            <div class="form-group">
                <label>Gebruikersnaam</label>
                <input class="input" type="text" name="username" placeholder="jouwnaam" required>
            </div>

            <div class="form-group">
                <label>Wachtwoord</label>
                <input class="input" type="password" name="password" placeholder="Minimaal 8 tekens" required>
            </div>

            <div class="form-group">
                <label>Wachtwoord bevestigen</label>
                <input class="input" type="password" name="confirm_password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
            </div>

            <button class="btn btn-honey btn-block btn-lg" type="submit">
                <i class="fa-solid fa-user-plus"></i> Account aanmaken
            </button>
        </form>

        <div class="link">
            Al een account? <a href="<?= route('auth/login') ?>">Inloggen</a>
        </div>

    </div>
</div>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
