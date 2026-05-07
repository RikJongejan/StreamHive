<?php
// User.php - Model voor de gebruiker
// Bevat alle logica rondom gebruikers:
// - Registreren, inloggen, uitloggen
// - Profiel ophalen en updaten
// - Account verwijderen
// Werkt direct met de 'users' tabel in de database
class User {
    private PDO $pdo;

    //$pdo wordt gegeven vanuit de controller
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    //alle gebruikers ophalen uit de database
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //1 gebruiker ophalen op ID
    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //gebruiker ophalen op email voor het inloggn
    public function getByEmail(string $email): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //nieuwe gebruiker aanmaken
    public function register(string $email, string $password): bool {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        return $stmt->execute([$email, $hashed]);
    }
    //controleren inloggen
    public function login(string $email, string $password): array|false {
        $user = $this->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    //profiel updaten
    public function updateProfile(int $id, string $bio, string $profile_image): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET bio = ?, profile_image = ? WHERE id = ?");
        return $stmt->execute([$bio, $profile_image, $id]);
    }
    //account verwijderen
    public function deleteAccount(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>