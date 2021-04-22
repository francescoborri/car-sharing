<?php
require_once '../../private/config.php';
require_once ABSOLUTE_ROOT . '/private/session.php';

$error = false;
$success = false;
$error_message = null;

$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$cars_result = $connection->query('SELECT * FROM `auto`');
if (!$cars_result) {
	echo $connection->error;
	die();
}

$cars = [];
while ($car = $cars_result->fetch_assoc())
	$cars[] = $car;

$members_result = $connection->query('SELECT * FROM `soci`');
if (!$members_result) {
	echo $connection->error;
	die();
}

$members = [];
while ($member = $members_result->fetch_assoc())
	$members[] = $member;

if (isset($_POST['targa'], $_POST['codice_fiscale'], $_POST['data_inizio'], $_POST['data_fine'])) {
	$targa = htmlentities($_POST['targa']);
	$codice_fiscale = htmlentities($_POST['codice_fiscale']);
	$data_inizio = htmlentities($_POST['data_inizio']);
	$data_fine = htmlentities($_POST['data_fine']);

	$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$targa_check_result = $connection->query(
		"SELECT *
		FROM `auto`
		WHERE
			`targa` = '$targa' AND
			`targa` NOT IN (
				SELECT `targa`
				FROM `noleggi`
				WHERE
					IF(`noleggi`.`data_restituzione` is NULL,
						'$data_inizio' < GREATEST(`noleggi`.`data_fine`, CURRENT_DATE()) AND '$data_fine' > `noleggi`.`data_inizio`,
						'$data_inizio' < `noleggi`.`data_restituzione` AND '$data_fine' > `noleggi`.`data_inizio`)
			)"
	);

	if (!preg_match('/^[A-Z]{2}\d{3}[A-Z]{2}$/', $targa)) {
		$error = true;
		$error_message = 'La targa inserita è invalida';
	} else if (!preg_match('/^[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]$/', $codice_fiscale)) {
		$error = true;
		$error_message = 'Il codice fiscale inserito è invalido';
	} else if ($data_fine < $data_inizio) {
		$error = true;
		$error_message = 'Le date inserite sono errate';
	} else if ($targa_check_result->num_rows != 1) {
		$error = true;
		$error_message = 'L\'auto desiderata è già noleggiata nel periodo selezionato';
	} else {
		$result = $connection->query(
			"INSERT INTO `noleggi`(`targa`, `codice_fiscale`, `data_inizio`, `data_fine`) 
			VALUES ('$targa', '$codice_fiscale', '$data_inizio', '$data_fine')"
		);

		if (!$result) {
			$error = true;
			$error_message = 'Impossibile prenotare il nuovo noleggio';
		} else
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
						<h1 class="display-4">Prenotazione noleggio</h1>
						<p class="lead">Prenota un nuovo noleggio completando il form.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-center mx-md-0 mx-3 mb-3">
			<ul class="col-md-auto col-12 list-group shadow pr-0">
				<li class="list-group-item p-3">
					<small>Scegli la targa dell'auto da prenotare</small>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">#</span>
						</div>
						<select name="targa" class="custom-select" form="new-hire-form" required>
							<option disabled hidden selected>Seleziona una targa</option>
							<?php foreach ($cars as $current) { ?>
								<option value="<?= $current['targa'] ?>"><?= "{$current['targa']} - {$current['modello']}" ?></option>
							<?php } ?>
						</select>
					</div>
				</li>
				<li class="list-group-item p-3">
					<small>Seleziona il socio che vuole prenotare l'auto</small>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
								</svg>
							</span>
						</div>
						<select name="codice_fiscale" class="custom-select" form="new-hire-form" required>
							<option disabled hidden selected>Seleziona una socio</option>
							<?php foreach ($members as $current) { ?>
								<option value="<?= $current['codice_fiscale'] ?>"><?= "{$current['nome']} {$current['cognome']}" ?></option>
							<?php } ?>
						</select>
					</div>
				</li>
				<li class="list-group-item p-3">
					<small>Inserisci la data di inizio del noleggio</small>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">Inizio</span>
						</div>
						<input type="date" name="data_inizio" class="form-control" form="new-hire-form" required>
					</div>
				</li>
				<li class="list-group-item p-3">
					<small>Inserisci la data di fine del noleggio</small>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">Fine</span>
						</div>
						<input type="date" name="data_fine" class="form-control" form="new-hire-form" required>
					</div>
				</li>
				<li class="list-group-item p-3">
					<input id="submit-btn" type="submit" class="btn btn-primary btn-block" value="Inserisci" form="new-hire-form" disabled>
				</li>
			</ul>
		</div>
	</div>
	<form id="new-hire-form" action="./" method="POST" class="d-none"></form>
	<?php if ($error || $success) { ?>
		<div class="modal" id="error-modal">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="alert alert-danger mb-0">
						<span class="lead"><?= $error_message ?></span>
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
						<span class="lead">Noleggio prenotato correttamente</span>
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
	<script src="<?= ROOT . '/js/new-hire.js' ?>"></script>
	<?php if ($error || $success) { ?>
		<script type="text/javascript">
			$('#<?= $error ? 'error' : ($success ? 'success' : '') ?>-modal').modal('show');
		</script>
	<?php } ?>
</body>

</html>