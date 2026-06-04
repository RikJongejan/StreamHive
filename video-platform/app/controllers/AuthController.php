<?php
// AuthController.php - Regelt alles rondom authenticatie
// Verantwoordelijk voor:
// - Inlogformulier verwerken
// - Registratieformulier verwerken
// - Uitloggen (sessie vernietigen)
// - Wachtwoord reset afhandelen
// Gebruikt: UserModel, PasswordResetModel

session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../includes/helpers.php';

$userModel = new UserModel($pdo);
$error = '';
$action = $_GET['action'] ?? '';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST')
{
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password))
    {
        $error = 'Fill in all the fields.';
    }
    else
    {
        $user = $userModel->login($email, $password);

        if ($user)
        {
            setUserSession($user);
            redirect('/GitHub/StreamHive/video-platform/app/controllers/VideoController.php?action=index');
        }
        else
        {
            $error = 'Invalid credentials.';
        }
    }
}

if ($action === 'login')
{
    require_once __DIR__ . '/../../views/auth/login.php';
}

if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST')
{
    $email    = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($username) || empty($password) || empty($confirm))
    {
        $error = 'Fill in all the fields.';
    }
    elseif ($password !== $confirm)
    {
        $error = 'Passwords do not match.';
    }
    elseif (strlen($password) < 8)
    {
        $error = 'Password needs at least 8 characters.';
    }
    else
    {
        $result = $userModel->register($email, $username, $password);

        if ($result)
        {
            $user = $userModel->login($email, $password);
            setUserSession($user);
            redirect('/GitHub/StreamHive/video-platform/app/controllers/VideoController.php?action=index');
        }
        else
        {
            $error = 'Email or username already in use.';
        }
    }
}

if ($action === 'register')
{
    require_once __DIR__ . '/../../views/auth/register.php';
}

if ($action === 'logout')
{
    session_destroy();
    redirect('/GitHub/StreamHive/video-platform/app/controllers/AuthController.php?action=login');
}
