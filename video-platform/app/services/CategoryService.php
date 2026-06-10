<?php
// CategoryService.php - Service voor videocategorieën
// Bevat de bedrijfslogica voor categorieën:
// - Alle categorieën ophalen
// - Categorieën opslaan na een video-upload
//   (bestaande aanvinken + nieuwe aanmaken via komma-gescheiden invoer)
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
            $rawNames = explode(',', $newNames);
            $names    = [];

            foreach ($rawNames as $rawName) {
                $names[] = trim($rawName);
            }

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
