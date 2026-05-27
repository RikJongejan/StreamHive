<?php
// auth.php - Hulpfuncties voor authenticatie
// isLoggedIn(): geeft true terug als gebruiker ingelogd is
// requireLogin(): stuurt niet-ingelogde gebruiker naar loginpagina

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: /GitHub/StreamHive/video-platform/app/controllers/AuthController.php?action=login');
        exit;
    }
}