<?php
// SubscriptionModel.php - Model voor abonnementen tussen gebruikers
// Beheert het volgen en ontvolgen van kanalen:
// - Abonneren en opzeggen
// - Controleren of een gebruiker al geabonneerd is
// - Abonneeaantal ophalen
// - Lijst van abonnees en abonnementen ophalen
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
        $row = $stmt->fetch();

        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    public function countForUser(int $leaderId): int
    {
        $stmt  = $this->pdo->prepare("SELECT COUNT(*) FROM subscriptions WHERE leader_id = ?");
        $stmt->execute([$leaderId]);
        $count = $stmt->fetchColumn();
        return (int) $count;
    }

    // Lijst van gebruikers die deze leader volgen (voor op de profielpagina)
    public function getSubscribers(int $leaderId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT users.id, users.username, users.profile_image
            FROM subscriptions
            JOIN users ON subscriptions.subscriber_id = users.id
            WHERE subscriptions.leader_id = ?
            ORDER BY subscriptions.created_at DESC
        ");
        $stmt->execute([$leaderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lijst van gebruikers op wie deze gebruiker zelf geabonneerd is
    public function getSubscriptions(int $subscriberId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT users.id, users.username, users.profile_image
            FROM subscriptions
            JOIN users ON subscriptions.leader_id = users.id
            WHERE subscriptions.subscriber_id = ?
            ORDER BY subscriptions.created_at DESC
        ");
        $stmt->execute([$subscriberId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
