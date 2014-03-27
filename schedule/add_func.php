<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

if (!$_SESSION['isAuth'] || !$_SESSION['isAdmin']) {
	$redirectURL = PATH_ROOT_URL.'/error/403.php';
	header('Location: '.$redirectURL);
	exit;
}



require_once('../module/db.php');
require_once('../module/checkData.php');

$key = isDataInvalid();
if ($key) {
	$_SESSION['msg'] = "$key cannot be empty.";
	$redirectURL = './';
} else {
	$sql = "SELECT * FROM flight WHERE flight_number = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_POST['flightNumber']));
	if ($sth->fetchObject()) {
		$_SESSION['msg'] = 'Flight exists.';
		$redirectURL = 'flight.php';
	} else {
		$sql = "INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date, price) VALUES(?, ?, ?, ?, ?, ?)";
		$sth = $db->prepare($sql);
		$sth->execute(array(
			$_POST['flightNumber'],
			$_POST['departure'],
			$_POST['destination'],
			$_POST['departureDate'],
			$_POST['arrivalDate'],
			$_POST['price']
		));
		$_SESSION['msg'] = 'Add flight successfully.';
		$redirectURL = './';
	}
}

header('Location: '.$redirectURL);
?>
