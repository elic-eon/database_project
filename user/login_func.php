<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();
require_once('../module/db.php');
require_once('../module/checkData.php');

$key = isDataInvalid();
if ($key) {
	$_SESSION['msg'] = "$key cannot be empty.";
	$redirectURL = 'login.php';
} else {
	$sql = "SELECT * FROM user WHERE account = ? AND password = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_POST['account'], crypt($_POST['password'], PW_SALT)));
	$result = $sth->fetchObject();
	if ($result) {
		$sql = "UPDATE user SET session_id = ? WHERE id = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array(
			session_id(),
			$result->id
		));

		$_SESSION['isAuth'] = true;
		$_SESSION['uid'] = $result->id;
		$_SESSION['account'] = $result->account;
		$_SESSION['isAdmin'] = $result->is_admin;
		$redirectURL = '../schedule/';
	} else {
		$_SESSION['msg'] = 'Wrong account or password.';
		$redirectURL = 'login.php';
	}
}

header('Location: '.$redirectURL);
?>
