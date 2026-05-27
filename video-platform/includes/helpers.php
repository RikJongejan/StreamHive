<?php
// helpers.php - Kleine hulpfuncties die overal gebruikt worden
// redirect($url): stuur gebruiker door naar een andere pagina
// sanitize($input): verwijder gevaarlijke tekens uit gebruikersinvoer
// formatDate($date): zet timestamp om naar leesbare datum
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function sanitiza(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

