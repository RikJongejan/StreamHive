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

        if (isset($_POST['video_id'])) {
            $videoId = (int) $_POST['video_id'];
        } else {
            $videoId = 0;
        }

        if (isset($_POST['content'])) {
            $content = trim($_POST['content']);
        } else {
            $content = '';
        }

        if ($content !== '') {
            $this->commentService->addComment((int) $_SESSION['user_id'], $videoId, $content);
        }

        redirect(route('video/show', ['id' => $videoId]));
    }
}
