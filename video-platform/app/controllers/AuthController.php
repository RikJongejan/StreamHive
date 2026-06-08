<?php
// AuthController.php - Regelt alles rondom authenticatie
// Verantwoordelijk voor:
// - Inlogformulier verwerken
// - Registratieformulier verwerken
// - Uitloggen (sessie vernietigen)
// De controller verwerkt alleen de request en roept AuthService aan voor de logica.

class AuthController
{
    private AuthService $authService;

    public function __construct(PDO $pdo)
    {
        $this->authService = new AuthService($pdo);
    }

    public function login(): void
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $result = $this->authService->login($email, $password);

            if ($result['success']) {
                setUserSession($result['user']);
                redirect(route('video/index'));
            }

            $error = $result['error'];
        }

        require VIEWS_PATH . '/auth/login.php';
    }

    public function register(): void
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

            $result = $this->authService->register($email, $username, $password, $confirm);

            if ($result['success']) {
                setUserSession($result['user']);
                redirect(route('video/index'));
            }

            $error = $result['error'];
        }

        require VIEWS_PATH . '/auth/register.php';
    }

    public function logout(): void
    {
        session_destroy();
        redirect(route('auth/login'));
    }
}
