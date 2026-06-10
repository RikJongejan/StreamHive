<?php
// VideoService.php - Service voor video-gerelateerde bedrijfslogica
// Bevat de bedrijfslogica rondom video's:
// - Video's ophalen en zoeken
// - Uploaden met bestandsvalidatie en opslag
// - Verwijderen inclusief bestanden op schijf
// - Weergaveteller ophogen
// - Aanbevelingen genereren
class VideoService
{
    private VideoModel $videoModel;

    public function __construct(PDO $pdo)
    {
        $this->videoModel = new VideoModel($pdo);
    }

    public function getAllVideos(): array
    {
        return $this->videoModel->getAll();
    }

    public function getVideo(int $id): array|false
    {
        return $this->videoModel->getById($id);
    }

    public function getVideosByUser(int $userId): array
    {
        return $this->videoModel->getByUser($userId);
    }

    // Verwijdert een video, maar alleen als hij van de ingelogde gebruiker is.
    // Ruimt ook de bestanden op schijf op. Gekoppelde rijen (likes, reacties,
    // categorie-koppelingen) verdwijnen vanzelf door ON DELETE CASCADE.
    public function delete(int $videoId, int $userId): bool
    {
        $video = $this->videoModel->getById($videoId);

        if (!$video || (int) $video['user_id'] !== $userId) {
            return false;
        }

        $this->deleteFileIfExists(UPLOADS_PATH . '/videos/' . $video['filename']);
        if (!empty($video['thumbnail'])) {
            $this->deleteFileIfExists(UPLOADS_PATH . '/thumbnails/' . $video['thumbnail']);
        }

        return $this->videoModel->delete($videoId);
    }

    private function deleteFileIfExists(string $path): void
    {
        if (is_file($path)) {
            unlink($path);
        }
    }

    public function getCategoriesForVideo(int $videoId): array
    {
        return $this->videoModel->getCategories($videoId);
    }

    public function getVideosByCategory(int $categoryId): array
    {
        return $this->videoModel->getByCategory($categoryId);
    }

    // Andere video's om naast de huidige aan te bevelen (max $limit stuks)
    public function getRecommended(int $excludeId, int $limit = 12): array
    {
        $others = array_values(array_filter(
            $this->videoModel->getAll(),
            fn($v) => (int) $v['id'] !== $excludeId
        ));

        return array_slice($others, 0, $limit);
    }

    public function search(string $query): array
    {
        return $this->videoModel->search($query);
    }

    public function incrementView(int $id): bool
    {
        return $this->videoModel->incrementViews($id);
    }

    // Valideert en slaat een upload op. Geeft array terug met 'success' en bij fout 'error'.
    public function upload(int $userId, string $title, string $description, ?array $videoFile, ?array $thumbnailFile): array
    {
        if (empty($title)) {
            return ['success' => false, 'error' => 'Title is required.'];
        }

        if ($videoFile === null || $videoFile['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Video file is required.'];
        }

        if ($videoFile['size'] > MAX_VIDEO_SIZE) {
            return ['success' => false, 'error' => 'Video is too large (max 500MB).'];
        }

        if (!in_array(mime_content_type($videoFile['tmp_name']), ['video/mp4', 'video/webm', 'video/ogg'])) {
            return ['success' => false, 'error' => 'Only MP4, WebM or OGG is allowed.'];
        }

        // Thumbnail is optioneel, maar als hij er is moet het een geldige afbeelding zijn
        $thumbnailName = '';

        if ($thumbnailFile !== null && $thumbnailFile['error'] === UPLOAD_ERR_OK) {
            if (!in_array(mime_content_type($thumbnailFile['tmp_name']), ['image/jpeg', 'image/png', 'image/webp'])) {
                return ['success' => false, 'error' => 'Thumbnail must be JPG, PNG or WebP.'];
            }

            $imageExtension = pathinfo($thumbnailFile['name'], PATHINFO_EXTENSION);
            $thumbnailName  = uniqid('thumb_', true) . '.' . $imageExtension;
            move_uploaded_file($thumbnailFile['tmp_name'], UPLOADS_PATH . '/thumbnails/' . $thumbnailName);
        }

        $videoExtension = pathinfo($videoFile['name'], PATHINFO_EXTENSION);
        $videoName      = uniqid('video_', true) . '.' . $videoExtension;
        move_uploaded_file($videoFile['tmp_name'], UPLOADS_PATH . '/videos/' . $videoName);

        $videoId = $this->videoModel->upload($userId, $title, $description, $videoName, $thumbnailName);

        if (!$videoId) {
            return ['success' => false, 'error' => 'Saving to database failed.'];
        }

        return ['success' => true, 'videoId' => $videoId];
    }
}
