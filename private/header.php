<?php
require_once 'config.php';

if (session_status() == PHP_SESSION_NONE) 
	session_start();
	
$loggedin = isset($_SESSION['username'], $_SESSION['start_time']) && time() - $_SESSION['start_time'] < SESSION_TIMEOUT;
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top shadow-lg text-light">
	<a class="navbar-brand" href="<?= ROOT ?>">
		<span class="mr-2">
			<img src="<?= ROOT . '/res/car.svg' ?>">
		</span>
		Car sharing
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapse-navbar" aria-controls="collapse-navbar">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="collapse-navbar">
		<?php if ($loggedin) { ?>
			<ul class="navbar-nav mr-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown">Aggiungi</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="<?= ROOT . '/new/car' ?>">Auto</a>
						<a class="dropdown-item" href="<?= ROOT . '/new/hire' ?>">Noleggio</a>
						<a class="dropdown-item" href="<?= ROOT . '/new/member' ?>">Socio</a>
					</div>
				</li>
				<li class="nav-item dropdown mb-md-0 mb-2">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown">Visualizza</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="<?= ROOT . '/view/car' ?>">Auto</a>
						<a class="dropdown-item" href="<?= ROOT . '/view/hire' ?>">Noleggi</a>
						<a class="dropdown-item" href="<?= ROOT . '/view/member' ?>">Socio</a>
					</div>
				</li>
			</ul>
			<ul class="navbar-nav">
				<li class="nav-item mb-md-auto mb-2 mr-2">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">
								<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
								</svg>
							</span>
						</div>
						<div class="input-group-append">
							<span class="input-group-text"><?= $_SESSION['username'] ?></span>
						</div>
					</div>
				</li>
				<li class="nav-item">
					<a href="<?= ROOT . '/private/logout.php' ?>" class="btn btn-danger">Esci</a>
				</li>
			</ul>
		<?php } else { ?>
			<ul class="navbar-nav mr-auto">
				<li class="nav-item dropdown">
					<a class="nav-link" href="<?= ROOT . '/view/car' ?>">Visualizza auto disponibili</a>
				</li>
			</ul>
			<ul class="navbar-nav">
				<li class="nav-item mb-md-auto mb-2 mr-2">
					<a href="<?= ROOT . '/login' ?>" class="btn btn-primary">Accedi</a>
				</li>
				<li class="nav-item">
					<a href="<?= ROOT . '/signin' ?>" class="btn btn-primary">Registrati</a>
				</li>
			</ul>
		<?php } ?>
	</div>
</nav>