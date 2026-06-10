<?php
// Database.php - PDO singleton voor de databaseverbinding
// Zorgt ervoor dat er maar één verbinding per request bestaat:
// - Laadt configuratie uit app/config/database.php
// - Bouwt een PDO-instantie met foutmeldingen en FETCH_ASSOC als standaard
// - Hergebruikt de bestaande verbinding bij elke aanroep
class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../app/config/database.php';

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8";

            try {
                self::$connection = new PDO($dsn, $config['username'], $config['password']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Databaseverbinding mislukt: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
