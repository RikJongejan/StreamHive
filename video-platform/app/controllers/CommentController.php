<?php
// CommentController.php - Regelt reacties op video's
// Verantwoordelijk voor:
// - Nieuwe reactie opslaan
// De controller verwerkt de request en roept CommentService aan voor de logica.

class CommentController
{
    private CommentService $commentService;

    public function __construct(PDO $pdo)
    {
        $this->commentService = new CommentService($pdo);
    }

    public function post(): void
    {
        requireLogin();

        $videoId = (int) ($_POST['video_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        if ($content !== '') {
            $this->commentService->addComment((int) $_SESSION['user_id'], $videoId, $content);
        }

        redirect(route('video/show', ['id' => $videoId]));
    }
}
