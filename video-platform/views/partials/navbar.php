<?php
// navbar.php - Navigatiebalk
// Toont de navigatiebalk bovenaan elke pagina:
// - Logo, zoekbalk en navigatielinks
// - Avatar met initiaal voor ingelogde gebruiker
// - Login- en registratielinks voor uitgelogde bezoekers
$navUsername        = $_SESSION['username'] ?? '';
$navUsernameInitial = $navUsername !== '' ? strtoupper(substr($navUsername, 0, 1)) : '?';
?>
<nav class="nav">
    <div class="container">

        <a class="brand" href="<?= Helpers::route('video/index') ?>">
            <img src="<?= ASSETS_URL ?>/images/logo.png" alt="StreamHive logo"
                 onerror="this.onerror=null;this.src='<?= ASSETS_URL ?>/images/logo.svg'">
            <span><span class="b-stream">Stream</span><span class="b-hive">Hive</span></span>
        </a>

        <form class="nav-search" method="GET" action="<?= BASE_URL ?>" role="search">
            <input type="hidden" name="route" value="video/search">
            <input type="text" name="query" placeholder="Search videos..." aria-label="Search">
            <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <button class="nav-toggle" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>

        <div class="nav-links">
            <?php if (Auth::isLoggedIn()): ?>
                <a class="nav-link" href="<?= Helpers::route('video/index') ?>"><i class="fa-solid fa-house"></i> Home</a>
                <a class="nav-link" href="<?= Helpers::route('video/upload') ?>"><i class="fa-solid fa-cloud-arrow-up"></i> Upload</a>
                <a class="nav-link" href="<?= Helpers::route('auth/logout') ?>"><i class="fa-solid fa-right-from-bracket"></i> Log out</a>
                <a class="nav-avatar" href="<?= Helpers::route('user/profile') ?>" title="My channel (<?= htmlspecialchars($navUsername) ?>)"><?= htmlspecialchars($navUsernameInitial) ?></a>
            <?php else: ?>
                <a class="nav-link" href="<?= Helpers::route('auth/login') ?>">Log in</a>
                <a class="btn btn-honey" href="<?= Helpers::route('auth/register') ?>">Sign up</a>
            <?php endif; ?>
        </div>

    </div>
</nav>
