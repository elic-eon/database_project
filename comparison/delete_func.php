<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

// require_once('../module/checkUserExist.php');
if (!$_SESSION['isAuth']) {
	$redirectURL = PATH_ROOT_URL.'/error/403.php';
	header('Location: '.$redirectURL);
	exit;
}



require_once('../module/db.php');

if (isset($_GET['redirect'])) {
	$sql = "DELETE FROM comparison WHERE flight_id = ? AND user_id = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_GET['id'], $_SESSION['uid']));
	$redirectURL = '../schedule/';
} else {
	$sql = "DELETE FROM comparison WHERE id = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_GET['id']));
	$redirectURL = './';
}
$_SESSION['msg'] = 'Delete comparison successfully.';

header('Location: '.$redirectURL);
?>
