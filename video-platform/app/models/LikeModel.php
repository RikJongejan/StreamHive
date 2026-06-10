<?php
// LikeModel.php - Model voor likes op video's
// Beheert het liken en unliken van video's:
// - Like toevoegen of verwijderen
// - Controleren of een gebruiker een video al geliket heeft
// - Totaal aantal likes per video ophalen
// Werkt met de 'likes' tabel in de database
class LikeModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(int $userId, int $videoId): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO likes (user_id, video_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $videoId]);
    }

    public function remove(int $userId, int $videoId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM likes WHERE user_id = ? AND video_id = ?");
        return $stmt->execute([$userId, $videoId]);
    }

    public function hasLiked(int $userId, int $videoId): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM likes WHERE user_id = ? AND video_id = ?");
        $stmt->execute([$userId, $videoId]);
        return (bool) $stmt->fetch();
    }

    public function countForVideo(int $videoId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM likes WHERE video_id = ?");
        $stmt->execute([$videoId]);
        return (int) $stmt->fetchColumn();
    }
}
