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

?>
