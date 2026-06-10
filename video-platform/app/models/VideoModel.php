<?php
// VideoModel.php - Model voor video's
// Beheert alle video-gerelateerde databaseoperaties:
// - Video's ophalen (alles, per gebruiker, per categorie, op id)
// - Video uploaden en verwijderen
// - Weergaven bijhouden
// - Zoeken in titel en beschrijving
// - Categorieën ophalen via de video_category koppeltabel
// Werkt met de 'videos' en 'video_category' tabellen in de database
class VideoModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // JOIN met users zodat de view direct de naam van de uploader heeft (kolom 'uploader')
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT videos.*, users.username AS uploader
            FROM videos
            JOIN users ON videos.user_id = users.id
            ORDER BY videos.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT videos.*, users.username AS uploader
            FROM videos
            JOIN users ON videos.user_id = users.id
            WHERE videos.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        $stmt = $this->pdo->prepare("INSERT INTO videos (user_id, title, description, filename, thumbnail) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt->execute([$userId, $title, $description, $filename, $thumbnail])) {
            return false;
        }
        return (int) $this->pdo->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM videos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Sessiecheck in de controller zorgt dat dezelfde gebruiker niet meerdere views kan geven bij refreshen
    public function incrementViews(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE videos SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Zoekt op titel en beschrijving met LIKE zodat beide velden doorzocht worden
    public function search(string $query): array
    {
        $term = '%' . $query . '%';
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

    // Alle video's binnen één categorie (voor het filteren op de homepagina)
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
