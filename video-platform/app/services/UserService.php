<?php
// UserService.php - Service voor gebruikersbeheer
// Bevat de bedrijfslogica voor gebruikersprofielen:
// - Profiel ophalen
// - Profielgegevens bijwerken inclusief avatar-upload en validatie
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

    // Null betekent dat de gebruiker geen nieuwe avatar heeft gekozen; de huidige afbeelding blijft dan staan
    public function updateProfile(int $id, string $username, string $bio, ?array $imageFile, string $currentImage): array
    {
        if ($username === '') {
            return ['success' => false, 'error' => 'Gebruikersnaam mag niet leeg zijn.'];
        }

        // Naam mag niet al door iemand anders gebruikt worden
        $existing = $this->userModel->getByUsername($username);

        if ($existing) {
            if ((int) $existing['id'] !== $id) {
                return ['success' => false, 'error' => 'Deze gebruikersnaam is al bezet.'];
            }
        }

        // Avatar is optioneel: alleen vervangen als er een geldig bestand is geupload
        $imageName = $currentImage;

        if ($imageFile !== null) {
            if ($imageFile['error'] === UPLOAD_ERR_OK) {
                $allowedTypes     = ['image/jpeg', 'image/png', 'image/webp'];
                $imageType        = mime_content_type($imageFile['tmp_name']);
                $imageTypeAllowed = in_array($imageType, $allowedTypes);

                if ($imageTypeAllowed === false) {
                    return ['success' => false, 'error' => 'Avatar moet JPG, PNG of WebP zijn.'];
                }

                $uploadDirectory = UPLOADS_PATH . '/avatars';

                if (!is_dir($uploadDirectory)) {
                    mkdir($uploadDirectory, 0777, true);
                }

                $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
                $imageName = uniqid('avatar_', true) . '.' . $extension;
                move_uploaded_file($imageFile['tmp_name'], $uploadDirectory . '/' . $imageName);
            }
        }

        $updated = $this->userModel->updateProfile($id, $username, $bio, $imageName);

        if (!$updated) {
            return ['success' => false, 'error' => 'Opslaan is mislukt.'];
        }

        return ['success' => true, 'username' => $username];
    }
}
