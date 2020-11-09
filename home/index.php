<?php require_once '../private/config.php'; ?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ROOT . '/css/default.css' ?>">
    <link rel="stylesheet" href="<?= ROOT . '/css/img.css' ?>">
    <title>Car sharing - Home</title>
</head>

<body class="bg-secondary">
    <div class="container-fluid px-0">
        <?php require_once ABSOLUTE_ROOT . '/private/header.php' ?>
        <div class="row justify-content-center">
            <div class="jumbotron jumbotron-fluid w-100 mb-3 text-light shadow" style="background-color: #545d65;">
                <div class="row justify-content-around">
                    <div class="col-md-auto col-10 text-center mt-auto mb-auto">
                        <h1><span class="display-3">Benvenuto</span> nel <span class="display-3">Car sharing</span></h1>
                        <p class="lead text-break">
                            <?php if (!isset($_SESSION['username']) || !isset($_SESSION['start_time']) || time() - $_SESSION['start_time'] > SESSION_TIMEOUT) { ?>
                                <a href="<?= ROOT . '/login' ?>" class="text-info">Accedi</a>, <a href="<?= ROOT . '/signin' ?>" class="text-info">registrati</a> o <a href="<?= ROOT . '/view/car' ?>" class="text-info">trova</a> un'auto disponibile quando vuoi tu.
                            <?php } else { ?>
                                Buongiorno, <span class="text-capitalize"><?= $_SESSION['username'] ?></span>.
                            <?php } ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-around">
            <div class="col-auto text-center mt-auto mb-auto">
                <div id="sample-cars" class="carousel slide mx-3" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?= ROOT . '/res/de-lorean.jpg' ?>" class="mb-5 img-fluid rounded shadow-lg logo">
                        </div>
                        <div class="carousel-item">
                            <img src="<?= ROOT . '/res/batmobile.jpg' ?>" class="mb-5 img-fluid rounded shadow-lg logo">
                        </div>
                        <div class="carousel-item">
                            <img src="<?= ROOT . '/res/saetta-mcqueen.jpg' ?>" class="mb-5 img-fluid rounded shadow-lg logo">
                        </div>
                        <div class="carousel-item">
                            <img src="<?= ROOT . '/res/cricchetto.png' ?>" class="mb-5 img-fluid rounded shadow-lg logo">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#sample-cars" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#sample-cars" role="button" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>