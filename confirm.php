<?php
$user_id = $_GET['id'];
$token = $_GET['token'];

require 'inc/db.php';

$req = $pdo->prepare('SELECT * FROM users WHERE id = ?');

$req->execute([$user_id]);
$user = $req->fetch();

session_start();

echo "1";

if ($user && $user->confirmation_token == $token) {
    $req = $pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?');
    $req->execute([$user_id]);

    $_SESSION['auth'] = $user;
    $_SESSION['flash']['success'] = "Votre compte a été validé";

    echo "2";

    header('Location: account.php');
} else {
    $_SESSION['flash']['danger'] = "Ce token n'est plus valide";

    echo "3";

    header('Location: login.php');
}