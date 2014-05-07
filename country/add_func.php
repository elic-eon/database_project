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
	$sql = "SELECT * FROM country WHERE name = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_POST['name']));
	if ($sth->fetchObject()) {
		$_SESSION['msg'] = 'Country exists.';
		$redirectURL = 'add.php';
	} else {
		$sql = "INSERT INTO country (name, fullName) VALUES(?, ?)";
		$sth = $db->prepare($sql);
		$sth->execute(array(
			$_POST['name'],
			$_POST['fullName']
		));
		$_SESSION['msg'] = 'Add country successfully.';
		$redirectURL = './';
	}
}

header('Location: '.$redirectURL);
?>