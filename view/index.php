<?php
require_once '../private/config.php';

if (isset($_GET['start'], $_GET['end'])) {
	$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$start = htmlentities($_GET['start']);
	$end = htmlentities($_GET['end']);
	$result = $connection->query("
		SELECT `auto`.`marca`, `auto`.`modello`, `auto`.`costo_giornaliero`, `auto`.`targa`, `noleggi`.`targa` AS `my_targa`
		FROM `noleggi`
		INNER JOIN `auto` USING (`targa`)
		WHERE
			(`noleggi`.`inizio` < '$start' AND `noleggi`.`fine` < '$start' AND `noleggi`.`auto_restituita`) OR
    		(`noleggi`.`inizio` > '$end' AND `noleggi`.`fine` > '$end')
		GROUP BY `noleggi`.`targa`
		HAVING COUNT(*) = (SELECT COUNT(*) FROM `noleggi` WHERE `noleggi`.`targa` = `my_targa`);
	");
	if (!$result) {
		echo "Errore: " . $connection->error;
		die();
	}
}
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" href="<?= ROOT . '/css/default.css' ?>">
	<title>Auto disponibili</title>
</head>

<body>
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="jumbotron jumbotron-fluid w-100">
				<div class="row justify-content-around">
					<div class="col-auto d-flex align-items-center text-center">
						<h1 class="display-4">Auto disponibili</h1>
					</div>
					<div class="col-auto">
						<form action="./" method="GET">
							<ul class="list-group shadow">
								<li class="list-group-item">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">Inizio</span>
										</div>
										<input type="date" class="form-control" name="start">
									</div>
								</li>
								<li class="list-group-item">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">Fine</span>
										</div>
										<input type="date" class="form-control" name="end">
									</div>
								</li>
								<li class="list-group-item">
									<input type="submit" class="btn btn-primary btn-block" value="Cerca">
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php if (isset($result)) { ?>
			<div class="row justify-content-center">
				<div class="col-6">
					<table class="table table-borderless table-hover">
						<thead>
							<tr>
								<th>Marca</th>
								<th>Modello</th>
								<th>Costo giornaliero</th>
								<th>Targa</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($record = $result->fetch_assoc()) { ?>
								<tr>
									<td><?= $record['marca'] ?></td>
									<td><?= $record['modello'] ?></td>
									<td><?= $record['costo_giornaliero'] ?></td>
									<td><?= $record['targa'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php } ?>
	</div>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>