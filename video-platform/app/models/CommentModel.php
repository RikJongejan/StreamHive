<?php
// CommentModel.php - Model voor reacties op videos
// Beheert alle commentaren van gebruikers:
// - Reactie plaatsen onder een video
// - Reactie verwijderen
// - Reactie aanpassen met edit()
// Werkt met de 'comments' tabel in de database

class CommentModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // JOIN met users zodat de view geen tweede query nodig heeft om de gebruikersnaam op te halen
    public function getByVideo(int $videoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT comments.*, users.username
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.video_id = ?
            ORDER BY comments.created_at ASC
        ");
        $stmt->execute([$videoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function post(int $userId, int $videoId, string $content): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO comments (user_id, video_id, content) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $videoId, $content]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
