<?php
// helpers.php - Kleine hulpfuncties die overal gebruikt worden
// redirect($url): stuur gebruiker door naar een andere pagina
// sanitize($input): verwijder gevaarlijke tekens uit gebruikersinvoer
// setUserSession($user): sla gebruikersdata op in de sessie

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function sanitize(string $input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Extracted to avoid repeating session assignment in every auth flow (login + register)
function setUserSession(array $user): void
{
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];
}
