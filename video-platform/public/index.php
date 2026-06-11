<?php
session_start(); // session_start() start de sessie zodat $_SESSION overal beschikbaar is — moet als eerste worden aangeroepen

require_once __DIR__ . '/../app/config/app.php';

// autoloadClass wordt automatisch aangeroepen door PHP zodra een klasse nog niet geladen is
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
        if (file_exists($file)) { // file_exists() controleert of het bestand bestaat voordat het geladen wordt
            require_once $file;
            return;
        }
    }
}

spl_autoload_register('autoloadClass'); // spl_autoload_register() registreert de autoloader — zo hoef je niet overal require_once te schrijven

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
    $route = Auth::isLoggedIn() ? 'video/index' : 'auth/login';
}

$router->dispatch($route);
