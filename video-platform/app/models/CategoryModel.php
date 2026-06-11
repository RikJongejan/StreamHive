<?php
class CategoryModel
{
    private PDO $pdo;

    // __construct() wordt automatisch aangeroepen zodra je 'new CategoryModel($pdo)' schrijft
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name ASC"); // query() voor vaste queries zonder gebruikersinvoer
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // fetchAll() haalt alle rijen op als array
    }

    // Categorie aanmaken als die nog niet bestaat voorkomt dubbele rijen in de categorietabel
    public function findOrCreate(string $name): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE name = ?"); // prepare() maakt een veilige query (? = placeholder tegen SQL-injectie)
        $stmt->execute([$name]); // execute() voert de query uit en vult de ? in
        $existing = $stmt->fetch(PDO::FETCH_ASSOC); // fetch() haalt één rij op als array

        if ($existing) {
            return (int) $existing['id'];
        }

        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        return (int) $this->pdo->lastInsertId(); // lastInsertId() geeft het auto-increment ID terug van de net ingevoegde rij
    }

    public function assign(int $videoId, int $categoryId): void
    {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO video_category (video_id, category_id) VALUES (?, ?)"); // INSERT IGNORE sloeg de insert over als de combinatie al bestaat
        $stmt->execute([$videoId, $categoryId]);
    }

    public function remove(int $videoId, int $categoryId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM video_category WHERE video_id = ? AND category_id = ?");
        $stmt->execute([$videoId, $categoryId]);
    }
}
