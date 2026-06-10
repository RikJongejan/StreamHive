<?php
// LikeController.php - Controller voor likes op video's
// Verwerkt het liken en unliken van video's:
// - Like toggling via POST
// - Doorverwijzen naar de videopagina na actie
class LikeController
{
    private LikeService $likeService;

    public function __construct(PDO $pdo)
    {
        $this->likeService = new LikeService($pdo);
    }

    public function toggle(): void
    {
        requireLogin();

        if (isset($_POST['video_id'])) {
            $videoId = (int) $_POST['video_id'];
        } else {
            $videoId = 0;
        }

        if ($videoId > 0) {
            $this->likeService->toggle((int) $_SESSION['user_id'], $videoId);
        }

        redirect(route('video/show', ['id' => $videoId]));
    }
}
