<?php

function debug($variable){
	echo "<pre>";
	print_r($variable);
	echo "</pre>";
}

function str_random($length){
    $alphabet = "1234567890azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

function logged_only(){
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['auth'])){
        $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page";
        header('Location: login.php');
        exit();
    }
}

function reconnect_from_cookie(){
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_COOKIE['remember']) && !isset($_SESSION['auth'])){
        require_once 'db.php';

        $remember_token = $_COOKIE['remember'];
        $parts = explode('==', $remember_token);
        $user_id = $parts[0];

        if(!isset($pdo)){
            global $pdo;
        }

        $req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $req->execute([$user_id]);
        $user = $req->fetch();
        if($user) {
            $expected = $user_id."==".$user->remember_token.sha1($user_id.'ratonlaveurs');
            if ($expected == $remember_token){
                $_SESSION['auth'] = $user;
                setcookie('remember', $remember_token, time()+60*60*24*7);
            } else {
                setcookie('remember', NULL, -1);
            }
        } else {
            setcookie('remember', NULL, -1);
        }
    }
}