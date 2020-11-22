<?php
require_once '../../private/config.php';
require_once ABSOLUTE_ROOT . '/private/session.php';

$on_error = false;

if (isset($_POST['targa'], $_POST['modello'], $_POST['marca'], $_POST['marca'])) {
	$targa = htmlentities($_POST['targa']);
    $modello = htmlentities($_POST['modello']);
    $marca = htmlentities($_POST['marca']);
    $marca = htmlentities($_POST['marca']);

	$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $result = $connection->query("INSERT INTO ");
    
	if (!$result || $result->num_rows == 0)
		$on_error = true;
	else {
		$user_data = $result->fetch_array();
		$password = crypt($password, crypt($username, DB_SALT));
		if ($password == $user_data['password']) {
			session_start();
			$_SESSION['username'] = $username;
			$_SESSION['start_time'] = time();
			header('location: ' . ROOT . '/home');
		} else
			$on_error = true;
	}
	$result->free();
	$connection->close();
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
                        <h1 class="display-4">Car sharing</h1>
                        <p class="lead">Inserisci una nuova auto.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mx-md-0 mx-3 mb-3">
            <ul class="col-md-auto col-12 list-group shadow pr-0">
                <li class="list-group-item p-3">
                    <input type="text" name="targa" class="form-control" placeholder="Targa" form="new-car-form" minlength="7" maxlength="7" required>
                </li>
                <li class="list-group-item p-3">
                    <input type="text" name="marca" class="form-control" placeholder="Marca" form="new-car-form" required>
                </li>
                <li class="list-group-item p-3">
                    <input type="text" name="modello" class="form-control" placeholder="Marca" form="new-car-form" required>
                </li>
                <li class="list-group-item p-3">
                    <input type="number" name="costo" class="form-control" placeholder="Costo giornaliero" form="new-car-form" required>
                </li>
                <li class="list-group-item p-3">
                    <input type="submit" class="btn btn-primary btn-block" value="Inserisci" form="new-car-form">
                </li>
            </ul>
        </div>
    </div>
    <form id="new-car-form" action="./" method="POST" class="d-none"></form>
    <?php if ($on_error) { ?>
        <div class="modal" id="login-error-modal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="alert alert-danger mb-0">
                        <span class="lead">Impossibile aggiungere l'auto-</span>
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
    <?php if ($on_error) { ?>
        <script type="text/javascript">
            $('#login-error-modal').modal('show');
        </script>
    <?php } ?>
</body>

</html>