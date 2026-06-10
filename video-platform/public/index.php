<?php
// index.php - Front controller en enige toegangspunt van de applicatie
// Bootstrapt de applicatie:
// - Start sessie en laadt configuratie, helpers en auth
// - Registreert de autoloader voor core/, models/, services/ en controllers/
// - Legt alle routes vast en koppelt ze aan controllers
// - Bepaalt welke route verwerkt wordt op basis van ?route= in de URL
session_start();

require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

function autoloadClass(string $class): void
{
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
}

spl_autoload_register('autoloadClass');

$pdo    = Database::getConnection();
$router = new Router($pdo);

$router->add('auth/login',    'AuthController',    'login');
$router->add('auth/register', 'AuthController',    'register');
$router->add('auth/logout',   'AuthController',    'logout');
$router->add('video/index',   'VideoController',   'index');
$router->add('video/show',    'VideoController',   'show');
$router->add('video/upload',  'VideoController',   'upload');
$router->add('video/search',  'VideoController',   'search');
$router->add('video/delete',  'VideoController',   'delete');
$router->add('comment/post',  'CommentController', 'post');
$router->add('like/toggle',          'LikeController',         'toggle');
$router->add('subscription/toggle',  'SubscriptionController', 'toggle');
$router->add('user/profile',  'UserController',    'profile');
$router->add('user/settings', 'UserController',    'settings');
$router->add('user/update',   'UserController',    'update');

// Lege route direct oplossen zodat de router nooit een lege string hoeft te verwerken
$route = $_GET['route'] ?? '';

if ($route === '') {
    $route = isLoggedIn() ? 'video/index' : 'auth/login';
}

$router->dispatch($route);
