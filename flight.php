<? session_start(); ?>
<?
	if (!$_SESSION['isAuth']):
		$redirectURL = '403.php';
		header('Location: '.$redirectURL);
	else:
?>
	<? require_once('layout/header.php') ?>
	<nav>
		<a href="./">Go home</a> | <a href="./logout.php">Logout</a>
	</nav>
	<? require_once('layout/msg.php') ?>
	<?
		require_once('db.php');

		$sql = "SELECT * FROM flight";
		$sth = $db->prepare($sql);
		$sth->execute();
	?>

	<? $isAdmin = $_SESSION['isAdmin']; ?>
	<table>
		<thead>
			<? if ($isAdmin): ?>
				<th>id</th>
			<? endif; ?>
			<th>Flight number</th>
			<th>Departure</th>
			<th>Destination</th>
			<th>Departure Date</th>
			<th>Arrival Date</th>
			<? if ($isAdmin): ?>
				<th>Operation</th>
			<? endif; ?>
		</thead>
		<tbody>
			<? if ($isAdmin): ?>
				<tr>
					<form action="flight_add_func.php" method="post">
						<td>New Plane</td>
						<td><input name="flightNumber" type="text" required></td>
						<td><input name="departure" type="text" required></td>
						<td><input name="destination" type="text" required></td>
						<td><input name="departureDate" type="datetime-local" required></td>
						<td><input name="arrivalDate" type="datetime-local" required></td>
						<td><input type="submit" value="Add"></td>
					</form>
				</tr>
			<? endif; ?>

			<?
				while ($result = $sth->fetchObject()) {
			?>
					<tr>
						<? if ($isAdmin): ?>
							<td><? echo $result->id ?></td>
						<? endif; ?>
						<td><? echo $result->flight_number ?></td>
						<td><? echo $result->departure ?></td>
						<td><? echo $result->destination ?></td>
						<td><? echo $result->departure_date ?></td>
						<td><? echo $result->arrival_date ?></td>
						<? if ($isAdmin): ?>
							<td>
								<a href="flight_edit.php?id=<? echo $result->id ?>">Edit</a> | 
								<a href="flight_delete_func.php?id=<? echo $result->id ?>">Delete</a>
							</td>
						<? endif; ?>
					</tr>
			<?
				}
			?>			
		</tbody>
	</table>
	<link rel="stylesheet" type="text/css" href="asset/css/flight.css">
	<? require_once('layout/footer.php') ?>
<? endif; ?>