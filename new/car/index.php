<?php
require_once '../../private/config.php';
require_once ABSOLUTE_ROOT . '/private/session.php';

$error = false;
$success = false;
$error_message = null;

if (isset($_POST['targa'], $_POST['modello'], $_POST['marca'], $_POST['costo_giornaliero'])) {
	$targa = htmlentities($_POST['targa']);
	$modello = htmlentities($_POST['modello']);
	$marca = htmlentities($_POST['marca']);
	$costo_giornaliero = htmlentities($_POST['costo_giornaliero']);

	if (!preg_match('/^[A-Z]{2}\d{3}[A-Z]{2}$/', $targa)) {
		$error = true;
		$error_message = 'La targa inserita è invalida';
	} else {
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$result = $connection->query(
			"INSERT INTO `auto`(`targa`, `marca`, `modello`, `costo_giornaliero`) 
			VALUES ('$targa', '$marca', '$modello', '$costo_giornaliero')"
		);

		if (!$result) {
			$error = true;
			$error_message = 'Impossibile aggiungere l\'auto';
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
						<h1 class="display-4">Inserimento nuove auto</h1>
						<p class="lead">Inserisci una nuova auto completando il form.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-center mx-md-0 mx-3 mb-3">
			<ul class="col-md-auto col-12 list-group shadow pr-0">
				<li class="list-group-item p-3">
					<small>Inserire la targa della nuova auto</small>
					<div id="targa-container" class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">#</span>
						</div>
						<input type="text" name="targa" class="form-control" placeholder="AA000AA" form="new-car-form" pattern="^[A-Z]{2}\d{3}[A-Z]{2}$" required>
					</div>
				</li>
				<li class="list-group-item p-3">
					<small>Inserire marca e modello della nuova auto</small>
					<input type="text" name="marca" class="form-control mb-2" placeholder="Marca" form="new-car-form" required>
					<input type="text" name="modello" class="form-control" placeholder="Modello" form="new-car-form" required>
				</li>
				<li class="list-group-item p-3">
					<small>Inserire il costo giornaliero per la nuova auto</small>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">€</span>
						</div>
						<input type="number" name="costo_giornaliero" class="form-control" placeholder="Costo giornaliero" form="new-car-form" min="1" step="0.01" required>
					</div>
				</li>
				<li class="list-group-item p-3">
					<input id="submit-btn" type="submit" class="btn btn-primary btn-block" value="Inserisci" form="new-car-form" disabled>
				</li>
			</ul>
		</div>
	</div>
	<form id="new-car-form" action="./" method="POST" class="d-none"></form>
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
						<span class="lead">Auto aggiunta correttamente</span>
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
	<script src="<?= ROOT . '/js/new-car.js' ?>"></script>
	<?php if ($error || $success) { ?>
		<script type="text/javascript">
			$('#<?= $error ? 'error' : ($success ? 'success' : '') ?>-modal').modal('show');
		</script>
	<?php } ?>
</body>

</html>