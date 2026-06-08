<?php
// CommentService.php - Logica laag voor reacties
// Bevat de regels rondom reacties op video's:
// - Reacties van een video ophalen (met gebruikersnaam erbij)
// - Een nieuwe reactie plaatsen
// De controller roept deze service aan en houdt zich alleen met de request bezig.

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
