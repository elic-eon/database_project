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

$sql = "DELETE FROM favoriteFlight WHERE flightNumber = ? AND userId = ?";
if (isset($_GET['redirect'])) {
	$redirectURL = '../schedule/';
} else {
	$redirectURL = './flight.php';
	$_SESSION['msg'] = 'Delete favorite flight successfully.';
}

$sth = $db->prepare($sql);
$sth->execute(array(
	$_GET['number'],
	$_SESSION['uid']
));

header('Location: '.$redirectURL);
?>
