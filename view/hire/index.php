<?php
require_once '../../private/config.php';
require_once ABSOLUTE_ROOT . '/private/session.php';

$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!isset($_GET['type']) || $_GET['type'] == 'all') {
	$title = 'Tutti i noleggi';
	$description = 'Stai visualizzando lo storico di tutti i noleggi.';
	$result = $connection->query(
		"SELECT *
		FROM `noleggi`
		ORDER BY `noleggi`.`data_inizio`"
	);
} else if ($_GET['type'] == 'active') {
	$title = 'Noleggi attivi';
	$description = 'Stai visualizzando i noleggi non ancora conclusi.';
	$result = $connection->query(
		"SELECT *
		FROM `noleggi`
		WHERE
			`noleggi`.`data_restituzione` IS NULL AND
			`noleggi`.`data_inizio` <= CURRENT_DATE()
		ORDER BY `noleggi`.`data_inizio`"
	);
} else if ($_GET['type'] == 'late') {
	$title = 'Noleggi in ritardo';
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
	$title = 'Noleggi conclusi';
	$description = 'Stai visualizzando lo storico dei noleggi conclusi.';
	$result = $connection->query(
		"SELECT *
		FROM `noleggi`
		WHERE `noleggi`.`data_restituzione` IS NOT NULL
		ORDER BY `noleggi`.`data_inizio`"
	);
} else if ($_GET['type'] == 'future') {
	$title = 'Noleggi programmati';
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

$hires = [];
while ($row = $result->fetch_array())
	$hires[] = $row;
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
								<button type="submit" class="btn btn-outline-info <?= $_GET['type'] == 'active' ? 'active' : '' ?>" name="type" value="active" form="hire-type">Attivi</button>
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
							<th class="align-middle">ID noleggio</th>
							<th class="align-middle">Targa auto</th>
							<th class="align-middle">Codice fiscale socio</th>
							<th class="align-middle">Inizio</th>
							<th class="align-middle">Fine</th>
							<th class="align-middle">Data di restituzione</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($hires as $index => $hire) { ?>
							<tr>
								<td class="align-middle"><?= $hire['id_noleggio'] ?></td>
								<td class="align-middle"><?= $hire['targa'] ?></td>
								<td class="align-middle"><?= $hire['codice_fiscale'] ?></td>
								<td class="align-middle"><?= date('j M Y', strtotime($hire['data_inizio'])) ?></td>
								<td class="align-middle"><?= date('j M Y', strtotime($hire['data_fine'])) ?></td>
								<td class="align-middle">
									<?php if ($hire['data_restituzione'] != '')
										echo date('j M Y', strtotime($hire['data_restituzione']));
									else if (isset($_GET['type']) && ($_GET['type'] == 'active' || $_GET['type'] == 'late')) { ?>
										<button type="button" id="close-btn-<?= $index ?>" class="btn btn-info btn-sm" <?= $hire['data_inizio'] > date('Y-m-d') ? 'disabled' : '' ?>>
											Chiudi questo noleggio
										</button>
									<?php } else echo 'Non restituita'; ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php if (isset($_GET['type']) && ($_GET['type'] == 'active' || $_GET['type'] == 'late')) { ?>
		<div class="modal fade" id="modal">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Chiusura noleggio</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						Stai per chiudere questo noleggio, controlla i dati e conferma la data di restituzione.
						<hr class="my-2">
						<span class="small">ID:</span>
						<input type="text" name="id_noleggio" id="id_noleggio" form="close-form" class="form-control" readonly>
						<span class="small">Targa auto:</span>
						<input type="text" id="targa" class="form-control" readonly>
						<span class="small">Codice fiscale socio:</span>
						<input type="text" id="codice_fiscale" class="form-control" readonly>
						<span class="small">Seleziona una data</span>
						<input type="date" name="data_restituzione" form="close-form" class="form-control" value="<?= date('Y-m-d') ?>">
						<form action="close.php" id="close-form" method="GET"></form>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-info mr-auto" form="close-form">Si</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<form id="hire-type" class="d-none" action="./" method="GET"></form>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script type="text/javascript">
		<?php if (isset($_GET['type']) && ($_GET['type'] == 'active' || $_GET['type'] == 'late')) {
			foreach ($hires as $index => $hire) { ?>
				$('#close-btn-<?= $index ?>').click(() => {
					$('#id_noleggio').attr('value', '<?= $hire['id_noleggio'] ?>');
					$('#targa').attr('value', '<?= $hire['targa'] ?>');
					$('#codice_fiscale').attr('value', '<?= $hire['codice_fiscale'] ?>');
					$('#modal').modal('show');
				});
		<?php }
		} ?>
	</script>
</body>

</html>