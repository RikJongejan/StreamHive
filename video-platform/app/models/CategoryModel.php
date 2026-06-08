<?php
// CategoryModel.php - Model voor categorieen van videos
// Beheert de categories tabel en de video_category koppeltabel:
// - Alle categorieen ophalen voor checkboxes in het uploadformulier
// - Categorie zoeken op naam of aanmaken als hij nog niet bestaat
// - Categorie koppelen aan een video via de koppeltabel (N:N)

class CategoryModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Zoekt een categorie op naam. Bestaat hij nog niet, dan wordt hij aangemaakt.
    // Geeft altijd het id terug zodat je hem direct kunt koppelen aan een video.
    public function findOrCreate(string $name): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->execute([$name]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            return (int) $existing['id'];
        }

        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        return (int) $this->pdo->lastInsertId();
    }

    public function assign(int $videoId, int $categoryId): void
    {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO video_category (video_id, category_id) VALUES (?, ?)");
        $stmt->execute([$videoId, $categoryId]);
    }

    public function remove(int $videoId, int $categoryId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM video_category WHERE video_id = ? AND category_id = ?");
        $stmt->execute([$videoId, $categoryId]);
    }
}
