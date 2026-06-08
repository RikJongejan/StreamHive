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

// Zet een datum/tijd om in een korte "x geleden" tekst voor in de views
function timeAgo(string $datetime): string
{
    $seconds = time() - strtotime($datetime);

    if ($seconds < 60)    return 'zojuist';
    if ($seconds < 3600)  return floor($seconds / 60)    . ' min geleden';
    if ($seconds < 86400) return floor($seconds / 3600)  . ' uur geleden';
    if ($seconds < 604800)return floor($seconds / 86400) . ' dagen geleden';

    return date('d-m-Y', strtotime($datetime));
}

// Maakt korte aantallen leesbaar (1200 -> 1,2k) voor weergaven en abonnees
function formatCount(int $number): string
{
    if ($number >= 1000000) return round($number / 1000000, 1) . 'M';
    if ($number >= 1000)    return round($number / 1000, 1) . 'k';

    return (string) $number;
}
