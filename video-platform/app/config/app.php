<?php
// app.php - Algemene instellingen van de applicatie
// Stelt constanten in die overal beschikbaar zijn:
// - SITE_NAME: naam van de site
// - BASE_URL:  het enige ingangspunt (front controller) waar alle routes langs gaan
// - UPLOADS_URL/UPLOADS_PATH: locatie van geuploade bestanden (web + schijf)
// - VIEWS_PATH: map waar de view-bestanden staan

define('SITE_NAME', 'StreamHive');

// Front controller: elke pagina wordt aangeroepen via ?route=...
define('BASE_URL', '/GitHub/StreamHive/video-platform/public/index.php');

// Web-pad naar geuploade bestanden (gebruikt in views)
define('UPLOADS_URL', '/GitHub/StreamHive/video-platform/uploads');

// Web-pad naar de publieke statische bestanden (css, js, afbeeldingen/logo)
define('ASSETS_URL', '/GitHub/StreamHive/video-platform/public');

// Schijf-pad naar geuploade bestanden (gebruikt bij het opslaan)
define('UPLOADS_PATH', realpath(__DIR__ . '/../../uploads'));

// Map met de views
define('VIEWS_PATH', realpath(__DIR__ . '/../../views'));

// Maximale uploadgrootte voor video's (500 MB)
define('MAX_VIDEO_SIZE', 500 * 1024 * 1024);
