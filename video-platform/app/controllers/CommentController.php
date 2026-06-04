<?php
// CommentController.php - Regelt reacties op videos
// Verantwoordelijk voor:
// - Nieuwe reactie opslaan
// - Reactie verwijderen
// - Reactie bewerken
// Gebruikt: Comment model

session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';

requireLogin();

$commentModel = new Comment($pdo);
$action = $_GET['action'] ?? '';

// comment plaatsen
if ($action === 'post' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $video_id = (int) $_POST['video_id'];
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (!empty ($content)) {
        $commentModel->post($user_id, $video_id, $content);
    }

    redirect('/GitHub/StreamHive/video-platform/app/controllers/VideoController.php?action=show&id=' . $video_id);
}
?>
