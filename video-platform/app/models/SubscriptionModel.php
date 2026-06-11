<?php
class SubscriptionModel
{
    private PDO $pdo;

    // __construct() wordt automatisch aangeroepen zodra je 'new SubscriptionModel($pdo)' schrijft
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function subscribe(int $subscriberId, int $leaderId): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO subscriptions (subscriber_id, leader_id) VALUES (?, ?)"); // prepare() maakt een veilige query (? = placeholder tegen SQL-injectie)
        return $stmt->execute([$subscriberId, $leaderId]); // execute() voert de query uit en vult de ?'s in
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
        $row = $stmt->fetch(); // fetch() haalt één rij op — geeft false als er geen rij gevonden is

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
        $count = $stmt->fetchColumn(); // fetchColumn() haalt de waarde van één kolom op — handig voor COUNT(*)
        return (int) $count;
    }

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
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // fetchAll() haalt alle rijen op als array
    }

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
