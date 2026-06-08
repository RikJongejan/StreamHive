<?php
// AuthService.php - Logica laag voor authenticatie
// Bevat alle regels rondom in- en uitloggen en registreren:
// - Velden controleren (validatie)
// - Wachtwoorden vergelijken (via het model)
// - Nieuwe gebruikers aanmaken
// De controller roept deze service aan en houdt zich alleen met de request bezig.

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
        if (empty($email) || empty($password)) {
            return ['success' => false, 'error' => 'Fill in all the fields.'];
        }

        $user = $this->userModel->login($email, $password);

        if (!$user) {
            return ['success' => false, 'error' => 'Invalid credentials.'];
        }

        return ['success' => true, 'user' => $user];
    }

    public function register(string $email, string $username, string $password, string $confirm): array
    {
        if (empty($email) || empty($username) || empty($password) || empty($confirm)) {
            return ['success' => false, 'error' => 'Fill in all the fields.'];
        }

        if ($password !== $confirm) {
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
