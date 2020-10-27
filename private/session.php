<?php 
require_once 'config.php';
session_start();

if (!isset($_SESSION['start_time']) || time() - $_SESSION['start_time'] > SESSION_TIMEOUT)
	header('location: ' . ROOT . '/login');