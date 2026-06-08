<?php
// helpers.php - Kleine hulpfuncties die overal gebruikt worden
// route($path, $params): bouwt een URL naar de front controller (?route=...)
// redirect($url): stuur gebruiker door naar een andere pagina
// sanitize($input): verwijder gevaarlijke tekens uit gebruikersinvoer
// setUserSession($user): sla gebruikersdata op in de sessie

// Bouwt een nette URL naar de front controller, bijv. route('video/show', ['id' => 5])
function route(string $path = '', array $params = []): string
{
    $url = BASE_URL . '?route=' . $path;

    foreach ($params as $key => $value) {
        $url .= '&' . $key . '=' . urlencode((string) $value);
    }

    return $url;
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function sanitize(string $input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Aparte functie om te voorkomen dat sessie-instelling dubbel staat bij login en registratie
function setUserSession(array $user): void
{
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];
}
