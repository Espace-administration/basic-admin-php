<?php

require_once "inc/functions.php";

if (!empty($_POST)){
	$errors = array();
    require_once "inc/db.php";

	if (empty($_POST['username']) || !preg_match('/^[A-Za-z0-9_]+$/', $_POST['username'])){
		$errors['username'] = "Le pseudo n'est pas valide !";
	} else {
        $req = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $req->execute([$_POST['username']]);
        $user = $req->fetch();
        if ($user){
            $errors['username'] = 'Ce pseudo est déjà pris';
        }
    }

	if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$errors['email'] = "Votre mail n'est pas valide !";
	} else {
        $req = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $req->execute([$_POST['email']]);
        $user = $req->fetch();
        if ($user){
            $errors['email'] = 'Cette email est déjà utilisé';
        }
    }

	if (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
		$errors['password'] = "Le password doit être valide !";
	}

    if (empty($errors)) {
        $req = $pdo->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token= ?");
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $token = str_random(60);

        $req->execute([$_POST['username'], $password, $_POST['email'], $token]);

        $user_id = $pdo->lastInsertId();
        $email = strip_tags($_POST['email']);

        $header = "From : ".$email;
        $sujet = "Confirmation de votre mail";
        $message = "Afin de valider votre compte, merci de cliquer sur ce lien\n\nhttp://".$_SERVER['SERVER_NAME']."/confirm.php?id=".$user_id."&token=".$token;

        mail($email, $sujet, $message, $header);
        $_SESSION['flash']['success'] = "Un email de confirmation vous a été envoyé !";

        header('Location: login.php');
        exit();
    }
}
?>

<?php require "inc/header.php"; ?>
<h1>S'inscrire</h1>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
    <p>Le formulaire n'a pas été rempli correctement !</p>
    <ul>
    <?php foreach($errors as $error): ?>
        <li><?= $error; ?></li>
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="" method="POST">
	<div class="form-group">
		<label for="">Pseudo</label>
		<input type="text" name="username" class="form-control" />
	</div>

	<div class="form-group">
		<label for="">Email</label>
		<input type="email" name="email" class="form-control" />
	</div>

	<div class="form-group">
		<label for="">Mot de passe</label>
		<input type="password" name="password" class="form-control" />
	</div>

	<div class="form-group">
		<label for="">Confirmer votre mot de passe</label>
		<input type="password" name="password_confirm" class="form-control" />
	</div>

	<button type="submit" class="btn btn-primary">M'inscrire</button>
</form>

<?php require "inc/footer.php"; ?>
