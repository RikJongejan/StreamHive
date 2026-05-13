<?php
// register.php - Registratiepagina
// Toont het registratieformulier voor nieuwe gebruikers
// Velden: e-mail, wachtwoord, wachtwoord bevestigen
// Bij submit verwerkt AuthController de gegevens
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="card">
        <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

         <form method="POST" action="/github/streamhive/video-platform/app/controllers/AuthController.php?action=register">
            <label>E-mailadres</label>
            <input type="email" name="email" placeholder="jouw@email.com" required>
 
            <label>Gebruikersnaam</label>
            <input type="text" name="username" placeholder="jouwusername" required>
 
            <label>Wachtwoord</label>
            <input type="password" name="password" placeholder="Minimaal 8 tekens" required>
 
            <label>Wachtwoord bevestigen</label>
            <input type="password" name="confirm_password" placeholder="••••••••" required>
 
            <button type="submit">Account aanmaken</button>
        </form>

        <div class="link">
            Al een account? <a href="/GitHub/StreamHive/video-platform/app/controllers/AuthController.php?action=login">Inloggen</a>
        </div>

    </div>
</body>
</html>