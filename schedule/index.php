<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

if (!$_SESSION['isAuth']) {
	$redirectURL = PATH_ROOT_URL.'/error/403.php';
	header('Location: '.$redirectURL);
	exit;
}

// Prevent injection
global $columnNameMap;
global $directionMap;

$columnNameMap = array(
	'id' => 'id',
	'flight_number' => 'flight_number',
	'departure' => 'departure',
	'destination' => 'destination',
	'departure_date' => 'departure_date',
	'arrival_date' => 'arrival_date',
	'price' => 'price'
);

$directionMap = array(
	'asc' => 'asc',
	'desc' => 'desc'
);

function generateOrderHtml ($key) {
	// default html
	$html = '<a title="Sort" href="./?orderKey='.$key.'&orderDirection=asc">'.
				' <i class="fa fa-sort"></i>'.
			'</a>';;

	// If user defines sort
	if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
		$orderKey = $_GET['orderKey'];
		$orderDirection = $_GET['orderDirection'];

		$params = "?orderKey=$key&orderDirection=asc";
		$newIcon = 'fa fa-sort';
		$newDirection = 'asc';

		if ($key == $orderKey) {
			if ($orderDirection == 'asc') {
				$newIcon = 'fa fa-sort-asc';
				$newDirection = 'desc';
			} else if ($orderDirection == 'desc') {
				$newIcon = 'fa fa-sort-desc';
				$newDirection = 'asc';
			}
			$html = '<a title="Sort" href="./?orderKey='.$key.'&orderDirection='.$newDirection.'">'.
						' <i class="'.$newIcon.'"></i>'.
					'</a>';
		}
	}

	return $html;
}

?>

<?php require_once('../layout/header.php') ?>
<?php require_once('../layout/msg.php') ?>
<div class="row">
    <div class="col-lg-12">
		<h1 class="page-header">Schedule</h1>

		<?php
			require_once('../module/db.php');

			$defaultOrder = 'flight_number ASC';
			$sth = null;

			// If user defines some kind of sort
			if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
				$orderKey = $columnNameMap[$_GET['orderKey']];
				$orderDirection = $directionMap[$_GET['orderDirection']];
				$sql = "SELECT * FROM flight ORDER BY $orderKey $orderDirection, $defaultOrder";
			} else {
				$sql = "SELECT * FROM flight ORDER BY $defaultOrder";
			}
			$sth = $db->prepare($sql);
			$sth->execute();
			
		?>

		<?php $isAdmin = $_SESSION['isAdmin']; ?>
		<table class="table table-condensed table-hover">
			<thead>
				<tr>
					<?php if ($isAdmin): ?>
						<th>id<?php echo generateOrderHtml('id') ?></th>
					<?php endif; ?>
					<th>Flight number<?php echo generateOrderHtml('flight_number') ?></th>
					<th>Departure<?php echo generateOrderHtml('departure') ?></th>
					<th>Destination<?php echo generateOrderHtml('destination') ?></th>
					<th>Departure Date<?php echo generateOrderHtml('departure_date') ?></th>
					<th>Arrival Date<?php echo generateOrderHtml('arrival_date') ?></th>
					<th>Price<?php echo generateOrderHtml('price') ?></th>
					<?php if ($isAdmin): ?>
						<th>Operation</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
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
							<td><?php echo $result->price ?></td>
							<?php if ($isAdmin): ?>
								<td>
									<a class="btn btn-xs btn-block btn-warning" href="edit.php?id=<?php echo $result->id ?>">Edit</a>
									<a class="btn btn-xs btn-block btn-danger" href="delete_func.php?id=<?php echo $result->id ?>">Delete</a>
								</td>
							<?php endif; ?>
						</tr>
				<?php
					}
				?>
				<?php if ($isAdmin): ?>
					<tr>
						<form action="add_func.php" method="post" role="form">
							<td>Quick Add</td>
							<td><input name="flightNumber" type="text" class="form-control input-sm" required></td>
							<td><input name="departure" type="text" class="form-control input-sm" required></td>
							<td><input name="destination" type="text" class="form-control input-sm"  required></td>
							<td><input name="departureDate" type="datetime-local" class="form-control input-sm"  required></td>
							<td><input name="arrivalDate" type="datetime-local" class="form-control input-sm"  required></td>
							<td><input name="price" type="text" class="form-control input-sm"  required></td>
							<td><input type="submit" value="Add" class="btn btn-sm btn-block btn-default" ></td>
						</form>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<?php require_once('../layout/footer.php') ?>
