<?php
require_once 'app/config/database.php';
require_once 'app/models/User.php';

$userModel = new User($pdo);

// Test 1: gebruiker registreren
$result = $userModel->register('test@test.com', 'wachtwoord123');
echo $result ? "✅ Register werkt" : "❌ Register mislukt";
echo "<br>";

// Test 2: gebruiker ophalen op e-mail
$user = $userModel->getByEmail('test@test.com');
echo $user ? "✅ getByEmail werkt" : "❌ getByEmail mislukt";
echo "<br>";

// Test 3: inloggen
$login = $userModel->login('test@test.com', 'wachtwoord123');
echo $login ? "✅ Login werkt" : "❌ Login mislukt";
echo "<br>";

// Test 4: gebruiker ophalen op ID
$user = $userModel->getById(1);
echo $user ? "✅ getById werkt" : "❌ getById mislukt";
echo "<br>";