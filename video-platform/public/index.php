<?php
// index.php (public) - Het startpunt van de hele applicatie (front controller)
// Dit is het ENIGE bestand dat de browser rechtstreeks aanroept.
// Alle verkeer loopt via ?route=... en wordt door de Router naar de juiste controller gestuurd.
// Stappen:
//   1. Sessie starten en config/helpers laden
//   2. Autoloader registreren zodat classes vanzelf ingeladen worden
//   3. Databaseverbinding maken
//   4. Routes registreren
//   5. De gevraagde route afhandelen

session_start();

require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Laadt classes automatisch in op basis van hun naam (bestandsnaam == classnaam)
spl_autoload_register(function (string $class): void {
    $folders = [
        __DIR__ . '/../core/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/services/',
        __DIR__ . '/../app/controllers/',
    ];

    foreach ($folders as $folder) {
        $file = $folder . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$pdo    = Database::getConnection();
$router = new Router($pdo);

// Routes: route-naam => controller + methode
$router->add('auth/login',    'AuthController',    'login');
$router->add('auth/register', 'AuthController',    'register');
$router->add('auth/logout',   'AuthController',    'logout');
$router->add('video/index',   'VideoController',   'index');
$router->add('video/show',    'VideoController',   'show');
$router->add('video/upload',  'VideoController',   'upload');
$router->add('video/search',  'VideoController',   'search');
$router->add('comment/post',  'CommentController', 'post');
$router->add('like/toggle',          'LikeController',         'toggle');
$router->add('subscription/toggle',  'SubscriptionController', 'toggle');

// Geen route opgegeven? Stuur naar het overzicht als je ingelogd bent, anders naar login.
$route = $_GET['route'] ?? '';

if ($route === '') {
    $route = isLoggedIn() ? 'video/index' : 'auth/login';
}

$router->dispatch($route);
