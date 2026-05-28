<?php
// index.php (public) - Het startpunt van de hele applicatie
// Dit is het ENIGE bestand dat de browser rechtstreeks aanroept
// Alle verkeer loopt via dit bestand (front controller patroon)
// Het verschil met views/videos/index.php:
//   - public/index.php = de router, beslist WELKE pagina getoond wordt
//   - views/videos/index.php = de pagina zelf met de HTML


session_start();

//ingelogd? redirect naar video pagina
if (isset($_SESSION['user_id'])) {
    header('Location: /GitHub/StreamHive/video-platform/app/controllers/VideoController.php?action=index');
} else {
    header('Location: /GitHub/StreamHive/video-platform/app/controllers/AuthController.php?action=login');
}
exit;