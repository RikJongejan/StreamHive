<?php
// LikeController.php - Regelt likes op video's
// Verantwoordelijk voor:
// - Like toevoegen of verwijderen als gebruiker op de knop klikt (toggle)
// De controller verwerkt de request en roept LikeService aan voor de logica.

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

        $videoId = (int) ($_POST['video_id'] ?? 0);

        if ($videoId > 0) {
            $this->likeService->toggle((int) $_SESSION['user_id'], $videoId);
        }

        redirect(route('video/show', ['id' => $videoId]));
    }
}
