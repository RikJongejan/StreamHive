<?php
// header.php - Bovenste gedeelte van elke pagina
// Wordt bovenaan elke view geladen met require.
// Bevat DOCTYPE, head (CSS + Font Awesome), opent de body en tekent de
// vliegende bijen op de achtergrond. Laadt de navbar in, tenzij $hideNav waar is.
// De view kan vooraf $pageTitle en $hideNav zetten.
$pageTitle = $pageTitle ?? SITE_NAME;
$hideNav   = $hideNav ?? false;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> &middot; StreamHive</title>
    <link rel="icon" type="image/png" href="<?= ASSETS_URL ?>/images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css">
</head>
<body>

    <!-- bijen die zachtjes over het scherm vliegen -->
    <div class="bee-field" aria-hidden="true">
        <?php $beeWrap = 'bee-float'; for ($i = 0; $i < 3; $i++) { require VIEWS_PATH . '/partials/bee.php'; } ?>
    </div>

    <?php if (!$hideNav) { require VIEWS_PATH . '/partials/navbar.php'; } ?>
