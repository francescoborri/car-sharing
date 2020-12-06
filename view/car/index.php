<?php
require_once '../../private/config.php';
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (isset($_GET['start'], $_GET['end'])) {
	$start = htmlentities($_GET['start']);
	$end = htmlentities($_GET['end']);

	$result = $connection->query(
		"SELECT *
		FROM `auto`
		WHERE `targa` NOT IN (
			SELECT `targa`
			FROM `noleggi`
			WHERE
				IF(`noleggi`.`data_restituzione` is NULL,
					'$start' < GREATEST(`noleggi`.`data_fine`, CURRENT_DATE()) AND '$end' > `noleggi`.`data_inizio`,
					'$start' < `noleggi`.`data_restituzione` AND '$end' > `noleggi`.`data_inizio`)
		)"
	);
} else $result = $connection->query("SELECT * FROM `auto`");

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
	<title>Car sharing - Auto disponibili</title>
</head>

<body class="bg-secondary">
	<div class="container-fluid px-0">
		<?php require_once ABSOLUTE_ROOT . '/private/header.php' ?>
		<div class="row justify-content-center">
			<div class="jumbotron jumbotron-fluid text-light shadow w-100 mb-3" style="background-color: #545d65;">
				<div class="row justify-content-around mx-md-0 mx-3">
					<div class="col-md-auto col-10 text-center my-auto">
						<h1 class="display-4">Auto disponibili</h1>
						<p class="lead text-break"><?= isset($_GET['start'], $_GET['end']) ? "Ci sono {$result->num_rows} auto disponibili nel periodo che hai selezionato." : 'Cerca un\'auto disponibile selezionando il periodo di noleggio.' ?></p>
					</div>
					<div class="col-md-auto">
						<form action="./" method="GET">
							<ul class="list-group shadow">
								<li class="list-group-item p-3">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">Inizio</span>
										</div>
										<input type="date" class="form-control" name="start" <?= isset($_GET['start']) ? "value=\"{$_GET['start']}\"" : '' ?> required>
									</div>
								</li>
								<li class="list-group-item p-3">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">Fine</span>
										</div>
										<input type="date" class="form-control" name="end" <?= isset($_GET['end']) ? "value=\"{$_GET['end']}\"" : '' ?> required>
									</div>
								</li>
								<li class="list-group-item p-3">
									<input id="submit-btn" type="submit" class="btn btn-primary btn-block" value="Cerca" disabled>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-center mb-3 mx-3">
			<div class="col-md-6 col-sm-8 col-12 table-responsive shadow-lg px-0">
				<table class="table table-borderless table-hover table-dark text-center rounded mb-0">
					<thead>
						<tr>
							<th class="align-middle">Targa</th>
							<th class="align-middle">Marca</th>
							<th class="align-middle">Modello</th>
							<th class="align-middle">Costo giornaliero</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($record = $result->fetch_assoc()) { ?>
							<tr>
								<td class="align-middle"><?= $record['targa'] ?></td>
								<td class="align-middle"><?= $record['marca'] ?></td>
								<td class="align-middle"><?= $record['modello'] ?></td>
								<td class="align-middle">€<?= $record['costo_giornaliero'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="<?= ROOT . '/js/check-date.js' ?>"></script>
</body>

</html>