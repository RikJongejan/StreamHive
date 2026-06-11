<?php
class UserModel
{
    private PDO $pdo;

    // __construct() wordt automatisch aangeroepen zodra je 'new UserModel($pdo)' schrijft
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users"); // query() voor vaste queries zonder gebruikersinvoer
        return $stmt->fetchAll(PDO::FETCH_ASSOC);         // fetchAll() haalt alle rijen op als array
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?"); // prepare() maakt een veilige query (? = placeholder tegen SQL-injectie)
        $stmt->execute([$id]);                                           // execute() voert de query uit en vult de ? in met $id
        return $stmt->fetch(PDO::FETCH_ASSOC);                          // fetch() haalt één rij op als array
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
        if ($this->getByEmail($email)) {
            return false;
        }

        if ($this->getByUsername($username)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // password_hash() hasht het wachtwoord veilig — nooit plaintext opslaan!
        $stmt = $this->pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $username, $hashedPassword]);
    }

    public function login(string $email, string $password): array|false
    {
        $user = $this->getByEmail($email);

        if ($user) {
            if (password_verify($password, $user['password'])) { // password_verify() vergelijkt het ingevoerde wachtwoord met de opgeslagen hash
                return $user;
            }
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
