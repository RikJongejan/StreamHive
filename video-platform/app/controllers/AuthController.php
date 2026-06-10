<?php
// AuthController.php - Controller voor authenticatie
// Beheert inloggen, registreren en uitloggen:
// - Loginformulier weergeven en verwerken (login)
// - Registratieformulier weergeven en verwerken (register)
// - Sessie beëindigen en doorverwijzen (logout)
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
            $email           = trim($_POST['email'] ?? '');
            $username        = trim($_POST['username'] ?? '');
            $password        = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $result = $this->authService->register($email, $username, $password, $confirmPassword);

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
