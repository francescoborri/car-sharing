<?php
require_once '../../private/config.php';
require_once ABSOLUTE_ROOT . '/private/session.php';

if (isset($_GET['id_noleggio'], $_GET['data_restituzione'])) {
	$id_noleggio = htmlentities($_GET['id_noleggio']);
	$data_restituzione = htmlentities($_GET['data_restituzione']);

	$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$result = $connection->query("UPDATE `noleggi` SET `data_restituzione` = '$data_restituzione' WHERE `id_noleggio` = '$id_noleggio'");

	if (!$result) {
		echo $connection->error;
		die();
	}
	
	$connection->close();
}

header('Location: ./');