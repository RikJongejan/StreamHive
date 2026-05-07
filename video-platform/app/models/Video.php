<?php
// Video.php - Model voor videos
// Bevat alle logica rondom videos:
// - Video uploaden en verwijderen
// - Video ophalen op ID of gebruiker
// - Weergaven bijhouden met incrementViews()
// - Categorieen ophalen via getCategories()
// Werkt met de 'videos' tabel in de database

class Video {
    private PDO $pdo;

    public function __contruct(PDO $pdo){
        $this->pdo = $pdo;
    }
    //alle videos ophalen
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM videos ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //een video ophalen op ID
    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM videos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //alle videos ophalen van een gebruiker
    public function getByUser(int $user_id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM videos WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}