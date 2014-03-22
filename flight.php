<?php session_start(); ?>
<?php
	if (!$_SESSION['isAuth']):
		$redirectURL = '403.php';
		header('Location: '.$redirectURL);
	else:
?>
	<?php require_once('layout/header.php') ?>
	<nav>
		<a href="./">Go home</a> | <a href="./logout.php">Logout</a>
	</nav>
	<?php require_once('layout/msg.php') ?>
	<?php
		require_once('db.php');

		$sql = "SELECT * FROM flight";
		$sth = $db->prepare($sql);
		$sth->execute();
	?>

	<?php $isAdmin = $_SESSION['isAdmin']; ?>
	<table>
		<thead>
			<?php if ($isAdmin): ?>
				<th>id</th>
			<?php endif; ?>
			<th>Flight number</th>
			<th>Departure</th>
			<th>Destination</th>
			<th>Departure Date</th>
			<th>Arrival Date</th>
			<?php if ($isAdmin): ?>
				<th>Operation</th>
			<?php endif; ?>
		</thead>
		<tbody>
			<?php if ($isAdmin): ?>
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
			<?php endif; ?>

			<?php
				while ($result = $sth->fetchObject()) {
			?>
					<tr>
						<?php if ($isAdmin): ?>
							<td><?php echo $result->id ?></td>
						<?php endif; ?>
						<td><?php echo $result->flight_number ?></td>
						<td><?php echo $result->departure ?></td>
						<td><?php echo $result->destination ?></td>
						<td><?php echo $result->departure_date ?></td>
						<td><?php echo $result->arrival_date ?></td>
						<?php if ($isAdmin): ?>
							<td>
								<a href="flight_edit.php?id=<?php echo $result->id ?>">Edit</a> | 
								<a href="flight_delete_func.php?id=<?php echo $result->id ?>">Delete</a>
							</td>
						<?php endif; ?>
					</tr>
			<?php
				}
			?>			
		</tbody>
	</table>
	<link rel="stylesheet" type="text/css" href="asset/css/flight.css">
	<?php require_once('layout/footer.php') ?>
<?php endif; ?>
