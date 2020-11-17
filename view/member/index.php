<?php
require_once '../../private/config.php';
require_once ABSOLUTE_ROOT . '/private/session.php';

$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$members = $connection->query('SELECT * FROM `soci` ORDER BY `cognome`');
$title = "Noleggi di un socio.";
$description = "Visualizza i noleggi effettuati da un socio in un determinato periodo.";

if (isset($_GET['cf'], $_GET['start'], $_GET['end'])) {
    $cf = htmlentities($_GET['cf']);
    $start = htmlentities($_GET['start']);
    $end = htmlentities($_GET['end']);
    $member = $connection->query("SELECT * FROM `soci` WHERE `codice_fiscale` = '$cf'");
    if ($member && $member->num_rows == 1)
        $member = $member->fetch_assoc();
    else {
        echo "Errore: " . $connection->error;
        die();
    }
    $hires = $connection->query(
        "SELECT *
		FROM `noleggi`
        INNER JOIN `auto` USING(`targa`)
		WHERE `codice_fiscale` = '$cf' AND 
            IF(`noleggi`.`data_restituzione` is NULL,
				'$start' < GREATEST(`noleggi`.`data_fine`, CURRENT_DATE()) AND '$end' > `noleggi`.`data_inizio`,
                '$start' < `noleggi`.`data_restituzione` AND '$end' > `noleggi`.`data_inizio`)
        "
    );
    $title = "Noleggi di {$member['nome']} {$member['cognome']}";
    $description = "Stai visualizzando i noleggi di {$member['nome']} {$member['cognome']}, che ha effettuato {$hires->num_rows} auto fra il " . date('j M Y', strtotime($start)) . " e il " . date('j M Y', strtotime($end));
} else
    $hires = $connection->query(
        "SELECT *
        FROM `noleggi`
        INNER JOIN `auto` USING(`targa`)
        ORDER BY `noleggi`.`data_inizio`"
    );

if (!$hires || !$members) {
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
    <title>Car sharing - Noleggio di un socio</title>
</head>

<body class="bg-secondary">
    <div class="container-fluid px-0">
        <?php require_once ABSOLUTE_ROOT . '/private/header.php' ?>
        <div class="row justify-content-center">
            <div class="jumbotron jumbotron-fluid w-100 mb-3 text-light shadow" style="background-color: #545d65;">
                <div class="row justify-content-around mx-md-0 mx-3">
                    <div class="col-md-auto col-10 text-center my-auto">
                        <h1 class="display-4"><?= $title ?></h1>
                        <p class="lead text-break"><?= $description ?></p>
                    </div>
                    <?= isset($member) ? '<div class="col-auto row justify-content-center px-0 mx-md-3">' : '' ?>
                    <?php if (isset($member)) { ?>
                        <div class="col-md-auto my-md-auto mb-3">
                            <div class="card shadow text-body">
                                <div class="card-header">Informazioni socio</div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><?= "{$member['nome']} {$member['cognome']}" ?></li>
                                    <li class="list-group-item"><?= "Codice fiscale: <span class=\"text-monospace\">{$member['codice_fiscale']}</span>" ?></li>
                                    <li class="list-group-item"><?= "Indirizzo: {$member['indirizzo']}" ?></li>
                                    <li class="list-group-item"><?= "Telefono: {$member['telefono']}" ?></li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-md-auto">
                        <form action="./" method="GET">
                            <ul class="list-group shadow">
                                <li class="list-group-item p-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Inizio</span>
                                        </div>
                                        <select class="custom-select" name="cf" required>
                                            <option disabled hidden selected>Seleziona un socio</option>
                                            <?php while ($record = $members->fetch_assoc()) { ?>
                                                <option value="<?= $record['codice_fiscale'] ?>" <?= isset($member) && $record['codice_fiscale'] == $member['codice_fiscale'] ? 'selected' : '' ?>><?= "{$record['nome']} {$record['cognome']}" ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </li>
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
                                    <input type="submit" class="btn btn-primary btn-block" value="Cerca">
                                </li>
                            </ul>
                        </form>
                    </div>
                    <?= isset($member) ? '</div>' : '' ?>
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
                        <th>Marca</th>
                        <th>Modello</th>
                        <th>Inizio</th>
                        <th>Fine</th>
                        <th>Data di restituzione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($record = $hires->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $record['id_noleggio'] ?></td>
                            <td><?= $record['targa'] ?></td>
                            <td><?= $record['marca'] ?></td>
                            <td><?= $record['modello'] ?></td>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>