<?php
// login.php - Inlogpagina
// Toont het inlogformulier met velden voor e-mail en wachtwoord
// Bij submit verwerkt AuthController de gegevens
// Toon een foutmelding als het inloggen mislukt
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login StreamHive</title>
</head>
<body>
    <div class="card">
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

            <form method="POST" action="/GitHub/StreamHive/video-platform/app/controllers/AuthController.php?action=login">
            <label>E-mailadres</label>
            <input type="email" name="email" placeholder="Your@email.com" required>
 
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
 
            <button type="submit">Login</button>
        </form>
 
    </div>
    
</body>
</html>