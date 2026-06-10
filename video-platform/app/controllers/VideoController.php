<?php
// VideoController.php - Controller voor video's
// Beheert alle video-gerelateerde pagina's en acties:
// - Overzichtspagina met optioneel categoriefilter (index)
// - Videopagina met reacties, likes en aanbevelingen (show)
// - Zoekpagina (search)
// - Uploadformulier en verwerking (upload)
// - Video verwijderen (delete)
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

        // Optioneel filteren op categorie via ?cat=ID; anders alle video's
        $categories     = $this->categoryService->getAllCategories();
        $activeCategory = (int) ($_GET['cat'] ?? 0);

        $videos = $activeCategory > 0
            ? $this->videoService->getVideosByCategory($activeCategory)
            : $this->videoService->getAllVideos();

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
        $recommended     = $this->videoService->getRecommended($id);

        $uploaderName    = $video['uploader'] ?? '';
        $uploaderInitial = $uploaderName !== '' ? strtoupper(substr($uploaderName, 0, 1)) : '?';
        $isOwnVideo      = (int) $video['user_id'] === (int) $_SESSION['user_id'];
        $myInitial       = strtoupper(substr($_SESSION['username'] ?? '?', 0, 1));

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

    // Verwijdert een eigen video. De service controleert het eigenaarschap.
    public function delete(): void
    {
        requireLogin();

        $videoId = (int) ($_POST['video_id'] ?? 0);
        $this->videoService->delete($videoId, (int) $_SESSION['user_id']);

        redirect(route('user/profile'));
    }
}
