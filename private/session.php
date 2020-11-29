<?php 
require_once 'config.php';
session_start();

if (!isset($_SESSION['start_time']) || !isset($_SESSION['username']) || time() - $_SESSION['start_time'] > SESSION_TIMEOUT) {
	header('location: ' . ROOT . '/login');
	die();
}