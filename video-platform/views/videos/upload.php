<?php
// upload.php - Pagina voor het uploaden van een video
// Formulier met: titel, beschrijving, videobestand, thumbnail, categorie
// Alleen toegankelijk voor ingelogde gebruikers (check met requireLogin())
// Bij submit verwerkt VideoController het bestand en slaat het op

session_start();
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Video uploaden - StreamHive</title>
</head>
<body>
    <h1>Video uploaden</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <form method="POST" action="/GitHub/StreamHive/video-platform/app/controllers/VideoController.php?action=upload" enctype="multipart/form-data">

        <label>Titel</label><br>
        <input type="text" name="title" required><br><br>

        <label>beschrijving</label><br>
        <textarea name="description" rows="4"></textarea><br><br>

        <label>Videobestand</label><br>
        <input type="file" name="video" accept="video/*" required><br><br>

        <label>thumbnail</label><br>
        <input type="file" name="thumbnail" accept="image/*"><br><br>

        <button type="sunmit">Uploaden</button>
    </form>
    
</body>
</html>