<?php
require_once('config.php');
session_save_path($SESSION_STORE);
session_start();
require_once('db.php');
require_once('module/checkData.php');

$key = isDataInvalid();
if ($key) {
	$_SESSION['msg'] = "$key cannot be empty.";
	$redirectURL = 'login.php?id='.$_POST['id'];
} else {
	$sql = "SELECT * FROM user WHERE account = ? AND password = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_POST['account'], crypt($_POST['password'], $PW_SALT)));
	$result = $sth->fetchObject();
	if ($result) {
		$_SESSION['isAuth'] = true;
		$_SESSION['isAdmin'] = $result->is_admin;
		$redirectURL = 'flight.php';
	} else {
		$_SESSION['msg'] = 'Wrong account or password.';
		$redirectURL = 'login.php';
	}
}

header('Location: '.$redirectURL);
?>
