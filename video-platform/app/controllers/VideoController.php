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

        $categories = $this->categoryService->getAllCategories();

        // Optioneel filteren op categorie via ?cat=ID; anders alle video's
        if (isset($_GET['cat'])) {
            $activeCategory = (int) $_GET['cat'];
        } else {
            $activeCategory = 0;
        }

        if ($activeCategory > 0) {
            $videos = $this->videoService->getVideosByCategory($activeCategory);
        } else {
            $videos = $this->videoService->getAllVideos();
        }

        require VIEWS_PATH . '/videos/index.php';
    }

    public function show(): void
    {
        requireLogin();

        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
        } else {
            $id = 0;
        }

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

        $userId         = (int) $_SESSION['user_id'];
        $uploaderUserId = (int) $video['user_id'];

        $comments        = $this->commentService->getCommentsForVideo($id);
        $likeCount       = $this->likeService->getLikeCount($id);
        $userLiked       = $this->likeService->hasLiked($userId, $id);
        $subscriberCount = $this->subscriptionService->getSubscriberCount($uploaderUserId);
        $userSubscribed  = $this->subscriptionService->isSubscribed($userId, $uploaderUserId);
        $categories      = $this->categoryService->getAllCategories();
        $recommended     = $this->videoService->getRecommended($id);

        $videoCategories = [];
        $categoryRows    = $this->videoService->getCategoriesForVideo($id);
        foreach ($categoryRows as $categoryRow) {
            $videoCategories[] = $categoryRow['id'];
        }

        if (isset($video['uploader'])) {
            $uploaderName = $video['uploader'];
        } else {
            $uploaderName = '';
        }

        if ($uploaderName !== '') {
            $uploaderInitial = strtoupper(substr($uploaderName, 0, 1));
        } else {
            $uploaderInitial = '?';
        }

        $isOwnVideo = $uploaderUserId === $userId;

        if (isset($_SESSION['username'])) {
            $myInitial = strtoupper(substr($_SESSION['username'], 0, 1));
        } else {
            $myInitial = '?';
        }

        require VIEWS_PATH . '/videos/show.php';
    }

    public function search(): void
    {
        requireLogin();

        if (isset($_GET['query'])) {
            $query = sanitize($_GET['query']);
        } else {
            $query = '';
        }

        if ($query !== '') {
            $videos = $this->videoService->search($query);
        } else {
            $videos = [];
        }

        require VIEWS_PATH . '/videos/search.php';
    }

    public function upload(): void
    {
        requireLogin();

        $error      = '';
        $categories = $this->categoryService->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId      = (int) $_SESSION['user_id'];
            $title       = sanitize($_POST['title'] ?? '');
            $description = sanitize($_POST['description'] ?? '');

            if (isset($_FILES['video'])) {
                $videoFile = $_FILES['video'];
            } else {
                $videoFile = null;
            }

            if (isset($_FILES['thumbnail'])) {
                $thumbnailFile = $_FILES['thumbnail'];
            } else {
                $thumbnailFile = null;
            }

            $result = $this->videoService->upload($userId, $title, $description, $videoFile, $thumbnailFile);

            if ($result['success']) {
                $selectedCategories = $_POST['categories'] ?? [];
                $newCategoryNames   = sanitize($_POST['new_categories'] ?? '');

                $this->categoryService->saveForVideo($result['videoId'], $selectedCategories, $newCategoryNames);
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

        if (isset($_POST['video_id'])) {
            $videoId = (int) $_POST['video_id'];
        } else {
            $videoId = 0;
        }

        $this->videoService->delete($videoId, (int) $_SESSION['user_id']);

        redirect(route('user/profile'));
    }
}
