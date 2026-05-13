<?php
// AuthController.php - Regelt alles rondom authenticatie
// Verantwoordelijk voor:
// - Inlogformulier verwerken
// - Registratieformulier verwerken
// - Uitloggen (sessie vernietigen)
// - Wachtwoord reset afhandelen
// Gebruikt: User model, PasswordReset model

session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$userModel = new User($pdo);
$error = '';
$action = $_GET['action'] ?? '';


// login code
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Fill in all the fields.';
    } else {
        $user = $userModel->login($email, $password);
        if ($user) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            header('Location: /github/streamhive/video-platform/views/index.php');
            exit;
        } else {
            $error = 'Invalid credentials';
        }
    }
}

// Altijd de view tonen als action=login (ook bij eerste bezoek en bij fouten)
    if ($action === 'login') {
        require_once __DIR__ . '/../../views/auth/login.php';
}

//register Code
if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    =   trim($_POST['email'] ?? '');
    $username =   trim($_POST['username'] ?? '');
    $password =   $_POST['password'] ?? '';
    $confirm  =   $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($username) || empty($password) || empty($confirm)) {
        $error = 'Fill in all the fields.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords dont match!';
    } elseif (strlen($password) < 8) {
        $error = 'password needs atleast 8 characters';
    } else {
        $result = $userModel->register($email, $username, $password);
        if ($result) {
            $user = $userModel->login($email, $password);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: /github/streamhive/video-platform/views/index.php');
            exit;
        } else {
            $error = 'Email or Username already in use.';
        }
    } 
}
if ($action === 'register') {
    require_once __DIR__ . '/../../views/auth/register.php';
}