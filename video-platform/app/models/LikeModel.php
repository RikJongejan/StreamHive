<?php
// LikeModel.php - Model voor likes op videos
// Bijhoudt welke gebruiker welke video heeft geliked
// - Like toevoegen met add()
// - Like verwijderen met remove()
// - Checken of gebruiker al geliked heeft
// Werkt met de 'likes' tabel in de database

class LikeModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
