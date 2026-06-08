<?php
// CategoryService.php - Logica laag voor categorieen
// Regelt het koppelen van categorieen aan videos:
// - Bestaande categorieen ophalen voor het uploadformulier
// - Geselecteerde en nieuwe categorieen opslaan en koppelen aan een video

class CategoryService
{
    private CategoryModel $categoryModel;

    public function __construct(PDO $pdo)
    {
        $this->categoryModel = new CategoryModel($pdo);
    }

    public function getAllCategories(): array
    {
        return $this->categoryModel->getAll();
    }

    // Verwerkt de categorieen na een video-upload:
    // - $selectedIds: bestaande categorieen die de gebruiker heeft aangevinkt
    // - $newNames: komma-gescheiden string met nieuwe categorienamen
    public function saveForVideo(int $videoId, array $selectedIds, string $newNames): void
    {
        foreach ($selectedIds as $categoryId) {
            $this->categoryModel->assign($videoId, (int) $categoryId);
        }

        if ($newNames !== '') {
            $names = array_map('trim', explode(',', $newNames));
            foreach ($names as $name) {
                if ($name === '') {
                    continue;
                }
                $categoryId = $this->categoryModel->findOrCreate($name);
                $this->categoryModel->assign($videoId, $categoryId);
            }
        }
    }
}
