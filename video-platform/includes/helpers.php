<?php
// helpers.php - Algemene hulpfuncties voor de hele applicatie
// Bevat globale hulpfuncties die overal in de applicatie gebruikt worden:
// - route(): URL bouwen naar de front controller
// - redirect(): doorverwijzen naar een URL
// - sanitize(): invoer opschonen met htmlspecialchars
// - setUserSession(): sessievariabelen instellen na inloggen of registreren
// - timeAgo(): datum omzetten naar "x geleden" tekst
// - formatCount(): getal omzetten naar leesbare notatie (1,2k / 3,4M)

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

function timeAgo(string $datetime): string
{
    $seconds = time() - strtotime($datetime);

    if ($seconds < 60) {
        return 'zojuist';
    } elseif ($seconds < 3600) {
        $minutes = floor($seconds / 60);
        return $minutes . ' min geleden';
    } elseif ($seconds < 86400) {
        $hours = floor($seconds / 3600);
        return $hours . ' uur geleden';
    } elseif ($seconds < 604800) {
        $days = floor($seconds / 86400);
        return $days . ' dagen geleden';
    } else {
        return date('d-m-Y', strtotime($datetime));
    }
}

function formatCount(int $number): string
{
    if ($number >= 1000000) {
        return round($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return round($number / 1000, 1) . 'k';
    } else {
        return (string) $number;
    }
}
