<?php
// CategoryModel.php - Model voor categorieen van videos
// Koppelt een categorienaam aan een video
// - Categorie toewijzen aan een video met assign()
// - Categorie verwijderen met remove()
// Werkt met de 'categories' tabel in de database

class CategoryModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
