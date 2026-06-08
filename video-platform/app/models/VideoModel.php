<?php
// VideoModel.php - Model voor videos
// Bevat alle logica rondom videos:
// - Video uploaden en verwijderen
// - Video ophalen op ID of gebruiker
// - Weergaven bijhouden met incrementViews()
// - Categorieen ophalen via getCategories()
// Werkt met de 'videos' tabel in de database

class VideoModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM videos ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM videos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM videos WHERE user_id = ?");
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
            SELECT * FROM videos
            WHERE title LIKE ? OR description LIKE ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$term, $term]);
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
