<?php
// SubscriptionService.php - Service voor abonnementen tussen gebruikers
// Bevat de bedrijfslogica voor het volgen van kanalen:
// - Abonnement toggling (abonneren of opzeggen)
// - Controleren of een gebruiker al geabonneerd is
// - Abonneeaantal ophalen
// - Lijsten van abonnees en abonnementen ophalen
class SubscriptionService
{
    private SubscriptionModel $subscriptionModel;

    public function __construct(PDO $pdo)
    {
        $this->subscriptionModel = new SubscriptionModel($pdo);
    }

    public function toggle(int $subscriberId, int $leaderId): void
    {
        if ($subscriberId === $leaderId) {
            return;
        }

        if ($this->subscriptionModel->isSubscribed($subscriberId, $leaderId)) {
            $this->subscriptionModel->unsubscribe($subscriberId, $leaderId);
        } else {
            $this->subscriptionModel->subscribe($subscriberId, $leaderId);
        }
    }

    public function isSubscribed(int $subscriberId, int $leaderId): bool
    {
        return $this->subscriptionModel->isSubscribed($subscriberId, $leaderId);
    }

    public function getSubscriberCount(int $leaderId): int
    {
        return $this->subscriptionModel->countForUser($leaderId);
    }

    public function getSubscribers(int $leaderId): array
    {
        return $this->subscriptionModel->getSubscribers($leaderId);
    }

    public function getSubscriptions(int $subscriberId): array
    {
        return $this->subscriptionModel->getSubscriptions($subscriberId);
    }
}
