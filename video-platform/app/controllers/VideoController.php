<?php
class VideoController
{
    private VideoService $videoService;
    private CommentService $commentService;
    private LikeService $likeService;
    private SubscriptionService $subscriptionService;
    private CategoryService $categoryService;

    // __construct() wordt automatisch aangeroepen zodra je 'new VideoController($pdo)' schrijft
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
        Auth::requireLogin();

        $categories = $this->categoryService->getAllCategories();

        if (isset($_GET['cat'])) { // isset() controleert of de URL-parameter bestaat
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
        Auth::requireLogin();

        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
        } else {
            $id = 0;
        }

        $video = $this->videoService->getVideo($id);

        if (!$video) {
            http_response_code(404); // http_response_code() stuurt een HTTP-statuscode naar de browser (404 = pagina niet gevonden)
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
            $uploaderInitial = strtoupper(substr($uploaderName, 0, 1)); // strtoupper() maakt hoofdletter, substr(string, 0, 1) haalt het eerste teken op
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
        Auth::requireLogin();

        if (isset($_GET['query'])) {
            $query = Helpers::sanitize($_GET['query']); // sanitize() verwijdert gevaarlijke HTML-tekens uit de zoekterm
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
        Auth::requireLogin();

        $error      = '';
        $categories = $this->categoryService->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId      = (int) $_SESSION['user_id'];
            $title       = Helpers::sanitize($_POST['title'] ?? '');
            $description = Helpers::sanitize($_POST['description'] ?? '');

            if (isset($_FILES['video'])) { // $_FILES bevat de geüploade bestanden
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
                $newCategoryNames   = Helpers::sanitize($_POST['new_categories'] ?? '');

                $this->categoryService->saveForVideo($result['videoId'], $selectedCategories, $newCategoryNames);
                Helpers::redirect(Helpers::route('video/index'));
            }

            $error = $result['error'];
        }

        require VIEWS_PATH . '/videos/upload.php';
    }

    // Eigenaarschapscontrole zit in de service zodat de controller geen bedrijfslogica bevat
    public function delete(): void
    {
        Auth::requireLogin();

        if (isset($_POST['video_id'])) {
            $videoId = (int) $_POST['video_id'];
        } else {
            $videoId = 0;
        }

        $this->videoService->delete($videoId, (int) $_SESSION['user_id']);

        Helpers::redirect(Helpers::route('user/profile'));
    }
}
