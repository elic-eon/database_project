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
		$redirectURL = 'flight.php?id='.$_POST['id'];
	} else {
    $sql = "SELECT * FROM flight WHERE flight_number = ?";
    $sth = $db->prepare($sql);
    $sth->execute(array($_POST['flightNumber']));
    if ($sth->fetchObject()) {
      $_SESSION['msg'] = 'Flight exists.';
      $redirectURL = 'flight.php';
    } else {
      $sql = "INSERT INTO flight (flight_number, departure, destination, departure_date, arrival_date) VALUES(?, ?, ?, ?, ?)";
      $sth = $db->prepare($sql);
      $sth->execute(array(
        $_POST['flightNumber'],
        $_POST['departure'],
        $_POST['destination'],
        $_POST['departureDate'],
        $_POST['arrivalDate']
      ));
      $_SESSION['msg'] = 'Add flight successfully.';
      $redirectURL = 'flight.php';
    }
	}
endif;

header('Location: '.$redirectURL);
?>
