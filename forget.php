<?php

require_once "inc/functions.php";

if (!empty($_POST) && !empty($_POST['email'])){
    require_once "inc/db.php";
    require_once "inc/functions.php";

    $req = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
    $req->execute([$_POST['email']]);

    $user = $req->fetch();
    if ($user){
        session_start();
        $reset_token = str_random(60);
        $pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?')->execute([$reset_token, $user->id]);

        $_SESSION['auth'] = $user;
        $_SESSION['flash']['success'] = 'Le rappel du mot de passe a été envoyé par mail';

        $header = "From : ".$email;
        $sujet = "[http://".$_SERVER['SERVER_NAME']."/] Reset du password";
        $message = "Pour réinitialiser votre mot de passe, merci de cliquer sur ce lien\n\nhttp://".$_SERVER['SERVER_NAME']."/reset.php?id={$user->id}&token=".$reset_token;

        mail($email, $sujet, $message, $header);
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['flash']['danger'] = 'Aucun compte ne correspond à cet email !';
    }
}
?>

<?php require "inc/header.php"; ?>
<h1>Mot de passe oublié</h1>

<form action="" method="POST">
    <div class="form-group">
        <label for="">E-mail</label>
        <input type="email" name="email" class="form-control" />
    </div>

    <button type="submit" class="btn btn-primary">Renouveller le mot de passe</button>
</form>

<?php require "inc/footer.php"; ?>
