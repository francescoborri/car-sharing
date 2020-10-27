<?php
require_once '../private/config.php';

$error = [
	'<div class="alert alert-success">Utente registrato correttamente, <a href="' . ROOT . '/login/">accedi</a>.</div>',
	'<div class="alert alert-danger">Le password non corrispondono.</div>',
	'<div class="alert alert-danger">Utente già esistente.</div>',
	'<div class="alert alert-danger">Impossibile aggiungere l\'utente.</div>'
];
$message = -1;

if (isset($_POST['username'], $_POST['password'], $_POST['password_confirm'])) {
	$username = htmlentities($_POST['username']);
	$password = htmlentities($_POST['password']);
	$password_confirm = htmlentities($_POST['password_confirm']);
	if ($password != $password_confirm)
		$message = 1;
	else {
		$encrypted_password = crypt($password, crypt($username, DB_SALT));
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$result = $connection->query("SELECT * FROM `utenti` WHERE `username` = '$username'");
		if (!$result || $result->num_rows != 0)
			$message = 2;
		else
			$message = $connection->query("INSERT INTO `utenti` (`username`, `password`) VALUES ('$username', '$encrypted_password')") ? 0 : 3;
		$result->free();
		$connection->close();
	}
}
?>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" href="<?= ROOT . '/css/default.css' ?>">
	<title>Signin</title>
</head>

<body style="background-color: #f5f5f5">
	<div class="container-fluid">
		<div class="row justify-content-center mb-md-4">
			<div class="jumbotron jumbotron-fluid w-100">
				<div class="row align-items-center justify-content-md-around justify-content-center">
					<div class="col-auto text-center">
						<h1 class="display-4">Registrazione utente</h1>
						<p class="lead">Inserisci username e password per registrarti.</p>
					</div>
				</div>
			</div>
		</div>
		<form method="POST" action="./">
			<?php if ($message != -1) { ?>
				<div class="row justify-content-center">
					<div class="col-md-4 col-sm-7 col-10">
						<?= $error[$message] ?>
					</div>
				</div>
			<?php } ?>
			<div class="row justify-content-center">
				<div class="col-md-4 col-sm-7 col-10 form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="addon-wrapping">@</span>
					</div>
					<input type="text" name="username" class="form-control" placeholder="Nome utente" <?= isset($_POST['username']) ? 'value="' . $_POST['username'] . '"' : '' ?> required>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-4 col-sm-7 col-10 form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="addon-wrapping">
							<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-key" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z" />
								<path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
							</svg>
						</span>
					</div>
					<input type="password" name="password" class="form-control" placeholder="Password" minlength="8" required>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-4 col-sm-7 col-10 form-group input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="addon-wrapping">
							<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-key" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z" />
								<path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
							</svg>
						</span>
					</div>
					<input type="password" name="password_confirm" class="form-control" placeholder="Conferma password" required>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-4 col-sm-7 col-10 form-group">
					<input type="submit" class="btn btn-primary btn-block" value="Registrati">
				</div>
			</div>
		</form>
	</div>
	<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>