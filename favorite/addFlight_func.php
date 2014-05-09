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
	$redirectURL = '../schedule/';
} else {
	$sql = "SELECT * FROM favoriteFlight WHERE userId = ? AND flightNumber = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array(
		$_SESSION['uid'],
		$_GET['number']
	));

	if ($sth->fetchObject()) {
		$_SESSION['msg'] = 'Flight exists.';
		$redirectURL = '../schedule';
	} else {
		$sql = "INSERT INTO favoriteFlight (userId, flightNumber) VALUES(?, ?)";
		$sth = $db->prepare($sql);
		$r = $sth->execute(array(
			$_SESSION['uid'],
			$_GET['number']
		));
		$redirectURL = '../schedule';
	}
}

header('Location: '.$redirectURL);
?>