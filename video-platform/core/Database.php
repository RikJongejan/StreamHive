<?php
// Database.php - Zet de databaseverbinding op via PDO
// Gebruikt het singleton-patroon: er is maar EEN verbinding voor de hele request.
// De inloggegevens komen uit app/config/database.php
// Aanroepen met: Database::getConnection()

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
