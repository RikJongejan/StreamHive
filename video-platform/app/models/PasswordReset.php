<?php
// PasswordReset.php - Model voor wachtwoord reset verzoeken
// Beheert de tokens die verstuurd worden via e-mail:
// - Token aanmaken bij reset verzoek met generate()
// - Token valideren als gebruiker op link klikt met validate()
// - Token ongeldig maken na gebruik met invalidate()
// Werkt met de 'password_reset' tabel in de database
