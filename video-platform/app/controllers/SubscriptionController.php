<?php
// SubscriptionController.php - Regelt abonnementen
// Verantwoordelijk voor:
// - Abonneren of opzeggen als gebruiker op de knop klikt (toggle)
// De controller verwerkt de request en roept SubscriptionService aan voor de logica.

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

        $leaderId = (int) ($_POST['leader_id'] ?? 0);
        $videoId  = (int) ($_POST['video_id'] ?? 0);

        if ($leaderId > 0) {
            $this->subscriptionService->toggle((int) $_SESSION['user_id'], $leaderId);
        }

        // Kwam de knop van een videopagina? Ga daarheen terug, anders naar het profiel
        if ($videoId > 0) {
            redirect(route('video/show', ['id' => $videoId]));
        }

        redirect(route('user/profile', ['id' => $leaderId]));
    }
}
