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

$sql = "DELETE FROM flight WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($_GET['id']));

$_SESSION['msg'] = 'Delete flight successfully.';
$redirectURL = './';

header('Location: '.$redirectURL);
?>
