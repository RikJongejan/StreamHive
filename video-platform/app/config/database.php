<?php
// database.php - Databaseverbinding via PDO
// Pas de gegevens hieronder aan naar jouw eigen database
// $pdo is daarna beschikbaar in al je models en controllers


$host     = 'localhost';
$dbname   = 'StreamHive';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
?>