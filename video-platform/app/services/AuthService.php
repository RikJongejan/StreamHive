<?php
// AuthService.php - Service voor authenticatielogica
// Bevat de bedrijfslogica voor inloggen en registreren:
// - Validatie van invoer (lege velden, wachtwoordlengte, overeenkomst)
// - Inloggen via het UserModel
// - Registreren en direct inloggen na aanmaken account
class AuthService
{
    private UserModel $userModel;

    public function __construct(PDO $pdo)
    {
        $this->userModel = new UserModel($pdo);
    }

    // Geeft een array terug met 'success' en bij fout 'error', bij succes 'user'
    public function login(string $email, string $password): array
    {
        if (empty($email)) {
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

        if (strlen($password) < 8) {
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
