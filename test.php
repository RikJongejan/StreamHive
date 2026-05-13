<?php
require_once 'app/config/database.php';
require_once 'app/models/Video.php';

$videoModel = new Video($pdo);
$passed = 0;
$failed = 0;

function test(string $naam, bool $resultaat): void {
    global $passed, $failed;
    if ($resultaat) {
        echo "✅ $naam<br>";
        $passed++;
    } else {
        echo "❌ $naam<br>";
        $failed++;
    }
}

echo "<h2>Video Model Tests</h2>";

// ============================================
// 1. upload()
// ============================================
echo "<h3>1. upload()</h3>";

$geupload = $videoModel->upload(1, 'Testvideo', 'Dit is een testbeschrijving', 'test.mp4', 'test.jpg');
test("Video uploaden", $geupload);

// Direct na upload ID ophalen
$nieuweId = (int)$pdo->lastInsertId();

// ============================================
// 2. getAll()
// ============================================
echo "<h3>2. getAll()</h3>";

$alle = $videoModel->getAll();
test("Alle videos ophalen geeft een array terug", is_array($alle));
test("Array is niet leeg — " . count($alle) . " video(s) gevonden", count($alle) > 0);

// ============================================
// 3. getById()
// ============================================
echo "<h3>3. getById()</h3>";

$video = $videoModel->getById((int)$nieuweId);
test("Bestaande video ophalen op ID", $video !== false);
test("ID klopt in resultaat", $video && (int)$video['id'] === (int)$nieuweId);

$nietBestaand = $videoModel->getById(99999);
test("Niet bestaand ID geeft false terug", $nietBestaand === false);

// ============================================
// 4. getByUser()
// ============================================
echo "<h3>4. getByUser()</h3>";

$userVideos = $videoModel->getByUser(1);
test("Videos ophalen van gebruiker geeft array terug", is_array($userVideos));
test("Array is niet leeg", count($userVideos) > 0);

$geenVideos = $videoModel->getByUser(99999);
test("Niet bestaande gebruiker geeft lege array terug", is_array($geenVideos) && count($geenVideos) === 0);

// ============================================
// 5. incrementViews()
// ============================================
echo "<h3>5. incrementViews()</h3>";

$viewsVoor = $videoModel->getById((int)$nieuweId)['views'];
$videoModel->incrementViews((int)$nieuweId);
$viewsNa = $videoModel->getById((int)$nieuweId)['views'];
test("Views ophogen", (int)$viewsNa === (int)$viewsVoor + 1);

// ============================================
// 6. getCategories()
// ============================================
echo "<h3>6. getCategories()</h3>";

// Voeg een testcategorie in
$pdo->prepare("INSERT INTO categories (video_id, name) VALUES (?, ?)")->execute([$nieuweId, 'Testcategorie']);

$categorieen = $videoModel->getCategories((int)$nieuweId);
test("Categorieen ophalen geeft array terug", is_array($categorieen));
test("Categorie is aanwezig", count($categorieen) > 0);
test("Categorienaam klopt", $categorieen[0]['name'] === 'Testcategorie');

// ============================================
// 7. delete()
// ============================================
echo "<h3>7. delete()</h3>";

$verwijderd = $videoModel->delete((int)$nieuweId);
test("Video verwijderen", $verwijderd);

$weg = $videoModel->getById((int)$nieuweId);
test("Video is echt weg uit de database", $weg === false);

// ============================================
// Resultaat
// ============================================
$totaal = $passed + $failed;
echo "<h3>Resultaat: $passed / $totaal geslaagd</h3>";
if ($failed > 0) {
    echo "<p>⚠️ $failed test(s) mislukt, controleer je Video.php of database.</p>";
} else {
    echo "<p>🎉 Alle tests geslaagd!</p>";
}
?>