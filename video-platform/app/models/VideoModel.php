<?php
class VideoModel
{
    private PDO $pdo;

    // __construct() wordt automatisch aangeroepen zodra je 'new VideoModel($pdo)' schrijft
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Uploadersnaam meejoinen voorkomt een extra query per video in de view
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT videos.*, users.username AS uploader
            FROM videos
            JOIN users ON videos.user_id = users.id
            ORDER BY videos.created_at DESC
        "); // query() voor vaste queries zonder gebruikersinvoer
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // fetchAll() haalt alle rijen op als array
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT videos.*, users.username AS uploader
            FROM videos
            JOIN users ON videos.user_id = users.id
            WHERE videos.id = ?
        "); // prepare() maakt een veilige query (? = placeholder tegen SQL-injectie)
        $stmt->execute([$id]); // execute() voert de query uit en vult de ? in
        return $stmt->fetch(PDO::FETCH_ASSOC); // fetch() haalt één rij op als array
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT videos.*, users.username AS uploader
            FROM videos
            JOIN users ON videos.user_id = users.id
            WHERE videos.user_id = ?
            ORDER BY videos.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Geeft het ID van de nieuwe video terug zodat er direct categorieen aan gekoppeld kunnen worden
    public function upload(int $userId, string $title, string $description, string $filename, string $thumbnail): int|false
    {
        $stmt    = $this->pdo->prepare("INSERT INTO videos (user_id, title, description, filename, thumbnail) VALUES (?, ?, ?, ?, ?)");
        $success = $stmt->execute([$userId, $title, $description, $filename, $thumbnail]);

        if (!$success) {
            return false;
        }

        $newId = (int) $this->pdo->lastInsertId(); // lastInsertId() geeft het auto-increment ID terug van de net ingevoegde rij
        return $newId;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM videos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function incrementViews(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE videos SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function search(string $query): array
    {
        $term = '%' . $query . '%'; // % is een SQL-wildcard: zoekt alles dat de query bevat
        $stmt = $this->pdo->prepare("
            SELECT videos.*, users.username AS uploader
            FROM videos
            JOIN users ON videos.user_id = users.id
            WHERE videos.title LIKE ? OR videos.description LIKE ?
            ORDER BY videos.created_at DESC
        ");
        $stmt->execute([$term, $term]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory(int $categoryId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT videos.*, users.username AS uploader
            FROM videos
            JOIN users ON videos.user_id = users.id
            JOIN video_category ON videos.id = video_category.video_id
            WHERE video_category.category_id = ?
            ORDER BY videos.created_at DESC
        ");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // JOIN via de koppeltabel video_category omdat categorieen een N:N relatie met videos hebben
    public function getCategories(int $videoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT categories.*
            FROM categories
            JOIN video_category ON categories.id = video_category.category_id
            WHERE video_category.video_id = ?
        ");
        $stmt->execute([$videoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
