<?php
session_start();
if (!$_SESSION['isAuth'] || !$_SESSION['isAdmin']):
	$redirectURL = '403.php';
	header('Location: '.$redirectURL);
else:
	require_once('db.php');
	$sql = "SELECT * FROM flight WHERE id = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_GET['id']));
	$result = $sth->fetchObject();
	require_once('layout/header.php');
	require_once('layout/msg.php');
?>
	<?php require_once('layout/msg.php') ?>
	<form action="flight_edit_func.php" method="post" role="form">
		<input name="id" type="hidden" value="<?php echo $result->id ?>">
		<p>Flight Number: <input name="flightNumber" type="text" value="<?php echo $result->flight_number ?>"></p>
		<p>Departure: <input name="departure" type="text" value="<?php echo $result->departure ?>"></p>
		<p>Destination: <input name="destination" type="text" value="<?php echo $result->destination ?>"></p>
		<p>Departure Date: <input name="departureDate" type="datetime-local" value="<?php echo strftime('%Y-%m-%dT%H:%M:%S', strtotime($result->departure_date)) ?>"></p>
		<p>Arrival Date: <input name="arrivalDate" type="datetime-local" value="<?php echo strftime('%Y-%m-%dT%H:%M:%S', strtotime($result->arrival_date)) ?>"></p>
		<p><input type="submit" value="Update"> | <a href="flight.php">Cancel</a></p>
	</form>
<?php
	require_once('layout/footer.php');
endif;
?>
