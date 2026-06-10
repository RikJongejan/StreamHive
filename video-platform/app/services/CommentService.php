<?php
// CommentService.php - Service voor reacties op video's
// Bevat de bedrijfslogica voor commentaren:
// - Reacties ophalen voor een video
// - Nieuwe reactie plaatsen
class CommentService
{
    private CommentModel $commentModel;

    public function __construct(PDO $pdo)
    {
        $this->commentModel = new CommentModel($pdo);
    }

    public function getCommentsForVideo(int $videoId): array
    {
        return $this->commentModel->getByVideo($videoId);
    }

    public function addComment(int $userId, int $videoId, string $content): bool
    {
        return $this->commentModel->post($userId, $videoId, $content);
    }
}
