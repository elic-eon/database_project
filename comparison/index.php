<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();

if (!$_SESSION['isAuth']) {
	$redirectURL = PATH_ROOT_URL.'/error/403.php';
	header('Location: '.$redirectURL);
	exit;
}
?>

<?php require_once('../module/generateOrderHtml.php') ?>
<?php require_once('../layout/header.php') ?>
<?php require_once('../layout/msg.php') ?>
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Comparison Sheet</h1>
		<?php
			require_once('../module/db.php');

			$order = 'flight_number ASC';
			$sth = null;

			// If user defines some kind of sort
			if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
				$orderKey = addslashes($_GET['orderKey']);
				$orderDirection = addslashes($_GET['orderDirection']);
				$order = "$orderKey $orderDirection, $order";
			}
			$sql = "SELECT comparison.id AS comparison_id, a.* ".
				   "FROM comparison, ".
				   "( ".
				   "    SELECT tmp.*, airport.name AS destination_name ".
				   "    FROM ".
				   "    ( ".
				   "        SELECT flight.*, airport.name AS departure_name ".
				   "        FROM flight, airport ".
				   "        WHERE flight.departure_id = airport.id ".
				   "    ) AS tmp, airport ".
				   "    WHERE tmp.destination_id = airport.id".
				   ") AS a ".
				   "WHERE a.id = comparison.flight_id AND comparison.user_id = ? ".
				   "ORDER BY $order";
			$sth = $db->prepare($sql);
			$sth->execute(array($_SESSION['uid']));
		?>

		<?php $isAdmin = $_SESSION['isAdmin']; ?>
		<table class="table table-condensed table-hover" id="datalist">
			<thead id="datalist_head">
				<tr>
					<?php if ($isAdmin): ?>
						<th style="width: 70px;">ID<?php echo generateOrderHtml('id') ?></th>
					<?php endif; ?>
					<th style="width: 140px;">Flight number<?php echo generateOrderHtml('flight_number') ?></th>
					<th style="width: 110px;">Departure<?php echo generateOrderHtml('departure_name') ?></th>
					<th style="width: 120px;">Destination<?php echo generateOrderHtml('destination_name') ?></th>
					<th style="width: 160px;">Departure Date<?php echo generateOrderHtml('departure_date') ?></th>
					<th style="width: 160px;">Arrival Date<?php echo generateOrderHtml('arrival_date') ?></th>
					<th style="width: 80px;">Price<?php echo generateOrderHtml('price') ?></th>
					<th>Operation</th>
				</tr>
			</thead>
			<tbody>
				<?php
					while ($result = $sth->fetchObject()) {
				?>
						<tr>
							<?php if ($isAdmin): ?>
								<td style="width: 70px;"><?php echo $result->id ?></td>
							<?php endif; ?>
							<td style="width: 140px;"><?php echo $result->flight_number ?></td>
							<td style="width: 110px;"><?php echo $result->departure_name ?></td>
							<td style="width: 120px;"><?php echo $result->destination_name ?></td>
							<td style="width: 160px;"><?php echo $result->departure_date ?></td>
							<td style="width: 160px;"><?php echo $result->arrival_date ?></td>
							<td style="width: 80px;"><?php echo $result->price ?></td>
							<td>
								<a class="btn btn-xs btn-danger" href="delete_func.php?id=<?php echo $result->comparison_id ?>">Delete</a>
							</td>
						</tr>
				<?php
					}
				?>
			</tbody>
		</table>	
		</div>
		<!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<link href="<?php echo PATH_ROOT_URL; ?>/asset/css/table.css" rel="stylesheet">
<script src="<?php echo PATH_ROOT_URL; ?>/asset/js/table.js"></script>

<?php require_once('../layout/footer.php') ?>
