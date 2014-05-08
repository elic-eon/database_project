<?php
require_once('../config.php');
require_once('../module/checkData.php');
session_save_path(PATH_SESSION_STORE);
session_start();

$key = isDataInvalid();
if ($key) {
	$_SESSION['msg'] = "$key cannot be empty.";
	$redirectURL = './';
	header('Location: '.$redirectURL);
	exit;
}
?>

<?php /*require_once('../module/generateOrderHtml.php')*/ ?>
<?php require_once('../layout/header.php') ?>
<?php require_once('../layout/msg.php') ?>

<?php
	require_once('../module/db.php');

	// $order = 'flight_number ASC';
	// $search = '';
	// $sth = null;

	// if ((isset($_POST['searchField']) && isset($_POST['searchKeyword'])) || (isset($_SESSION['searchKeyword']) && isset($_SESSION['searchField']))) {
	// 	$searchField = isset($_POST['searchField'])? addslashes($_POST['searchField']): $_SESSION['searchField'];
	// 	$searchKeyword = isset($_POST['searchKeyword'])? addslashes($_POST['searchKeyword']): $_SESSION['searchKeyword'];
	// 	$searchKeyword = strtolower($searchKeyword);
	// 	$_SESSION['searchField'] = $searchField;
	// 	$_SESSION['searchKeyword'] = $searchKeyword;
	// 	if ($searchField == 'destination')
	// 		$searchField = 'destination';
	// 	$search = " AND LOWER($searchField) LIKE '%$searchKeyword%' ";
	// }

	// // If user defines some kind of sort
	// if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
	// 	$orderKey = addslashes($_GET['orderKey']);
	// 	$orderDirection = addslashes($_GET['orderDirection']);
	// 	$order = "$orderKey $orderDirection, $order";
	// }

	// Get airports
	$sql = "SELECT * FROM airport";
	$sth = $db->prepare($sql);
	$sth->execute();
	$airports = '';
	while ($result = $sth->fetchObject()) {
		$airports .= '<option value="'.$result->name.'">'.$result->fullName.'</option>';
	}

	$_SESSION['departure'] = $_POST['departure'];
	$_SESSION['destination'] = $_POST['destination'];
	$_SESSION['maxTransfer'] = $_POST['maxTransfer'];

	$sql =  "SELECT ".
			" flight_number1, departure1, destination1, departure_date1, arrival_date1, TIMEDIFF(UTC_A1, UTC_D1) AS flightTime1, ".
			"    NULL AS flight_number2, NULL AS departure2, NULL AS destination2, NULL AS departure_date2, NULL AS arrival_date2, NULL AS flightTime2, ".
			"    NULL AS flight_number3, NULL AS departure3, NULL AS destination3, NULL AS departure_date3, NULL AS arrival_date3, NULL AS flightTime3, ".
			"    NULL AS transferTime, ".
			"    price ".
			"FROM ( ".
			"    SELECT ".
			"        flight_number AS flight_number1, ".
			"        departure AS departure1, ".
			"        destination AS destination1, ".
			"        departure_date AS departure_date1, ".
			"        arrival_date AS arrival_date1, ".
			"        (arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = destination) MINUTE) AS UTC_A1, ".
			"        (departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = departure) MINUTE) AS UTC_D1, ".
			"        price ".
			"    FROM ".
			"        flight ".
			"    WHERE ".
			"        departure = ? AND ".
			"        destination = ? ".
			") AS r1 ".

			"UNION ".

			"SELECT ".
			"    flight_number1, departure1, destination1, departure_date1, arrival_date1, TIMEDIFF(UTC_A1, UTC_D1) AS flightTime1, ".
			"    flight_number2, departure2, destination2, departure_date2, arrival_date2, TIMEDIFF(UTC_A2, UTC_D2) AS flightTime2, ".
			"        NULL AS flight_number2, NULL AS departure2, NULL AS destination2, NULL AS departure_date2, NULL AS arrival_date2, NULL AS flightTime2, ".
			"    TIMEDIFF(UTC_D2, UTC_A1) AS transferTime, ".
			"    price ".
			"FROM ( ".
			"    SELECT ".
			"        S.flight_number AS flight_number1, ".
			"        S.departure AS departure1, ".
			"        S.destination AS destination1, ".
			"        S.departure_date AS departure_date1, ".
			"        S.arrival_date AS arrival_date1, ".
			"        (S.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.destination) MINUTE) AS UTC_A1, ".
			"        (S.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.departure) MINUTE) AS UTC_D1, ".
			"     ".
			"        T.flight_number AS flight_number2, ".
			"        T.departure AS departure2, ".
			"        T.destination AS destination2, ".
			"        T.departure_date AS departure_date2, ".
			"        T.arrival_date AS arrival_date2, ".
			"        (T.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.destination) MINUTE) AS UTC_A2, ".
			"        (T.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.departure) MINUTE) AS UTC_D2, ".
			"        (S.price + T.price) * 0.9 AS price ".
			"    FROM ".
			"        flight AS S JOIN ".
			"        flight AS T ".
			"    WHERE ".
			"        S.departure = ? AND ".
			"        T.destination = ? AND ".
			"        S.destination = T.departure AND ".
			"        S.arrival_date + INTERVAL 2 HOUR < T.departure_date ".
			") AS r2 ".

			"UNION ".

			"SELECT ".
			"    flight_number1, departure1, destination1, departure_date1, arrival_date1, TIMEDIFF(UTC_A1, UTC_D1) AS flightTime1, ".
			"    flight_number2, departure2, destination2, departure_date2, arrival_date2, TIMEDIFF(UTC_A2, UTC_D2) AS flightTime2, ".
			"    flight_number3, departure3, destination3, departure_date3, arrival_date3, TIMEDIFF(UTC_A3, UTC_D3) AS flightTime3, ".
			"    ADDTIME(TIMEDIFF(UTC_D2, UTC_A1), TIMEDIFF(UTC_D3, UTC_A2)) AS transferTime, ".
			"    price ".
			"FROM ( ".
			"    SELECT ".
			"        S.flight_number AS flight_number1, ".
			"        S.departure AS departure1, ".
			"        S.destination AS destination1, ".
			"        S.departure_date AS departure_date1, ".
			"        S.arrival_date AS arrival_date1, ".
			"        (S.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.destination) MINUTE) AS UTC_A1, ".
			"        (S.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.departure) MINUTE) AS UTC_D1, ".
			"     ".
			"        T.flight_number AS flight_number2, ".
			"        T.departure AS departure2, ".
			"        T.destination AS destination2, ".
			"        T.departure_date AS departure_date2, ".
			"        T.arrival_date AS arrival_date2, ".
			"        (T.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.destination) MINUTE) AS UTC_A2, ".
			"        (T.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.departure) MINUTE) AS UTC_D2, ".

			"        U.flight_number AS flight_number3, ".
			"        U.departure AS departure3, ".
			"        U.destination AS destination3, ".
			"        U.departure_date AS departure_date3, ".
			"        U.arrival_date AS arrival_date3, ".
			"        (U.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = U.destination) MINUTE) AS UTC_A3, ".
			"        (U.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = U.departure) MINUTE) AS UTC_D3, ".

			"        (S.price + T.price + U.price) * 0.8 AS price ".
			"    FROM ".
			"        flight AS S JOIN ".
			"        flight AS T JOIN ".
			"        flight AS U ".
			"    WHERE ".
			"        S.departure = ? AND ".
			"        U.destination = ? AND ".
			"        S.destination = T.departure AND ".
			"        T.destination = U.departure AND ".
			"        S.arrival_date + INTERVAL 2 HOUR < T.departure_date AND ".
			"        T.arrival_date + INTERVAL 2 HOUR < U.departure_date ".
			") AS r3";
	$sth = $db->prepare($sql);
	$sth->execute(array(
		$_POST['departure'],
		$_POST['destination'],
		$_POST['departure'],
		$_POST['destination'],
		$_POST['departure'],
		$_POST['destination']
	));
