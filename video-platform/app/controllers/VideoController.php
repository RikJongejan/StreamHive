<?php
// VideoController.php - Regelt alles rondom videos
// Verantwoordelijk voor:
// - Lijst van alle videos ophalen voor de homepagina
// - Een specifieke video ophalen op ID
// - Video uploaden (bestand opslaan + database)
// - Video verwijderen
// Gebruikt: VideoModel, CategoryModel

session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/VideoModel.php';
require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';

requireLogin();

$videoModel = new VideoModel($pdo);
$error = '';

$action = $_GET['action'] ?? 'index';

if ($action === 'index')
{
    $videos = $videoModel->getAll();
    require_once __DIR__ . '/../../views/videos/index.php';
}

if ($action === 'show')
{
    $id = (int) $_GET['id'];
    $video = $videoModel->getById($id);

    if (!$video)
    {
        echo 'Video not found.';
        exit;
    }

    // Prevents the same user from adding a view on every refresh
    if (!isset($_SESSION['viewed_videos'][$id]))
    {
        $videoModel->incrementViews($id);
        $_SESSION['viewed_videos'][$id] = true;
    }

    $commentModel = new CommentModel($pdo);
    $comments = $commentModel->getByVideo($id);

    require_once __DIR__ . '/../../views/videos/show.php';
}

if ($action === 'upload')
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $title       = sanitize($_POST['title'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $userId      = $_SESSION['user_id'];

        $videoFile     = $_FILES['video'];
        $thumbnailFile = $_FILES['thumbnail'];

        if (empty($title))
        {
            $error = 'Title is required.';
        }
        elseif ($videoFile['error'] !== UPLOAD_ERR_OK)
        {
            $error = 'Video file is required.';
        }
        elseif ($videoFile['size'] > 100 * 1024 * 1024)
        {
            $error = 'Video is too large (max 100MB).';
        }
        elseif (!in_array(mime_content_type($videoFile['tmp_name']), ['video/mp4', 'video/webm', 'video/ogg']))
        {
            $error = 'Only MP4, WebM or OGG is allowed.';
        }
        else
        {
            $videoExt  = pathinfo($videoFile['name'], PATHINFO_EXTENSION);
            $videoName = uniqid('video_', true) . '.' . $videoExt;
            $videoPath = __DIR__ . '/../../uploads/videos/' . $videoName;
            move_uploaded_file($videoFile['tmp_name'], $videoPath);

            $thumbnailName = '';

            if ($thumbnailFile['error'] === UPLOAD_ERR_OK)
            {
                if (!in_array(mime_content_type($thumbnailFile['tmp_name']), ['image/jpeg', 'image/png', 'image/webp']))
                {
                    $error = 'Thumbnail must be JPG, PNG or WebP.';
                }
                else
                {
                    $imgExt        = pathinfo($thumbnailFile['name'], PATHINFO_EXTENSION);
                    $thumbnailName = uniqid('thumb_', true) . '.' . $imgExt;
                    $thumbPath     = __DIR__ . '/../../uploads/thumbnails/' . $thumbnailName;
                    move_uploaded_file($thumbnailFile['tmp_name'], $thumbPath);
                }
            }

            if (empty($error))
            {
                $success = $videoModel->upload($userId, $title, $description, $videoName, $thumbnailName);

                if ($success)
                {
                    redirect('/GitHub/StreamHive/video-platform/app/controllers/VideoController.php?action=index');
                }
                else
                {
                    $error = 'Saving to database failed.';
                }
            }
        }
    }

    require_once __DIR__ . '/../../views/videos/upload.php';
}
