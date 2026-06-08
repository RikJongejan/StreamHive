<?php
// VideoController.php - Regelt alles rondom video's
// Verantwoordelijk voor:
// - Lijst van alle video's tonen (homepagina)
// - Een specifieke video tonen met reacties
// - Video uploaden
// De controller verwerkt de request en roept VideoService en CommentService aan.

class VideoController
{
    private VideoService $videoService;
    private CommentService $commentService;
    private LikeService $likeService;
    private SubscriptionService $subscriptionService;
    private CategoryService $categoryService;

    public function __construct(PDO $pdo)
    {
        $this->videoService        = new VideoService($pdo);
        $this->commentService      = new CommentService($pdo);
        $this->likeService         = new LikeService($pdo);
        $this->subscriptionService = new SubscriptionService($pdo);
        $this->categoryService     = new CategoryService($pdo);
    }

    public function index(): void
    {
        requireLogin();

        $videos = $this->videoService->getAllVideos();
        require VIEWS_PATH . '/videos/index.php';
    }

    public function show(): void
    {
        requireLogin();

        $id    = (int) ($_GET['id'] ?? 0);
        $video = $this->videoService->getVideo($id);

        if (!$video) {
            http_response_code(404);
            echo 'Video not found.';
            return;
        }

        // Voorkomt dat dezelfde gebruiker bij elke refresh een extra view geeft
        if (!isset($_SESSION['viewed_videos'][$id])) {
            $this->videoService->incrementView($id);
            $_SESSION['viewed_videos'][$id] = true;
        }

        $comments        = $this->commentService->getCommentsForVideo($id);
        $likeCount       = $this->likeService->getLikeCount($id);
        $userLiked       = $this->likeService->hasLiked((int) $_SESSION['user_id'], $id);
        $subscriberCount = $this->subscriptionService->getSubscriberCount((int) $video['user_id']);
        $userSubscribed  = $this->subscriptionService->isSubscribed((int) $_SESSION['user_id'], (int) $video['user_id']);
        $categories      = $this->categoryService->getAllCategories();
        $videoCategories = array_column($this->videoService->getCategoriesForVideo($id), 'id');
        require VIEWS_PATH . '/videos/show.php';
    }

    public function search(): void
    {
        requireLogin();

        $query  = sanitize($_GET['query'] ?? '');
        $videos = $query !== '' ? $this->videoService->search($query) : [];
        require VIEWS_PATH . '/videos/search.php';
    }

    public function upload(): void
    {
        requireLogin();

        $error      = '';
        $categories = $this->categoryService->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->videoService->upload(
                (int) $_SESSION['user_id'],
                sanitize($_POST['title'] ?? ''),
                sanitize($_POST['description'] ?? ''),
                $_FILES['video'] ?? null,
                $_FILES['thumbnail'] ?? null
            );

            if ($result['success']) {
                $this->categoryService->saveForVideo(
                    $result['videoId'],
                    $_POST['categories'] ?? [],
                    sanitize($_POST['new_categories'] ?? '')
                );
                redirect(route('video/index'));
            }

            $error = $result['error'];
        }

        require VIEWS_PATH . '/videos/upload.php';
    }
}
