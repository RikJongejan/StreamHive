<?php
class Helpers
{
    public static function route(string $path = '', array $params = []): string
    {
        $url = BASE_URL . '?route=' . $path;

        foreach ($params as $key => $value) {
            $url .= '&' . $key . '=' . urlencode((string) $value); // urlencode() maakt een waarde URL-veilig (spaties worden %20, & wordt %26 etc.)
        }

        return $url;
    }

    public static function redirect(string $url): void
    {
        header('Location: ' . $url); // header() stuurt een HTTP-header naar de browser — 'Location' zorgt voor een doorstuur (redirect)
        exit; // exit stopt de PHP-uitvoering zodat er geen code meer na de redirect loopt
    }

    public static function sanitize(string $input): string
    {
        // htmlspecialchars() zet gevaarlijke HTML-tekens om (< wordt &lt;) — bescherming tegen XSS-aanvallen
        // trim() verwijdert spaties en enters aan het begin en einde van de invoer
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function setUserSession(array $user): void
    {
        // $_SESSION slaat gegevens op die beschikbaar blijven tijdens de hele browsersessie
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];
    }

    public static function timeAgo(string $datetime): string
    {
        $seconds = time() - strtotime($datetime); // time() = huidige tijd als getal (seconden sinds 1970), strtotime() zet een datum-string om naar datzelfde getal

        if ($seconds < 60) {
            return 'zojuist';
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60); // floor() rondt altijd naar beneden af (bijv. 2.9 → 2)
            return $minutes . ' min geleden';
        } elseif ($seconds < 86400) {
            $hours = floor($seconds / 3600);
            return $hours . ' uur geleden';
        } elseif ($seconds < 604800) {
            $days = floor($seconds / 86400);
            return $days . ' dagen geleden';
        } else {
            return date('d-m-Y', strtotime($datetime)); // date() formatteert een timestamp naar een leesbare datum (d=dag, m=maand, Y=jaar)
        }
    }

    public static function formatCount(int $number): string
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M'; // round() rondt af op 1 decimaal (bijv. 1.234M → 1.2M)
        } elseif ($number >= 1000) {
            return round($number / 1000, 1) . 'k';
        } else {
            return (string) $number;
        }
    }
}
