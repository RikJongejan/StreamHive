<?php
// LikeService.php - Service voor likes op video's
// Bevat de bedrijfslogica voor het liken van video's:
// - Like toggling (liken als nog niet geliket, unliken als al geliket)
// - Aantal likes ophalen
// - Controleren of een gebruiker een video al geliket heeft
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
