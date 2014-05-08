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

$sql = "DELETE FROM favoriteTicket WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['id']));
$redirectURL = '../ticket/search.php';

$_SESSION['msg'] = 'Delete ticket successfully.';

header('Location: '.$redirectURL);
?>
