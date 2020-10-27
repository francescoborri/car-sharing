<?php
require_once 'config.php';

define('N_AUTO', 100);
define('N_SOCI', 200);
define('N_NOLEGGI', 50);

$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$targhe = [];
$codici_fiscali = [];
$telefoni = [];
$marche = [
    'Abarth', 'Alfa Romeo', 'Audi',
    'BMW',
    'Citroen',
    'Dacia',
    'Ferrari', 'FIAT', 'Ford',
    'Honda', 'Hyundai',
    'Jaguar', 'Jeep',
    'KIA',
    'Lamborghini', 'Lancia', 'Land Rover',
    'Maserati', 'Mazda', 'Mercedes', 'Mini', 'Mitsubishi',
    'Nissan',
    'Opel',
    'Peugeot', 'Porsche',
    'Renault', 'Seat', 'Skoda', 'Smart', 'Subaru', 'Suzuki',
    'Tesla', 'Toyota',
    'Volkswagen', 'Volvo'
];
$mesi = ['A', 'B', 'C', 'D', 'E', 'H', 'L', 'M', 'P', 'R', 'S', 'T'];

$connection->query('DELETE FROM `auto` WHERE 1');
$connection->query('DELETE FROM `soci` WHERE 1');
$connection->query('DELETE FROM `noleggi` WHERE 1');

$query = $connection->prepare('INSERT INTO `auto`(`targa`, `marca`, `modello`, `costo_giornaliero`) VALUES (?, ?, ?, ?)');
$query->bind_param(
    'sssd',
    $targa,
    $marca,
    $modello,
    $costo_giornaliero
);

for ($i = 0; $i < N_AUTO; $i++) {
    $targa = '';
    do {
        $targa = chr(rand(65, 90)) . chr(rand(65, 90)) . sprintf('%03d', rand(1, 999)) . chr(rand(65, 90)) . chr(rand(65, 90));
    } while (array_key_exists($targa, $targhe));

    $targhe[$targa] = true;
    $marca = $marche[rand(0, count($marche) - 1)];
    $modello = 'Modello ' . rand(1, 10);
    $costo_giornaliero = rand(50, 99) + (double)(rand(0, 9) / 10);

    $query->execute();
    if ($query->affected_rows <= 0)
        throw new Exception('Errore di inserimento: ' . $query->error);
}

$connection->commit();
$query->close();

$query = $connection->prepare('INSERT INTO `soci`(`codice_fiscale`, `cognome`, `nome`, `indirizzo`, `telefono`) VALUES (?, ?, ?, ?, ?)');
$query->bind_param(
    'sssss',
    $codice_fiscale,
    $cognome,
    $nome,
    $indirizzo,
    $telefono
);

for ($i = 0; $i < N_SOCI; $i++) {
    $codice_fiscale = '';

    do {
        $codice_fiscale = '';

        for ($j = 0; $j < 6; $j++)
            $codice_fiscale .= chr(rand(65, 90));
       
        $codice_fiscale .= 
            sprintf('%02d', rand(0, 99)) .
            $mesi[rand(0, count($mesi) - 1)] .
            sprintf('%02d', rand(1, 31)) .
            chr(rand(65, 90)) . sprintf('%03d', rand(1, 999)) .
            chr(rand(65, 90));
    } while (array_key_exists($codice_fiscale, $codici_fiscali));
    $codici_fiscali[$codice_fiscale] = true;

    $cognome = "Cognome$i"; 
    $nome = "Nome$i";
    $indirizzo = 'Via n' . strval(rand(1, 50)) . ' n. ' . strval(rand(1, 50));
    $telefono = '3';

    do {
        $telefono = '3';
        for ($j = 0; $j < 9; $j++)
            $telefono .= strval(rand(0, 9));
    } while (array_key_exists($telefono, $telefoni));
    $telefoni[$telefono] = true;

    $query->execute();
    if ($query->affected_rows <= 0)
        throw new Exception('Errore di inserimento: ' . $query->error);
}

$connection->commit();
$query->close();
