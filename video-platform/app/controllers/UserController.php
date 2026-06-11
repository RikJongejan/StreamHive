<?php
class UserController
{
    private UserService $userService;
    private VideoService $videoService;
    private SubscriptionService $subscriptionService;

    // __construct() wordt automatisch aangeroepen zodra je 'new UserController($pdo)' schrijft
    public function __construct(PDO $pdo)
    {
        $this->userService         = new UserService($pdo);
        $this->videoService        = new VideoService($pdo);
        $this->subscriptionService = new SubscriptionService($pdo);
    }

    public function profile(): void
    {
        Auth::requireLogin();

        $currentId = (int) $_SESSION['user_id'];

        if (isset($_GET['id'])) { // isset() controleert of de URL-parameter bestaat
            $profileId = (int) $_GET['id'];
        } else {
            $profileId = $currentId;
        }

        $profileUser = $this->userService->getProfile($profileId);

        if (!$profileUser) {
            http_response_code(404); // http_response_code() stuurt een HTTP-statuscode (404 = pagina niet gevonden)
            echo 'User not found.';
            return;
        }

        $isOwn           = $profileId === $currentId;
        $videos          = $this->videoService->getVideosByUser($profileId);
        $subscriberCount = $this->subscriptionService->getSubscriberCount($profileId);
        $subscribers     = $this->subscriptionService->getSubscribers($profileId);
        $userSubscribed  = $this->subscriptionService->isSubscribed($currentId, $profileId);
        // Abonnementen alleen ophalen voor de eigenaar zodat andere gebruikers dit niet kunnen inzien
        if ($isOwn) {
            $subscriptions = $this->subscriptionService->getSubscriptions($profileId);
        } else {
            $subscriptions = [];
        }
        // strtoupper() maakt hoofdletter, substr() haalt het eerste teken op — samen de avatar-initiaal
        $avatarInitial   = strtoupper(substr($profileUser['username'], 0, 1));

        $pageTitle = $profileUser['username'];
        require VIEWS_PATH . '/user/profile.php';
    }

    public function settings(): void
    {
        Auth::requireLogin();

        $user          = $this->userService->getProfile((int) $_SESSION['user_id']);
        $error         = '';
        $avatarInitial = strtoupper(substr($user['username'], 0, 1)); // strtoupper() + substr() voor de avatar-initiaal (eerste letter als hoofdletter)

        $pageTitle = 'Instellingen';
        require VIEWS_PATH . '/user/settings.php';
    }

    public function update(): void
    {
        Auth::requireLogin();

        $id      = (int) $_SESSION['user_id'];
        $current = $this->userService->getProfile($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // $_SERVER['REQUEST_METHOD'] controleert of het formulier is verzonden via POST
            $result = $this->userService->updateProfile(
                $id,
                trim($_POST['username'] ?? ''), // trim() verwijdert spaties aan begin en einde
                trim($_POST['bio'] ?? ''),
                $_FILES['avatar'] ?? null,
                $current['profile_image'] ?? ''
            );

            if ($result['success']) {
                // Sessie bijwerken zodat de navbar meteen de nieuwe naam toont
                $_SESSION['username'] = $result['username'];
                Helpers::redirect(Helpers::route('user/profile'));
            }

            $error         = $result['error'];
            $user          = $current;
            $avatarInitial = strtoupper(substr($user['username'], 0, 1));
            $pageTitle     = 'Instellingen';
            require VIEWS_PATH . '/user/settings.php';
            return;
        }

        Helpers::redirect(Helpers::route('user/settings'));
    }
}
