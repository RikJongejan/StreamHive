<?php
// auth.php - Authenticatiehulpfuncties
// Bevat hulpfuncties voor sessiecontrole:
// - Controleren of een gebruiker ingelogd is (isLoggedIn)
// - Toegang weigeren en doorverwijzen naar login als niet ingelogd (requireLogin)
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        redirect(route('auth/login'));
    }
}
