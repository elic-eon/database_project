<?php
require_once('./config.php');
session_save_path($SESSION_STORE);
session_start();
if (!$_SESSION['isAuth'] || !$_SESSION['isAdmin']):
	$redirectURL = '403.php';
else:
	require_once('db.php');
	require_once('module/checkData.php');

	$key = isDataInvalid();
	if ($key) {
		$_SESSION['msg'] = "$key cannot be empty.";
		$redirectURL = 'flight_edit.php?id='.$_POST['id'];
	} else {
		$sql = "UPDATE flight SET flight_number = ?, departure = ?, destination = ?, departure_date = ?, arrival_date = ? WHERE  id = ?;";
		$sth = $db->prepare($sql);
		$sth->execute(array(
			$_POST['flightNumber'],
			$_POST['departure'],
			$_POST['destination'],
			$_POST['departureDate'],
			$_POST['arrivalDate'],
			$_POST['id']
		));
		$redirectURL = 'flight.php';
		$_SESSION['msg'] = 'Update successfully.';
	}
endif;
header('Location: '.$redirectURL);
?>