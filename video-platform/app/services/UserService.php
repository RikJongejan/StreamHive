<?php
class UserService
{
    private UserModel $userModel;

    // __construct() wordt automatisch aangeroepen zodra je 'new UserService($pdo)' schrijft
    public function __construct(PDO $pdo)
    {
        $this->userModel = new UserModel($pdo);
    }

    public function getProfile(int $id): array|false
    {
        return $this->userModel->getById($id);
    }

    // Null betekent dat de gebruiker geen nieuwe avatar heeft gekozen; de huidige afbeelding blijft dan staan
    public function updateProfile(int $id, string $username, string $bio, ?array $imageFile, string $currentImage): array
    {
        if ($username === '') {
            return ['success' => false, 'error' => 'Username cannot be empty.'];
        }

        // Naam mag niet al door iemand anders gebruikt worden
        $existing = $this->userModel->getByUsername($username);

        if ($existing) {
            if ((int) $existing['id'] !== $id) {
                return ['success' => false, 'error' => 'This username is already taken.'];
            }
        }

        // Avatar is optioneel: alleen vervangen als er een geldig bestand is geupload
        $imageName = $currentImage;

        if ($imageFile !== null) {
            if ($imageFile['error'] === UPLOAD_ERR_OK) { // UPLOAD_ERR_OK betekent dat het bestand zonder fouten is geüpload
                $allowedTypes     = ['image/jpeg', 'image/png', 'image/webp'];
                $imageType        = mime_content_type($imageFile['tmp_name']); // mime_content_type() controleert het echte bestandstype op basis van de bestandsinhoud (veiliger dan de extensie)
                $imageTypeAllowed = in_array($imageType, $allowedTypes); // in_array() controleert of het type in de lijst van toegestane types zit

                if ($imageTypeAllowed === false) {
                    return ['success' => false, 'error' => 'Avatar must be JPG, PNG or WebP.'];
                }

                $uploadDirectory = UPLOADS_PATH . '/avatars';

                if (!is_dir($uploadDirectory)) { // is_dir() controleert of de map bestaat
                    mkdir($uploadDirectory, 0777, true); // mkdir() maakt de map aan (0777 = rechten, true = ook tussenliggende mappen aanmaken)
                }

                $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION); // pathinfo() haalt de bestandsextensie op (bijv. "jpg")
                $imageName = uniqid('avatar_', true) . '.' . $extension;       // uniqid() genereert een unieke naam op basis van de tijd zodat bestanden elkaar niet overschrijven
                move_uploaded_file($imageFile['tmp_name'], $uploadDirectory . '/' . $imageName); // move_uploaded_file() verplaatst het tijdelijk opgeslagen bestand naar de definitieve locatie
            }
        }

        $updated = $this->userModel->updateProfile($id, $username, $bio, $imageName);

        if (!$updated) {
            return ['success' => false, 'error' => 'Failed to save.'];
        }

        return ['success' => true, 'username' => $username];
    }
}
