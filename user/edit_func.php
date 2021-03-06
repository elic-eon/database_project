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
	$redirectURL = 'edit.php?id='.$_POST['id'];
} else {
	$isAdmin = $_POST['is_admin']? true: false;
	$sql = "UPDATE user SET is_admin = ? WHERE  id = ?;";
	$sth = $db->prepare($sql);
	$sth->execute(array(
		$isAdmin,
		$_POST['id']
	));
	$redirectURL = './';
	$_SESSION['msg'] = 'Update successfully.';
}

header('Location: '.$redirectURL);
?>