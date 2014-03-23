<?php
require_once('./config.php');
session_save_path($SESSION_STORE);
session_start();
if (!$_SESSION['isAuth'] || !$_SESSION['isAdmin']):
	$redirectURL = '403.php';
else:
	require_once('db.php');

	$sql = "DELETE FROM flight WHERE id = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_GET['id']));

	$_SESSION['msg'] = 'Delete flight successfully.';
	$redirectURL = 'flight.php';
endif;

header('Location: '.$redirectURL);
?>
