<?php
require_once '../private/config.php';

$messages = [
	'<div class="alert alert-success mb-0">
		<span class="lead">Utente registrato correttamente, <a href="' . ROOT . '/login/">accedi</a>.</span>
		<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>',
	'<div class="alert alert-danger mb-0">
		<span class="lead">Le password non corrispondono. Riprova.</span>
		<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>',
	'<div class="alert alert-danger mb-0">
		<span class="lead">Username già esistente. Riprova.</span>
		<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>',
	'<div class="alert alert-danger mb-0">
		<span class="lead">Impossibile aggiungere l\'utente. Riprova.</span>
		<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>'
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
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<link rel="stylesheet" href="<?= ROOT . '/css/default.css' ?>">
	<style>
		#show-hide-password-a,
		#show-hide-password-a:hover {
			color:#333;
		}
	</style>
	<title>Car sharing - Registrati</title>
</head>

<body class="bg-secondary">
	<?php require_once ABSOLUTE_ROOT . '/private/header.php' ?>
	<div class="container-fluid px-0">
		<div class="row justify-content-center mb-md-4 mb-3">
			<div class="jumbotron jumbotron-fluid w-100 text-light shadow mb-0" style="background-color: #545d65;">
				<div class="row justify-content-center">
					<div class="col-md-auto col-10 text-center">
						<h1 class="display-4">Car sharing</h1>
						<p class="lead">Completa il form sottostante per registrarti.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-center mx-md-0 mx-3 mb-3">
			<ul class="col-md-auto col-12 list-group shadow pr-0">
				<li class="list-group-item p-3">
					<small>Inserisci un username</small>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">@</span>
						</div>
						<input type="text" name="username" class="form-control" placeholder="Username" form="signin-form" <?= isset($_POST['username']) ? "value=\"{$_POST['username']}\"" : '' ?> required>
					</div>
				</li>
				<li class="list-group-item p-3">
					<small>Inserisci una password contenente almeno 8 caratteri, e che contenga maiuscole, minuscole e numeri</small>
					<div id="password-container" class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-key" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z" />
									<path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
								</svg>
							</span>
						</div>
						<input type="password" name="password" class="form-control" placeholder="Password" form="signin-form" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<a href="" id="show-hide-password-a">
									<i id="show-hide-password-icon" class="fa fa-eye-slash" aria-hidden="true"></i>
								</a>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item p-3">
					<small>Ripeti la password</small>
					<div id="password-confirm-container" class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-key" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z" />
									<path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
								</svg>
							</span>
						</div>
						<input type="password" name="password_confirm" class="form-control" placeholder="Ripeti password" form="signin-form" required>
					</div>
				</li>
				<li class="list-group-item p-3">
					<input id="submit-btn" type="submit" class="btn btn-primary btn-block" value="Registrati" form="signin-form" disabled>
				</li>
			</ul>
		</div>
	</div>
	<form id="signin-form" action="./" method="POST" class="d-none"></form>
	<?php if ($message != -1) { ?>
		<div class="modal" id="signin-error-modal">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<?= $messages[$message] ?>
				</div>
			</div>
		</div>
	<?php } ?>
	<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="<?= ROOT . '/js/signin.js' ?>"></script>
	<?php if ($message != -1) { ?>
		<script type="text/javascript">
			$('#signin-error-modal').modal('show');
		</script>
	<?php } ?>
</body>

</html>