?>

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Schedule Search</h1>
		<div class="well">
			<form action="advanceSearch.php" method="post" class="form-horizontal" role="form">
				<div class="form-group">
					<label class="col-sm-2 control-label">Departure</label>
					<div class="col-sm-4">
						<select name="departure" class="form-control">
							<?php echo $airports; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Destination</label>
					<div class="col-sm-4">
						<select name="destination" class="form-control">
							<?php echo $airports; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Max transfer time</label>
					<div class="col-sm-4">
						<select name="maxTransfer" class="form-control">
							<option value="0">No transfer</option>
							<option value="1">1 time</option>
							<option value="2">2 times</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-8">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
						<?php if (isset($_SESSION['departure'])): ?>
							<a class="btn btn-danger" href="deleteAdvanceSearch_func.php"><i class="fa fa-times"></i> Cancel</a>
						<?php endif; ?>
					</div>
				</div>
			</form>
		</div>

		<table class="table table-condensed" id="datalist">
			<thead id="datalist_head">
				<tr>
					<th style="width: 70px;">Result</th>
					<th style="width: 140px;">Flight number</th>
					<th style="width: 100px;">Departure</th>
					<th style="width: 100px;">Destination</th>
					<th style="width: 120px;">Departure Time</th>
					<th style="width: 120px;">Arrival Time</th>
					<th style="width: 160px;">Flight Time</th>
					<th style="width: 120px;">Transfer Time</th>
					<th>Price</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$i = 1;
					while ($result = $sth->fetchObject()) {
				?>
						<?php if ($_POST['maxTransfer'] >= 2 &&
								  $result->flight_number1 && 
								  $result->flight_number2 && 
								  $result->flight_number3): ?>
							<tr>
								<td rowspan="3" style="width: 70px;"><?php echo $i ?></td>
								<td style="width: 140px;"><?php echo $result->flight_number1 ?></td>
								<td style="width: 100px;"><?php echo $result->departure1 ?></td>
								<td style="width: 100px;"><?php echo $result->destination1 ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->departure_date1) ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->arrival_date1) ?></td>
								<td style="width: 160px;"><?php echo $result->flightTime1 ?></td>
								<td rowspan="3" style="width: 120px;"><?php echo $result->transferTime ?></td>
								<td rowspan="3">$ <?php echo $result->price ?></td>
							</tr>
							<tr>
								<td style="width: 140px;"><?php echo $result->flight_number2 ?></td>
								<td style="width: 100px;"><?php echo $result->departure2 ?></td>
								<td style="width: 100px;"><?php echo $result->destination2 ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->departure_date2) ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->arrival_date2) ?></td>
								<td style="width: 160px;"><?php echo $result->flightTime2 ?></td>
							</tr>
							<tr>
								<td style="width: 140px;"><?php echo $result->flight_number3 ?></td>
								<td style="width: 100px;"><?php echo $result->departure3 ?></td>
								<td style="width: 100px;"><?php echo $result->destination3 ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->departure_date3) ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->arrival_date3) ?></td>
								<td style="width: 160px;"><?php echo $result->flightTime3 ?></td>
							</tr>
						<?php elseif ($_POST['maxTransfer'] >= 1 &&
									  $result->flight_number1 && 
									  $result->flight_number2 &&  
									  !$result->flight_number3): ?>
							<tr>
								<td rowspan="2" style="width: 70px;"><?php echo $i ?></td>
								<td style="width: 140px;"><?php echo $result->flight_number1 ?></td>
								<td style="width: 100px;"><?php echo $result->departure1 ?></td>
								<td style="width: 100px;"><?php echo $result->destination1 ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->departure_date1) ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->arrival_date1) ?></td>
								<td style="width: 160px;"><?php echo $result->flightTime1 ?></td>
								<td rowspan="2" style="width: 120px;"><?php echo $result->transferTime ?></td>
								<td rowspan="2">$ <?php echo $result->price ?></td>
							</tr>
							<tr>
								<td style="width: 140px;"><?php echo $result->flight_number2 ?></td>
								<td style="width: 100px;"><?php echo $result->departure2 ?></td>
								<td style="width: 100px;"><?php echo $result->destination2 ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->departure_date2) ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->arrival_date2) ?></td>
								<td style="width: 160px;"><?php echo $result->flightTime2 ?></td>
							</tr>
						<?php elseif ($_POST['maxTransfer'] >= 0 &&
									  $result->flight_number1 && 
									  !$result->flight_number2 && 
									  !$result->flight_number3): ?>
							<tr>
								<td style="width: 70px;"><?php echo $i ?></td>
								<td style="width: 140px;"><?php echo $result->flight_number1 ?></td>
								<td style="width: 100px;"><?php echo $result->departure1 ?></td>
								<td style="width: 100px;"><?php echo $result->destination1 ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->departure_date1) ?></td>
								<td style="width: 120px;"><?php echo str_replace(' ', '<br>', $result->arrival_date1) ?></td>
								<td style="width: 160px;"><?php echo $result->flightTime1 ?></td>
								<td style="width: 120px;"> - </td>
								<td>$ <?php echo $result->price ?></td>
							</tr>
						<?php endif ?>
				<?php
						$i++;
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

<?php if (isset($_SESSION['departure'])): ?>
	<script type="text/javascript">
		$(function () {
			$('select[name=departure] option[value=<?php echo $_SESSION['departure'] ?>]').attr('selected', 'selected');
			$('select[name=destination] option[value=<?php echo $_SESSION['destination'] ?>]').attr('selected', 'selected');
			$('select[name=maxTransfer] option[value=<?php echo $_SESSION['maxTransfer'] ?>]').attr('selected', 'selected');
		})
	</script>
<?php endif; ?>
<?php require_once('../layout/footer.php') ?>
