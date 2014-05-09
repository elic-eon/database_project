<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

if (!$_SESSION['isAuth']) {
	$redirectURL = PATH_ROOT_URL.'/error/403.php';
	header('Location: '.$redirectURL);
	exit;
}



require_once('../module/db.php');
require_once('../module/checkData.php');

$key = isDataInvalid();
if ($key) {
	$_SESSION['msg'] = "$key cannot be empty.";
	$redirectURL = '../ticket/search.php';
} else {
	$flightNumber = explode(",", $_GET['number']);
	$n = sizeof($flightNumber);

	$sqlCheck = NULL;
	$sqlInsert = NULL;
	$data = NULL;
	switch ($n) {
		case 1:
			$sqlCheck = "SELECT * FROM favoriteTicket WHERE userId = ? AND flightNumber1 = ? AND flightNumber2 IS NULL AND flightNumber3 IS NULL";
			$sqlInsert = "INSERT INTO favoriteTicket (userId, flightNumber1) VALUES(?, ?)";
			$data = array(
				$_SESSION['uid'],
				$flightNumber[0]
			);
			break;
		case 2:
			$sqlCheck = "SELECT * FROM favoriteTicket WHERE userId = ? AND flightNumber1 = ? AND flightNumber2 = ? AND flightNumber3 IS NULL";
			$sqlInsert = "INSERT INTO favoriteTicket (userId, flightNumber1, flightNumber2) VALUES(?, ?, ?)";
			$data = array(
				$_SESSION['uid'],
				$flightNumber[0],
				$flightNumber[1]
			);
			break;
		case 3:
			$sqlCheck = "SELECT * FROM favoriteTicket WHERE userId = ? AND flightNumber1 = ? AND flightNumber2 = ? AND flightNumber3 = ?";
			$sqlInsert = "INSERT INTO favoriteTicket (userId, flightNumber1, flightNumber2, flightNumber3) VALUES(?, ?, ?, ?)";
			$data = array(
				$_SESSION['uid'],
				$flightNumber[0],
				$flightNumber[1],
				$flightNumber[2]
			);
			break;
	}

	$sth = $db->prepare($sqlCheck);
	$sth->execute($data);

	if ($sth->fetchObject()) {
		$_SESSION['msg'] = 'Ticket exists.';
		$redirectURL = '../ticket/search.php';
	} else {
		$sth = $db->prepare($sqlInsert);
		$r = $sth->execute($data);
		$redirectURL = '../ticket/search.php';
	}
}

header('Location: '.$redirectURL);
?>