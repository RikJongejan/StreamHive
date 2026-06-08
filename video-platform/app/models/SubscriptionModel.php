<?php
// SubscriptionModel.php - Model voor abonnementen tussen gebruikers
// Bijhoudt wie wie volgt, vergelijkbaar met YouTube abonnementen:
// - subscriber_id: de gebruiker die iemand volgt
// - leader_id: de gebruiker die gevolgd wordt
// Werkt met de 'subscriptions' tabel in de database

class SubscriptionModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function subscribe(int $subscriberId, int $leaderId): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO subscriptions (subscriber_id, leader_id) VALUES (?, ?)");
        return $stmt->execute([$subscriberId, $leaderId]);
    }

    public function unsubscribe(int $subscriberId, int $leaderId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM subscriptions WHERE subscriber_id = ? AND leader_id = ?");
        return $stmt->execute([$subscriberId, $leaderId]);
    }

    public function isSubscribed(int $subscriberId, int $leaderId): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM subscriptions WHERE subscriber_id = ? AND leader_id = ?");
        $stmt->execute([$subscriberId, $leaderId]);
        return (bool) $stmt->fetch();
    }

    public function countForUser(int $leaderId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM subscriptions WHERE leader_id = ?");
        $stmt->execute([$leaderId]);
        return (int) $stmt->fetchColumn();
    }
}
