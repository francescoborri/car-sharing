<?php
require_once '../../private/config.php';
require_once ABSOLUTE_ROOT . '/private/session.php';
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!isset($_GET['type']) || $_GET['type'] == 'all') {
	$title= 'Tutti i noleggi';
	$description = 'Stai visualizzando lo storico di tutti i noleggi.';
	$result = $connection->query(
		"SELECT *
		FROM `noleggi`
		ORDER BY `noleggi`.`data_inizio`"
	);
} else if ($_GET['type'] == 'late') {
	$title= 'Noleggi in ritardo';
	$description = 'Stai visualizzando i noleggi in ritardo.';
	$result = $connection->query(
		"SELECT *
		FROM `noleggi`
		WHERE 
			`noleggi`.`data_restituzione` IS NULL AND 
			`noleggi`.`data_fine` < CURRENT_DATE()
		ORDER BY `noleggi`.`data_inizio`"
	);
} else if ($_GET['type'] == 'closed') {
	$title= 'Noleggi conclusi';
	$description = 'Stai visualizzando lo storico dei noleggi conclusi.';
	$result = $connection->query(
		"SELECT *
		FROM `noleggi`
		WHERE `noleggi`.`data_restituzione` < CURRENT_DATE()
		ORDER BY `noleggi`.`data_inizio`"
	);
} else if ($_GET['type'] == 'future') {
	$title= 'Noleggi programmati';
	$description = 'Stai visualizzando i noleggi programmati per il futuro.';
	$result = $connection->query(
		"SELECT *
		FROM `noleggi`
		WHERE `noleggi`.`data_inizio` > CURRENT_DATE()
		ORDER BY `noleggi`.`data_inizio`"
	);
} else
	header('Location: ' . ROOT . '/view/hire');

if (!$result) {
	echo "Errore: " . $connection->error;
	die();
}
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" href="<?= ROOT . '/css/default.css' ?>">
	<title>Car sharing - Noleggi</title>
</head>

<body class="bg-secondary">
	<div class="container-fluid px-0">
		<?php require_once ABSOLUTE_ROOT . '/private/header.php' ?>
		<div class="row justify-content-center">
			<div class="jumbotron jumbotron-fluid w-100 mb-3 text-light shadow" style="background-color: #545d65;">
				<div class="row justify-content-around">
					<div class="col-md-auto col-10 text-center my-auto">
						<h1 class="display-4"><?= $title ?></h1>
						<p class="lead text-break"><?= $description ?></p>
					</div>
					<div class="col-auto d-flex align-items-center p-0 m-0">
						<div class="btn-group-vertical shadow-lg">
							<div class="btn-group btn-block">
								<button type="submit" class="btn btn-outline-info <?= !isset($_GET['type']) || $_GET['type'] == 'all' ? 'active' : '' ?>" name="type" value="all" form="hire-type">Tutti</button>
								<button type="submit" class="btn btn-outline-info <?= $_GET['type'] == 'late' ? 'active' : '' ?>" name="type" value="late" form="hire-type">In ritardo</button>
								<button type="submit" class="btn btn-outline-info <?= $_GET['type'] == 'closed' ? 'active' : '' ?>" name="type" value="closed" form="hire-type">Conclusi</button>
								<button type="submit" class="btn btn-outline-info <?= $_GET['type'] == 'future' ? 'active' : '' ?>" name="type" value="future" form="hire-type">Programmati</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-center mb-3 mx-3">
			<div class="col-md-10 col-12 table-responsive shadow-lg px-0">
				<table class="table table-borderless table-hover table-dark text-center rounded mb-0">
					<thead>
						<tr>
							<th>ID noleggio</th>
							<th>Targa auto</th>
							<th>Codice fiscale socio</th>
							<th>Inizio</th>
							<th>Fine</th>
							<th>Data di restituzione</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($record = $result->fetch_assoc()) { ?>
							<tr>
								<td><?= $record['id_noleggio'] ?></td>
								<td><?= $record['targa'] ?></td>
								<td><?= $record['codice_fiscale'] ?></td>
								<td><?= date('j M Y', strtotime($record['data_inizio'])) ?></td>
								<td><?= date('j M Y', strtotime($record['data_fine'])) ?></td>
								<td><?= date('j M Y', strtotime($record['data_restituzione'])) ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<form id="hire-type" class="d-none" action="./" method="GET"></form>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>