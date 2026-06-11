<?php
// register.php - View voor de registratiepagina
// Toont het registratieformulier met e-mail, gebruikersnaam en wachtwoordvelden
// Geeft een foutmelding als de registratie mislukt
$error     = $error ?? '';
$pageTitle = 'Sign Up';
$hideNav   = true;
require VIEWS_PATH . '/partials/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">

        <div class="auth-logo">
            <img src="<?= ASSETS_URL ?>/images/logo.png" alt="StreamHive"
                 onerror="this.onerror=null;this.src='<?= ASSETS_URL ?>/images/logo.svg'">
        </div>

        <h1>Join the Hive</h1>
        <p class="sub">Create an account and start sharing</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= Helpers::route('auth/register') ?>">
            <div class="form-group">
                <label>Email address</label>
                <input class="input" type="email" name="email" placeholder="you@email.com" required autofocus>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input class="input" type="text" name="username" placeholder="yourname" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input class="input" type="password" name="password" placeholder="At least 8 characters" required>
            </div>

            <div class="form-group">
                <label>Confirm password</label>
                <input class="input" type="password" name="confirm_password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
            </div>

            <button class="btn btn-honey btn-block btn-lg" type="submit">
                <i class="fa-solid fa-user-plus"></i> Create account
            </button>
        </form>

        <div class="link">
            Already have an account? <a href="<?= Helpers::route('auth/login') ?>">Log in</a>
        </div>

    </div>
</div>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
