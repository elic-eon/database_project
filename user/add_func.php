<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();
require_once('../module/db.php');
require_once('../module/checkData.php');

$key = isDataInvalid();
if ($key) {
	$_SESSION['msg'] = "$key cannot be empty.";
	$redirectURL = 'add.php';
} else {
	$sql = "SELECT * FROM user WHERE account = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_POST['account']));
	if ($sth->fetchObject()) {
		$_SESSION['msg'] = 'Account exists.';
		$redirectURL = 'add.php';
	} else {
		$isAdmin = false;
		$sql = "INSERT INTO user (account, password, is_admin) VALUES(?, ?, ?)";
		$sth = $db->prepare($sql);
		$sth->execute(array($_POST['account'], crypt($_POST['password'], PW_SALT), $isAdmin));
		$_SESSION['msg'] = 'Add user successfully.';
		$redirectURL = 'add.php';
	}
}

header('Location: '.$redirectURL);
?>
