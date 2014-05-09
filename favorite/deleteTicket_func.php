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

$sql = "DELETE FROM favoriteTicket WHERE id = ? AND userId = ?";
if (isset($_GET['redirect'])) {
	$redirectURL = '../ticket/search.php';
} else {
	$redirectURL = './ticket.php';
	$_SESSION['msg'] = 'Delete favorite ticket successfully.';
}

$sth = $db->prepare($sql);
$sth->execute(array(
	$_GET['id'],
	$_SESSION['uid']
));

header('Location: '.$redirectURL);
?>
