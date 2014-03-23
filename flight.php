<?php
require_once('./config.php');
session_save_path($SESSION_STORE);
session_start();
?>
<?php
	if (!$_SESSION['isAuth']):
		$redirectURL = '403.php';
		header('Location: '.$redirectURL);
	else:
?>
	<?php require_once('layout/header.php') ?>
  <ul class="nav nav-pills">
    <li><a href="./">Home</a></li>
    <li><a href="./logout.php">Logout</a></li>
  </ul>
	<?php
		require_once('db.php');

		$sql = "SELECT * FROM flight";
		$sth = $db->prepare($sql);
		$sth->execute();
	?>

	<?php $isAdmin = $_SESSION['isAdmin']; ?>
	<table class="table table-bordered">
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
					<form action="flight_add_func.php" method="post" role="form">
						<td>New Plane</td>
						<td><input name="flightNumber" type="text" class="form-control" required></td>
						<td><input name="departure" type="text" class="form-control" required></td>
						<td><input name="destination" type="text" class="form-control"  required></td>
						<td><input name="departureDate" type="datetime-local" class="form-control"  required></td>
						<td><input name="arrivalDate" type="datetime-local" class="form-control"  required></td>
						<td><input type="submit" value="Add" class="btn btn-default" ></td>
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
	<?php require_once('layout/msg.php') ?>
	<?php require_once('layout/footer.php') ?>
<?php endif; ?>
