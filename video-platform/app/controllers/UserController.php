<?php
// UserController.php - Controller voor gebruikersprofielen en instellingen
// Beheert alle gebruikersgerelateerde pagina's en acties:
// - Profielpagina van een gebruiker weergeven (profile)
// - Instellingenpagina weergeven (settings)
// - Profielwijzigingen opslaan inclusief avatar-upload (update)
class UserController
{
    private UserService $userService;
    private VideoService $videoService;
    private SubscriptionService $subscriptionService;

    public function __construct(PDO $pdo)
    {
        $this->userService         = new UserService($pdo);
        $this->videoService        = new VideoService($pdo);
        $this->subscriptionService = new SubscriptionService($pdo);
    }

    public function profile(): void
    {
        requireLogin();

        $currentId = (int) $_SESSION['user_id'];

        if (isset($_GET['id'])) {
            $profileId = (int) $_GET['id'];
        } else {
            $profileId = $currentId;
        }

        $profileUser = $this->userService->getProfile($profileId);

        if (!$profileUser) {
            http_response_code(404);
            echo 'Gebruiker niet gevonden.';
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
        $avatarInitial   = strtoupper(substr($profileUser['username'], 0, 1));

        $pageTitle = $profileUser['username'];
        require VIEWS_PATH . '/user/profile.php';
    }

    public function settings(): void
    {
        requireLogin();

        $user          = $this->userService->getProfile((int) $_SESSION['user_id']);
        $error         = '';
        $avatarInitial = strtoupper(substr($user['username'], 0, 1));

        $pageTitle = 'Instellingen';
        require VIEWS_PATH . '/user/settings.php';
    }

    public function update(): void
    {
        requireLogin();

        $id      = (int) $_SESSION['user_id'];
        $current = $this->userService->getProfile($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->userService->updateProfile(
                $id,
                trim($_POST['username'] ?? ''),
                trim($_POST['bio'] ?? ''),
                $_FILES['avatar'] ?? null,
                $current['profile_image'] ?? ''
            );

            if ($result['success']) {
                // Sessie bijwerken zodat de navbar meteen de nieuwe naam toont
                $_SESSION['username'] = $result['username'];
                redirect(route('user/profile'));
            }

            $error         = $result['error'];
            $user          = $current;
            $avatarInitial = strtoupper(substr($user['username'], 0, 1));
            $pageTitle     = 'Instellingen';
            require VIEWS_PATH . '/user/settings.php';
            return;
        }

        redirect(route('user/settings'));
    }
}
