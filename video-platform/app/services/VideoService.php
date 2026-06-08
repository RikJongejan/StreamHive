<?php
// VideoService.php - Logica laag voor video's
// Bevat alle regels rondom video's:
// - Video's ophalen voor het overzicht en de detailpagina
// - Een upload valideren (titel, bestandstype, grootte) en opslaan op schijf
// - De view-teller ophogen
// De controller roept deze service aan; hier zit geen HTML en geen request-afhandeling.

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

    public function getCategoriesForVideo(int $videoId): array
    {
        return $this->videoModel->getCategories($videoId);
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
