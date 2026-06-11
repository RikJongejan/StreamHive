<?php
class Auth
{
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']); // isset() controleert of de variabele bestaat en niet null is
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) { // self:: roept een andere static methode aan in dezelfde klasse
            Helpers::redirect(Helpers::route('auth/login'));
        }
    }
}
