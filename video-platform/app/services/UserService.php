<?php
// UserService.php - Logica laag voor gebruikersprofielen
// Regelt het ophalen van een profiel en het bijwerken ervan (naam, bio, avatar).
// De authenticatie (in/uitloggen, registreren) zit bewust apart in AuthService;
// deze service gaat alleen over het profiel zelf.

class UserService
{
    private UserModel $userModel;

    public function __construct(PDO $pdo)
    {
        $this->userModel = new UserModel($pdo);
    }

    public function getProfile(int $id): array|false
    {
        return $this->userModel->getById($id);
    }

    // Werkt het profiel bij. Geeft array terug met 'success' en bij fout 'error'.
    // $imageFile is het $_FILES-element van de avatar (mag null zijn als er niets wijzigt).
    public function updateProfile(int $id, string $username, string $bio, ?array $imageFile, string $currentImage): array
    {
        if ($username === '') {
            return ['success' => false, 'error' => 'Gebruikersnaam mag niet leeg zijn.'];
        }

        // Naam mag niet al door iemand anders gebruikt worden
        $existing = $this->userModel->getByUsername($username);
        if ($existing && (int) $existing['id'] !== $id) {
            return ['success' => false, 'error' => 'Deze gebruikersnaam is al bezet.'];
        }

        // Avatar is optioneel: alleen vervangen als er een geldig bestand is geupload
        $imageName = $currentImage;

        if ($imageFile !== null && $imageFile['error'] === UPLOAD_ERR_OK) {
            if (!in_array(mime_content_type($imageFile['tmp_name']), ['image/jpeg', 'image/png', 'image/webp'])) {
                return ['success' => false, 'error' => 'Avatar moet JPG, PNG of WebP zijn.'];
            }

            $dir = UPLOADS_PATH . '/avatars';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('avatar_', true) . '.' . $extension;
            move_uploaded_file($imageFile['tmp_name'], $dir . '/' . $imageName);
        }

        $ok = $this->userModel->updateProfile($id, $username, $bio, $imageName);

        if (!$ok) {
            return ['success' => false, 'error' => 'Opslaan is mislukt.'];
        }

        return ['success' => true, 'username' => $username];
    }
}
