<?php
class LikeModel
{
    private PDO $pdo;

    // __construct() wordt automatisch aangeroepen zodra je 'new LikeModel($pdo)' schrijft
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(int $userId, int $videoId): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO likes (user_id, video_id) VALUES (?, ?)"); // prepare() maakt een veilige query (? = placeholder tegen SQL-injectie)
        return $stmt->execute([$userId, $videoId]); // execute() voert de query uit en vult de ?'s in
    }

    public function remove(int $userId, int $videoId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM likes WHERE user_id = ? AND video_id = ?");
        return $stmt->execute([$userId, $videoId]);
    }

    public function hasLiked(int $userId, int $videoId): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM likes WHERE user_id = ? AND video_id = ?");
        $stmt->execute([$userId, $videoId]);
        $row = $stmt->fetch(); // fetch() haalt één rij op — als er geen rij is, geeft het false terug

        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    public function countForVideo(int $videoId): int
    {
        $stmt  = $this->pdo->prepare("SELECT COUNT(*) FROM likes WHERE video_id = ?");
        $stmt->execute([$videoId]);
        $count = $stmt->fetchColumn(); // fetchColumn() haalt de waarde van één kolom op — handig voor COUNT(*)
        return (int) $count;
    }
}
