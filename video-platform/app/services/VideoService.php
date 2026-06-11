<?php
class VideoService
{
    private VideoModel $videoModel;

    // __construct() wordt automatisch aangeroepen zodra je 'new VideoService($pdo)' schrijft
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

    // Bestanden op schijf handmatig verwijderen want ON DELETE CASCADE ruimt alleen de databaserijen op
    public function delete(int $videoId, int $userId): bool
    {
        $video = $this->videoModel->getById($videoId);

        if (!$video) {
            return false;
        }

        if ((int) $video['user_id'] !== $userId) {
            return false;
        }

        $this->deleteFileIfExists(UPLOADS_PATH . '/videos/' . $video['filename']);

        if (!empty($video['thumbnail'])) { // empty() controleert of de waarde leeg is
            $this->deleteFileIfExists(UPLOADS_PATH . '/thumbnails/' . $video['thumbnail']);
        }

        return $this->videoModel->delete($videoId);
    }

    private function deleteFileIfExists(string $path): void
    {
        if (is_file($path)) { // is_file() controleert of het bestand echt bestaat op de server
            unlink($path);    // unlink() verwijdert het bestand van de server
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

    public function getRecommended(int $excludeId, int $limit = 12): array
    {
        $allVideos   = $this->videoModel->getAll();
        $recommended = [];

        foreach ($allVideos as $video) {
            if ((int) $video['id'] !== $excludeId) {
                $recommended[] = $video;
            }

            if (count($recommended) >= $limit) { // count() telt het aantal items in een array
                break;
            }
        }

        return $recommended;
    }

    public function search(string $query): array
    {
        return $this->videoModel->search($query);
    }

    public function incrementView(int $id): bool
    {
        return $this->videoModel->incrementViews($id);
    }

    public function upload(int $userId, string $title, string $description, ?array $videoFile, ?array $thumbnailFile): array
    {
        if (empty($title)) { // empty() controleert of de titel leeg is
            return ['success' => false, 'error' => 'Title is required.'];
        }

        if ($videoFile === null) {
            return ['success' => false, 'error' => 'Video file is required.'];
        }

        if ($videoFile['error'] !== UPLOAD_ERR_OK) { // UPLOAD_ERR_OK betekent dat het bestand zonder fouten is geüpload
            return ['success' => false, 'error' => 'Video file is required.'];
        }

        if ($videoFile['size'] > MAX_VIDEO_SIZE) {
            return ['success' => false, 'error' => 'Video is too large (max 500MB).'];
        }

        $allowedVideoTypes = ['video/mp4', 'video/webm', 'video/ogg'];
        $videoType         = mime_content_type($videoFile['tmp_name']); // mime_content_type() controleert het echte bestandstype op basis van bestandsinhoud (veiliger dan de extensie)
        $videoTypeAllowed  = in_array($videoType, $allowedVideoTypes); // in_array() controleert of het type in de lijst van toegestane types zit

        if ($videoTypeAllowed === false) {
            return ['success' => false, 'error' => 'Only MP4, WebM or OGG is allowed.'];
        }

        // Thumbnail is optioneel, maar als hij er is moet het een geldige afbeelding zijn
        $thumbnailName = '';

        if ($thumbnailFile !== null) {
            if ($thumbnailFile['error'] === UPLOAD_ERR_OK) {
                $allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp'];
                $thumbType         = mime_content_type($thumbnailFile['tmp_name']);
                $thumbTypeAllowed  = in_array($thumbType, $allowedImageTypes);

                if ($thumbTypeAllowed === false) {
                    return ['success' => false, 'error' => 'Thumbnail must be JPG, PNG or WebP.'];
                }

                $imageExtension = pathinfo($thumbnailFile['name'], PATHINFO_EXTENSION); // pathinfo() haalt de bestandsextensie op (bijv. "jpg")
                $thumbnailName  = uniqid('thumb_', true) . '.' . $imageExtension;       // uniqid() genereert een unieke bestandsnaam op basis van de tijd
                move_uploaded_file($thumbnailFile['tmp_name'], UPLOADS_PATH . '/thumbnails/' . $thumbnailName); // move_uploaded_file() verplaatst het bestand van tijdelijke naar definitieve locatie
            }
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
