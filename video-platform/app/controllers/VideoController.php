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
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';

requireLogin();

$videoModel = new Video($pdo);
$error = '';
$action = $_GET['action'] ?? '';

if ($action === 'upload') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = sanitiza($_POST['title'] ?? '');
    $description = sanitiza($_POST['description'] ?? '');
    $user_id = $_SESSION['user_id'];

    //veiligheid
    $allowedVideoType = 
    }
}