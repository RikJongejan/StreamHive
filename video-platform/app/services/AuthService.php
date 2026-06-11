<?php
class AuthService
{
    private UserModel $userModel;

    // __construct() wordt automatisch aangeroepen zodra je 'new AuthService($pdo)' schrijft
    public function __construct(PDO $pdo)
    {
        $this->userModel = new UserModel($pdo);
    }

    public function login(string $email, string $password): array
    {
        if (empty($email)) { // empty() controleert of een waarde leeg is (lege string, null, 0, false)
            return ['success' => false, 'error' => 'Fill in all the fields.'];
        }

        if (empty($password)) {
            return ['success' => false, 'error' => 'Fill in all the fields.'];
        }

        $user = $this->userModel->login($email, $password);

        if (!$user) {
            return ['success' => false, 'error' => 'Invalid credentials.'];
        }

        return ['success' => true, 'user' => $user];
    }

    public function register(string $email, string $username, string $password, string $confirmPassword): array
    {
        if (empty($email)) {
            return ['success' => false, 'error' => 'Fill in all the fields.'];
        }

        if (empty($username)) {
            return ['success' => false, 'error' => 'Fill in all the fields.'];
        }

        if (empty($password)) {
            return ['success' => false, 'error' => 'Fill in all the fields.'];
        }

        if (empty($confirmPassword)) {
            return ['success' => false, 'error' => 'Fill in all the fields.'];
        }

        if ($password !== $confirmPassword) {
            return ['success' => false, 'error' => 'Passwords do not match.'];
        }

        if (strlen($password) < 8) { // strlen() telt het aantal tekens in een string
            return ['success' => false, 'error' => 'Password needs at least 8 characters.'];
        }

        $created = $this->userModel->register($email, $username, $password);

        if (!$created) {
            return ['success' => false, 'error' => 'Email or username already in use.'];
        }

        // Direct inloggen zodat de gebruiker na registratie meteen een sessie heeft
        $user = $this->userModel->login($email, $password);

        return ['success' => true, 'user' => $user];
    }
}
