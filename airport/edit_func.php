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
	$sql = "UPDATE airport SET name = ?, longitude = ?, latitude = ? WHERE  id = ?;";
	$sth = $db->prepare($sql);
	$sth->execute(array(
		$_POST['name'],
		$_POST['longitude'],
		$_POST['latitude'],
		$_POST['id']
	));
	$redirectURL = './';
	$_SESSION['msg'] = 'Update successfully.';
}

header('Location: '.$redirectURL);
?>