<?php
// Router.php - Koppelt een route (bijv. "video/show") aan een controller en methode
// Werkwijze:
// - In public/index.php worden alle routes geregistreerd met add()
// - dispatch() zoekt de juiste controller op, maakt hem aan en roept de methode aan
// - Bestaat de route niet, dan volgt een 404
// De controller krijgt de PDO-verbinding mee in zijn constructor.

class Router
{
    private PDO $pdo;
    private array $routes = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(string $route, string $controller, string $method): void
    {
        $this->routes[$route] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch(string $route): void
    {
        if (!isset($this->routes[$route])) {
            http_response_code(404);
            echo 'Page not found.';
            return;
        }

        $controllerName = $this->routes[$route]['controller'];
        $methodName     = $this->routes[$route]['method'];

        $controller = new $controllerName($this->pdo);
        $controller->$methodName();
    }
}
