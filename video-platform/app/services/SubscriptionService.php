<?php
// SubscriptionService.php - Logica laag voor abonnementen
// Regelt het toggling van abonnementen:
// - Al geabonneerd? Dan opzeggen
// - Nog niet geabonneerd? Dan abonneren
// - Je kan jezelf niet abonneren op jezelf

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
}
