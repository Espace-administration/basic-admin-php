<?php
require_once 'inc/functions.php';
logged_only();

if (!empty($_POST)){
    if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
        $_SESSION['flash']['danger'] = "Les password ne correspondent pas";
    } else {
        $user_id = $_SESSION['auth']->id;
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        require_once 'inc/db.php';
        $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$password, $user_id]);
        $_SESSION['flash']['success'] = "Votre mot de passe a bien été mise à jour";
    }
}

require "inc/header.php";
?>

    <h1>Votre compte</h1>
    <h2>Bonjour <?= $_SESSION['auth']->username; ?></h2>

    <form action="" method="post">
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Changer votre password" />
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password_confirm" placeholder="Confirmation du password" />
        </div>
        <button class="btn btn-primary">Changer mon mot de passe</button>
    </form>

<?php require "inc/footer.php"; ?>