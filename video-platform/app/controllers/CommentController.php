<?php
// CommentController.php - Regelt reacties op videos
// Verantwoordelijk voor:
// - Nieuwe reactie opslaan
// - Reactie verwijderen
// - Reactie bewerken
// Gebruikt: CommentModel

session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';

requireLogin();

$commentModel = new CommentModel($pdo);
$action = $_GET['action'] ?? '';

if ($action === 'post' && $_SERVER['REQUEST_METHOD'] === 'POST')
{
    $videoId = (int) $_POST['video_id'];
    $content = trim($_POST['content'] ?? '');
    $userId  = $_SESSION['user_id'];

    if (!empty($content))
    {
        $commentModel->post($userId, $videoId, $content);
    }

    redirect('/GitHub/StreamHive/video-platform/app/controllers/VideoController.php?action=show&id=' . $videoId);
}
