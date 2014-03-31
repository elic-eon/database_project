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

// Check whether account exists
$sql = "SELECT * FROM user WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_SESSION['uid']));
if (!$sth->fetchObject()) {
	$redirectURL = PATH_ROOT_URL.'/user/logout.php';
	header('Location: '.$redirectURL);
	exit;
}

$key = isDataInvalid();
if ($key) {
	$_SESSION['msg'] = "$key cannot be empty.";
	$redirectURL = '../schedule/';
} else {
	$sql = "SELECT * FROM comparison WHERE user_id = ? AND flight_id = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array(
		$_SESSION['uid'],
		$_GET['id']
	));

	if ($sth->fetchObject()) {
		$_SESSION['msg'] = 'Comparison exists.';
		$redirectURL = '../schedule';
	} else {
		$sql = "INSERT INTO comparison (user_id, flight_id) VALUES(?, ?)";
		$sth = $db->prepare($sql);
		$r = $sth->execute(array(
			$_SESSION['uid'],
			$_GET['id']
		));
		$_SESSION['msg'] = 'Add comparison successfully.';
		$redirectURL = '../schedule';
	}
}

header('Location: '.$redirectURL);
?>