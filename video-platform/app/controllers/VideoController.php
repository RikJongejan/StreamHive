<?php
// VideoController.php - Regelt alles rondom videos
// Verantwoordelijk voor:
// - Lijst van alle videos ophalen voor de homepagina
// - Een specifieke video ophalen op ID
// - Video uploaden (bestand opslaan + database)
// - Video verwijderen
// Gebruikt: Video model, Category model

session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Video.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';

// Gebruiker moet ingelogd zijn voor alle video-paginas
requireLogin();

$videoModel = new Video($pdo);
$error = '';

$action = $_GET['action'] ?? 'index';

//videos tonen
if ($action === 'index') {
    $videos = $videoModel->getAll();
    require_once __DIR__ . '/../../views/videos/index.php';
}

if ($action === 'show') {
    $id = (int) $_GET['id'];
    $video = $videoModel->getById($id);

    if (!$video) {
        echo 'Video niet gevonden.';
        exit;
    }
    
    //alleen ophogen als de video nog niet bekeken is
    if (!isset($_SESSION['viewed_videos'][$id])) {
        $videoModel->incrementViews($id);
        $_SESSION['viewed_videos'][$id] = true;
    }

    $commentModel = new Comment($pdo);
    $comments = $commentModel->getByVideo($id);

    require_once __DIR__ . '/../../views/videos/show.php';
}
//videos uploaden
if ($action === 'upload') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $title       = sanitiza($_POST['title'] ?? '');
        $description = sanitiza($_POST['description'] ?? '');
        $user_id     = $_SESSION['user_id'];

        $videoFile     = $_FILES['video'];
        $thumbnailFile = $_FILES['thumbnail'];

        //check of titel is ingevuld
        if (empty($title)) {
            $error = 'Titel is verplicht.';

        //check of er videobestand is
        } elseif ($videoFile['error'] !== UPLOAD_ERR_OK) {
            $error = 'Videobestand is verplicht.';

        //check bestandsgroote
        } elseif ($videoFile['size'] > 100 * 1024 * 1024) {
            $error = 'Video is te groot (max 100MB).';

        //check of het daadwerkelijk een videobestand is
        } elseif (!in_array(mime_content_type($videoFile['tmp_name']), ['video/mp4', 'video/webm', 'video/ogg'])) {
            $error = 'Alleen MP4, WebM of OGG is toegestaan.';

        } else {
            //goed? video opslaan met random naam
            $videoExt  = pathinfo($videoFile['name'], PATHINFO_EXTENSION);
            $videoName = uniqid('video_', true) . '.' . $videoExt;
            $videoPath = __DIR__ . '/../../uploads/videos/' . $videoName;
            move_uploaded_file($videoFile['tmp_name'], $videoPath);

            //thumbnail opslaan
            $thumbnailName = '';

            if ($thumbnailFile['error'] === UPLOAD_ERR_OK) {
                if (!in_array(mime_content_type($thumbnailFile['tmp_name']), ['image/jpeg', 'image/png', 'image/webp'])) {
                    $error = 'Thumbnail moet JPG, PNG of WebP zijn.';
                } else {
                    $imgExt        = pathinfo($thumbnailFile['name'], PATHINFO_EXTENSION);
                    $thumbnailName = uniqid('thumb_', true) . '.' . $imgExt;
                    $thumbPath     = __DIR__ . '/../../uploads/thumbnails/' . $thumbnailName;
                    move_uploaded_file($thumbnailFile['tmp_name'], $thumbPath);
                }
            }

            //video opslaan in db
            if (empty($error)) {
                $gelukt = $videoModel->upload($user_id, $title, $description, $videoName, $thumbnailName);

                if ($gelukt) {
                    redirect('/GitHub/StreamHive/video-platform/app/controllers/VideoController.php?action=index');
                } else {
                    $error = 'Opslaan in database mislukt.';
                }
            }
        }
    }


    require_once __DIR__ . '/../../views/videos/upload.php';
}
