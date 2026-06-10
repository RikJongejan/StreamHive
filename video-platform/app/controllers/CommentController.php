<?php
// CommentController.php - Controller voor reacties op video's
// Verwerkt het plaatsen van reacties:
// - Reactie opslaan via POST
// - Doorverwijzen naar de videopagina na plaatsen
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
