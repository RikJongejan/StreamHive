<?php
// SubscriptionController.php - Controller voor abonnementen
// Verwerkt het abonneren en opzeggen op kanalen:
// - Abonnement toggling via POST
// - Doorverwijzen naar videopagina of profielpagina na actie
class SubscriptionController
{
    private SubscriptionService $subscriptionService;

    public function __construct(PDO $pdo)
    {
        $this->subscriptionService = new SubscriptionService($pdo);
    }

    public function toggle(): void
    {
        requireLogin();

        if (isset($_POST['leader_id'])) {
            $leaderId = (int) $_POST['leader_id'];
        } else {
            $leaderId = 0;
        }

        if (isset($_POST['video_id'])) {
            $videoId = (int) $_POST['video_id'];
        } else {
            $videoId = 0;
        }

        if ($leaderId > 0) {
            $this->subscriptionService->toggle((int) $_SESSION['user_id'], $leaderId);
        }

        // Na het abonneren terugsturen naar de video geeft een betere ervaring dan altijd naar het profiel gaan
        if ($videoId > 0) {
            redirect(route('video/show', ['id' => $videoId]));
        }

        redirect(route('user/profile', ['id' => $leaderId]));
    }
}
