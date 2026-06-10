<?php
// Router.php - Front controller router
// Koppelt route-strings aan controller-methoden:
// - Routes registreren via add()
// - Route verwerken en bijbehorende controller instantiëren via dispatch()
// - 404 terugsturen als de route niet bekend is
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
