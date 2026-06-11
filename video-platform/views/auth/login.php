<?php
// login.php - View voor de loginpagina
// Toont het loginformulier met e-mail en wachtwoordveld
// Geeft een foutmelding als de inlogpoging mislukt
$error     = $error ?? '';
$pageTitle = 'Log In';
$hideNav   = true;
require VIEWS_PATH . '/partials/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">

        <div class="auth-logo">
            <img src="<?= ASSETS_URL ?>/images/logo.png" alt="StreamHive"
                 onerror="this.onerror=null;this.src='<?= ASSETS_URL ?>/images/logo.svg'">
        </div>

        <h1>Welcome back</h1>
        <p class="sub">Sign in and dive back into the hive</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= Helpers::route('auth/login') ?>">
            <div class="form-group">
                <label>Email address</label>
                <input class="input" type="email" name="email" placeholder="you@email.com" required autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input class="input" type="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
            </div>

            <button class="btn btn-honey btn-block btn-lg" type="submit">
                <i class="fa-solid fa-right-to-bracket"></i> Log in
            </button>
        </form>

        <div class="link">
            Don't have an account? <a href="<?= Helpers::route('auth/register') ?>">Join the Hive</a>
        </div>

    </div>
</div>

<?php require VIEWS_PATH . '/partials/footer.php'; ?>
