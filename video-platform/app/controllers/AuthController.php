<?php
class AuthController
{
    private AuthService $authService;

    // __construct() wordt automatisch aangeroepen zodra je 'new AuthController($pdo)' schrijft
    public function __construct(PDO $pdo)
    {
        $this->authService = new AuthService($pdo);
    }

    public function login(): void
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // $_SERVER['REQUEST_METHOD'] controleert of het formulier verzonden is via POST
            if (isset($_POST['email'])) { // isset() controleert of het veld bestaat en niet null is
                $email = trim($_POST['email']); // trim() verwijdert spaties aan begin en einde van de invoer
            } else {
                $email = '';
            }

            if (isset($_POST['password'])) {
                $password = $_POST['password'];
            } else {
                $password = '';
            }

            $result = $this->authService->login($email, $password);

            if ($result['success']) {
                Helpers::setUserSession($result['user']);
                Helpers::redirect(Helpers::route('video/index'));
            }

            $error = $result['error'];
        }

        require VIEWS_PATH . '/auth/login.php';
    }

    public function register(): void
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['email'])) {
                $email = trim($_POST['email']);
            } else {
                $email = '';
            }

            if (isset($_POST['username'])) {
                $username = trim($_POST['username']);
            } else {
                $username = '';
            }

            if (isset($_POST['password'])) {
                $password = $_POST['password'];
            } else {
                $password = '';
            }

            if (isset($_POST['confirm_password'])) {
                $confirmPassword = $_POST['confirm_password'];
            } else {
                $confirmPassword = '';
            }

            $result = $this->authService->register($email, $username, $password, $confirmPassword);

            if ($result['success']) {
                Helpers::setUserSession($result['user']);
                Helpers::redirect(Helpers::route('video/index'));
            }

            $error = $result['error'];
        }

        require VIEWS_PATH . '/auth/register.php';
    }

    public function logout(): void
    {
        session_destroy(); // session_destroy() vernietigt de sessie volledig — alle $_SESSION gegevens worden gewist (uitloggen)
        Helpers::redirect(Helpers::route('auth/login'));
    }
}
