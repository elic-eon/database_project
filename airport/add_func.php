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
	$redirectURL = 'add.php';
} else {
	$sql = "SELECT * FROM airport WHERE name = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_POST['name']));
	if ($sth->fetchObject()) {
		$_SESSION['msg'] = 'Airport exists.';
		$redirectURL = 'add.php';
	} else {
		$minutes = ((int)$_POST['timezone_type']) * (((int)$_POST['timezone_hour']) * 60 + ((int)$_POST['timezone_minute']));
		$sql = "INSERT INTO airport (name, fullName, longitude, latitude, country, timezone_minute) VALUES(?, ?, ?, ?, ?, ?)";
		$sth = $db->prepare($sql);
		$sth->execute(array(
			$_POST['name'],
			$_POST['fullName'],
			$_POST['longitude'],
			$_POST['latitude'],
			$_POST['country'],
			$minutes
		));
		$_SESSION['msg'] = 'Add airport successfully.';
		$redirectURL = './';
	}
}

header('Location: '.$redirectURL);
?>
