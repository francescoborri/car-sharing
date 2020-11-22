<?php
require_once '../../private/config.php';
require_once ABSOLUTE_ROOT . '/private/session.php';

if (isset($_GET['id-noleggio'], $_GET['data-restituzione'])) {
    $id = htmlentities($_GET['id-noleggio']);
    $data_restituzione = htmlentities($_GET['data-restituzione']);
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $result = $connection->query("UPDATE `noleggi` SET `data_restituzione` = '$data_restituzione' WHERE `id_noleggio` = '$id'");
    if (!$result) {
        echo $connection->error;
        die();
    }
    $connection->close();
}

header('Location: ./index.php?type=active');