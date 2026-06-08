<?php
// LikeService.php - Logica laag voor likes
// Regelt het toggling van likes op video's:
// - Als de gebruiker nog niet geliked heeft: like toevoegen
// - Als de gebruiker al geliked heeft: like verwijderen
// De controller roept toggle() aan en hoeft niet zelf te checken.

class LikeService
{
    private LikeModel $likeModel;

    public function __construct(PDO $pdo)
    {
        $this->likeModel = new LikeModel($pdo);
    }

    public function toggle(int $userId, int $videoId): void
    {
        if ($this->likeModel->hasLiked($userId, $videoId)) {
            $this->likeModel->remove($userId, $videoId);
        } else {
            $this->likeModel->add($userId, $videoId);
        }
    }

    public function getLikeCount(int $videoId): int
    {
        return $this->likeModel->countForVideo($videoId);
    }

    public function hasLiked(int $userId, int $videoId): bool
    {
        return $this->likeModel->hasLiked($userId, $videoId);
    }
}
