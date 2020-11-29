<?php
require_once '../../private/config.php';
require_once ABSOLUTE_ROOT . '/private/session.php';

$error = false;
$success = false;
$error_message = null;

if (isset($_POST['codice_fiscale'], $_POST['cognome'], $_POST['nome'], $_POST['indirizzo'], $_POST['telefono'])) {
	$codice_fiscale = htmlentities($_POST['codice_fiscale']);
	$cognome = htmlentities($_POST['cognome']);
	$nome = htmlentities($_POST['nome']);
	$indirizzo = htmlentities($_POST['indirizzo']);
	$telefono = htmlentities($_POST['telefono']);

	if (!preg_match('/^[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]$/', $codice_fiscale)) {
		$error = true;
		$error_message = 'Il codice fiscale inserito è invalido';
	} else if (!preg_match('/^\d{10}$/', $telefono)) {
		$error = true;
		$error_message = 'Il numero di telefono inserito è invalido';
	} else {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$result = $connection->query(
			"INSERT INTO `soci`(`codice_fiscale`, `cognome`, `nome`, `indirizzo`, `telefono`) 
		VALUES ('$codice_fiscale', '$cognome', '$nome', '$indirizzo', '$telefono')"
		);

		if (!$result)
			$error = true;
		else
			$success = true;

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
	<title>Car sharing - Nuova auto</title>
</head>

<body class="bg-secondary">
	<?php require_once ABSOLUTE_ROOT . '/private/header.php' ?>
	<div class="container-fluid px-0">
		<div class="row justify-content-center mb-md-4 mb-3">
			<div class="jumbotron jumbotron-fluid w-100 text-light shadow mb-0" style="background-color: #545d65;">
				<div class="row justify-content-center">
					<div class="col-md-auto col-10 text-center">
						<h1 class="display-4">Registrazione nuovo socio</h1>
						<p class="lead">Inserisci un nuovo socio completando il form con le sue informazioni.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-center mx-md-0 mx-3 mb-3">
			<ul class="col-md-auto col-12 list-group shadow pr-0">
				<li class="list-group-item p-3">
					<small>Inserire il codice fiscale del nuovo socio</small>
					<div id="codice-fiscale-container" class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">#</span>
						</div>
						<input type="text" name="codice_fiscale" class="form-control" placeholder="Codice fiscale" form="new-member-form" pattern="^[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]$" required>
					</div>
				</li>
				<li class="list-group-item p-3">
					<small>Inserire cognome e nome del nuovo socio</small>
					<input type="text" name="cognome" class="form-control mb-2" placeholder="Cognome" form="new-member-form" required>
					<input type="text" name="nome" class="form-control" placeholder="Nome" form="new-member-form" required>
				</li>
				<li class="list-group-item p-3">
					<small>Inserire l'indirizzo del nuovo socio</small>
					<input type="text" name="indirizzo" class="form-control" placeholder="Via ... n° ..." form="new-member-form" required>
				</li>
				<li class="list-group-item p-3">
					<small>Inserire il numero di telefono del nuovo socio</small>
					<div id="telefono-container" class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-telephone" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
								</svg>
							</span>
						</div>
						<input type="tel" name="telefono" class="form-control" placeholder="Numero di telefono" form="new-member-form" pattern="^\d{10}$" required>
					</div>
				</li>
				<li class="list-group-item p-3">
					<input id="submit-btn" type="submit" class="btn btn-primary btn-block" value="Inserisci" form="new-member-form" disabled>
				</li>
			</ul>
		</div>
	</div>
	<form id="new-member-form" action="./" method="POST" class="d-none"></form>
	<?php if ($error || $success) { ?>
		<div class="modal" id="error-modal">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="alert alert-danger mb-0">
						<span class="lead">Impossibile aggiungere il nuovo socio</span>
						<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal" id="success-modal">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="alert alert-success mb-0">
						<span class="lead">Socio registrato correttamente</span>
						<button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="<?= ROOT . '/js/new-member.js' ?>"></script>
	<?php if ($error || $success) { ?>
		<script type="text/javascript">
			$('#<?= $error ? 'error' : ($success ? 'success' : '') ?>-modal').modal('show');
		</script>
	<?php } ?>
</body>

</html>