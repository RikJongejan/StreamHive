<?php
// UserModel.php - Model voor gebruikers
// Beheert alle gebruikersgerelateerde databaseoperaties:
// - Gebruiker registreren en inloggen
// - Gebruiker ophalen op id, e-mail of gebruikersnaam
// - Profielgegevens bijwerken
// - Account verwijderen
// Werkt met de 'users' tabel in de database
class UserModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUsername(string $username): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Controleert email en gebruikersnaam vooraf om dubbele accounts te voorkomen
    public function register(string $email, string $username, string $password): bool
    {
        if ($this->getByEmail($email)) return false;
        if ($this->getByUsername($username)) return false;

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $username, $hashedPassword]);
    }

    public function login(string $email, string $password): array|false
    {
        $user = $this->getByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function updateProfile(int $id, string $username, string $bio, string $profileImage): bool
    {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, bio = ?, profile_image = ? WHERE id = ?");
        return $stmt->execute([$username, $bio, $profileImage, $id]);
    }

    public function deleteAccount(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
