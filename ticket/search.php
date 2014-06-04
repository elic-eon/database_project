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

<?php require_once('../module/db.php'); ?>
<?php require_once('../module/generateOrderHtml.php') ?>
<?php require_once('../module/getAirports.php') ?>
<?php require_once('../layout/header.php') ?>
<?php require_once('../layout/msg.php') ?>

<?php
	$order = '';

	// If user defines some kind of sort
	if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
		$orderKey = addslashes($_GET['orderKey']);
		$orderDirection = addslashes($_GET['orderDirection']);
		$order = " ORDER BY $orderKey $orderDirection ";
	}

	if (isset($_SESSION['departure']) || isset($_POST['departure'])) {
		$_SESSION['departure'] = (isset($_POST['departure'])? $_POST['departure']: $_SESSION['departure']);
		$_SESSION['destination'] = (isset($_POST['destination'])? $_POST['destination']: $_SESSION['destination']);
		$_SESSION['maxTransfer'] = (isset($_POST['maxTransfer'])? $_POST['maxTransfer']: $_SESSION['maxTransfer']);
		$_SESSION['overnight'] = (isset($_POST['overnight'])? $_POST['overnight']: $_SESSION['overnight']);

		$overnight = '';
		// No overnight
		if ($_SESSION['overnight'] == 0) {
			$overnight = " AND ".
						 "CASE type ".
						 "    WHEN 0 THEN TRUE ".
						 "    WHEN 1 THEN (UTC_D2 - INTERVAL 12 HOUR < UTC_A1) ".
						 "    WHEN 2 THEN (UTC_D2 - INTERVAL 12 HOUR < UTC_A1) && (UTC_D3 - INTERVAL 12 HOUR < UTC_A2) ".
						 "END ";
		}
		// should overnight
		else if ($_SESSION['overnight'] == 1) {
			$overnight = " AND ".
						 "CASE type ".
						 "    WHEN 0 THEN FALSE ".
						 "    WHEN 1 THEN (UTC_D2 - INTERVAL 12 HOUR >= UTC_A1) ".
						 "    WHEN 2 THEN (UTC_D2 - INTERVAL 12 HOUR >= UTC_A1) || (UTC_D3 - INTERVAL 12 HOUR >= UTC_A2) ".
						 "END ";
		}
		// Either overnight or not
		else {
			$overnight = " AND TRUE";
		}

		$sql =  "SELECT ".
				"    *, ".
				"    TIMEDIFF(totalTime, transferTime) AS flightTime ".
				"FROM ( ".
				"    SELECT ".
				"        0 AS type, price, ".
				"        flight_number1, departure1, destination1, departure_date1, arrival_date1, TIMEDIFF(UTC_A1, UTC_D1) AS flightTime1, ".
				"        NULL AS flight_number2, NULL AS departure2, NULL AS destination2, NULL AS departure_date2, NULL AS arrival_date2, NULL AS flightTime2, ".
				"        NULL AS flight_number3, NULL AS departure3, NULL AS destination3, NULL AS departure_date3, NULL AS arrival_date3, NULL AS flightTime3, ".
				"        0 AS transferTime, TIMEDIFF(UTC_A1, UTC_D1) AS totalTime, ".
				"        departure_date1 AS dTime, arrival_date1 AS aTime, ".
				"        (SELECT id FROM favoriteTicket WHERE userId = ? AND flightNumber1 = r1.flight_number1 AND flightNumber2 IS NULL AND flightNumber3 IS NULL) AS favoriteId, ".
				"        UTC_A1, UTC_D1, NULL AS UTC_A2, NULL AS UTC_D2, NULL AS UTC_A3, NULL AS UTC_D3 ".
				"    FROM ( ".
				"        SELECT ".
				"            flight_number AS flight_number1, ".
				"            departure AS departure1, ".
				"            destination AS destination1, ".
				"            departure_date AS departure_date1, ".
				"            arrival_date AS arrival_date1, ".
				"            (arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = destination) MINUTE) AS UTC_A1, ".
				"            (departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = departure) MINUTE) AS UTC_D1, ".
				"            price ".
				"        FROM ".
				"            flight ".
				"        WHERE ".
				"            departure = ? AND ".
				"            destination = ? ".
				"    ) AS r1 UNION ".
				"    SELECT ".
				"        1 AS type, price, ".
				"        flight_number1, departure1, destination1, departure_date1, arrival_date1, TIMEDIFF(UTC_A1, UTC_D1) AS flightTime1, ".
				"        flight_number2, departure2, destination2, departure_date2, arrival_date2, TIMEDIFF(UTC_A2, UTC_D2) AS flightTime2, ".
				"        NULL AS flight_number2, NULL AS departure2, NULL AS destination2, NULL AS departure_date2, NULL AS arrival_date2, NULL AS flightTime2, ".
				"        TIMEDIFF(UTC_D2, UTC_A1) AS transferTime, TIMEDIFF(UTC_A2, UTC_D1) AS totalTime, ".
				"        departure_date1 AS dTime, arrival_date2 AS aTime, ".
				"        (SELECT id FROM favoriteTicket WHERE userId = ? AND flightNumber1 = r2.flight_number1 AND flightNumber2 = r2.flight_number2 AND flightNumber3 IS NULL) AS favoriteId, ".
				"        UTC_A1, UTC_D1, UTC_A2, UTC_D2, NULL AS UTC_A3, NULL AS UTC_D3 ".
				"    FROM ( ".
				"        SELECT ".
				"            S.flight_number AS flight_number1, ".
				"            S.departure AS departure1, ".
				"            S.destination AS destination1, ".
				"            S.departure_date AS departure_date1, ".
				"            S.arrival_date AS arrival_date1, ".
				"            (S.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.destination) MINUTE) AS UTC_A1, ".
				"            (S.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.departure) MINUTE) AS UTC_D1, ".
				"            T.flight_number AS flight_number2, ".
				"            T.departure AS departure2, ".
				"            T.destination AS destination2, ".
				"            T.departure_date AS departure_date2, ".
				"            T.arrival_date AS arrival_date2, ".
				"            (T.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.destination) MINUTE) AS UTC_A2, ".
				"            (T.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.departure) MINUTE) AS UTC_D2, ".
				"            (S.price + T.price) * 0.9 AS price ".
				"        FROM ".
				"            flight AS S JOIN ".
				"            flight AS T ".
				"        WHERE ".
				"            S.departure = ? AND ".
				"            T.destination = ? AND ".
				"            S.destination = T.departure AND ".
				"            S.arrival_date + INTERVAL 2 HOUR <= T.departure_date ".
				"    ) AS r2 UNION ".
				"    SELECT ".
				"        2 AS type, price, ".
				"        flight_number1, departure1, destination1, departure_date1, arrival_date1, TIMEDIFF(UTC_A1, UTC_D1) AS flightTime1, ".
				"        flight_number2, departure2, destination2, departure_date2, arrival_date2, TIMEDIFF(UTC_A2, UTC_D2) AS flightTime2, ".
				"        flight_number3, departure3, destination3, departure_date3, arrival_date3, TIMEDIFF(UTC_A3, UTC_D3) AS flightTime3, ".
				"        ADDTIME(TIMEDIFF(UTC_D2, UTC_A1), TIMEDIFF(UTC_D3, UTC_A2)) AS transferTime, TIMEDIFF(UTC_A3, UTC_D1) AS totalTime, ".
				"        departure_date1 AS dTime, arrival_date3 AS aTime, ".
				"        (SELECT id FROM favoriteTicket WHERE userId = ? AND flightNumber1 = r3.flight_number1 AND flightNumber2 = r3.flight_number2 AND flightNumber3 = r3.flight_number3) AS favoriteId, ".
				"        UTC_A1, UTC_D1, UTC_A2, UTC_D2, UTC_A3, UTC_D3 ".
				"    FROM ( ".
				"        SELECT ".
				"            S.flight_number AS flight_number1, ".
				"            S.departure AS departure1, ".
				"            S.destination AS destination1, ".
				"            S.departure_date AS departure_date1, ".
				"            S.arrival_date AS arrival_date1, ".
				"            (S.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.destination) MINUTE) AS UTC_A1, ".
				"            (S.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.departure) MINUTE) AS UTC_D1, ".
				"            T.flight_number AS flight_number2, ".
				"            T.departure AS departure2, ".
				"            T.destination AS destination2, ".
				"            T.departure_date AS departure_date2, ".
				"            T.arrival_date AS arrival_date2, ".
				"            (T.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.destination) MINUTE) AS UTC_A2, ".
				"            (T.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.departure) MINUTE) AS UTC_D2, ".
				"            U.flight_number AS flight_number3, ".
				"            U.departure AS departure3, ".
				"            U.destination AS destination3, ".
				"            U.departure_date AS departure_date3, ".
				"            U.arrival_date AS arrival_date3, ".
				"            (U.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = U.destination) MINUTE) AS UTC_A3, ".
				"            (U.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = U.departure) MINUTE) AS UTC_D3, ".
				"            (S.price + T.price + U.price) * 0.8 AS price ".
				"        FROM ".
				"            flight AS S JOIN ".
				"            flight AS T JOIN ".
				"            flight AS U ".
				"        WHERE ".
				"            S.departure = ? AND ".
				"            U.destination = ? AND ".
				"            S.destination = T.departure AND ".
				"            T.destination = U.departure AND ".
				"            T.destination != S.departure AND ".
				"            U.destination != S.departure AND ".
				"            S.arrival_date + INTERVAL 2 HOUR <= T.departure_date AND ".
				"            T.arrival_date + INTERVAL 2 HOUR <= U.departure_date ".
				"    ) AS r3 ".
				") AS r ".
				"WHERE ".
				"    type <= ? $overnight".
				"$order ";
		$sth = $db->prepare($sql);
		$sth->execute(array(
			$_SESSION['uid'],
			$_SESSION['departure'],
			$_SESSION['destination'],
			$_SESSION['uid'],
			$_SESSION['departure'],
			$_SESSION['destination'],
			$_SESSION['uid'],
			$_SESSION['departure'],
			$_SESSION['destination'],
			$_SESSION['maxTransfer']
		));
		$rowCount = $sth->rowCount();
	}
?>

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Ticket Search</h1>
		<div class="well">
			<form action="search.php" method="post" class="form-horizontal" role="form">
				<div class="form-group">
					<label class="col-sm-2 control-label">Departure</label>
					<div class="col-sm-5">
						<select name="departure" style="width:100%;">
							<?php echo $airportOptions; ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label">Destination</label>
					<div class="col-sm-5">
						<select name="destination" style="width:100%;">
							<?php echo $airportOptions; ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label">Max transfer time</label>
					<div class="col-sm-6">
						<label class="radio-inline">
							<input type="radio" name="maxTransfer" value="0" checked> No transfer
						</label>
						<label class="radio-inline">
							<input type="radio" name="maxTransfer" value="1"> 1 time
						</label>
						<label class="radio-inline">
							<input type="radio" name="maxTransfer" value="2"> 2 times
						</label>
						<span class="help-block">Tourist may get discount of tickets as the time of transfer goes up.</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label">Overnight</label>
					<div class="col-sm-5">
						<label class="radio-inline">
							<input type="radio" name="overnight" value="-1" checked> Ignore
						</label>
						<label class="radio-inline">
							<input type="radio" name="overnight" value="0"> No
						</label>
						<label class="radio-inline">
							<input type="radio" name="overnight" value="1"> Yes
						</label>
						<span class="help-block">Transfer time in one transit airport is larger than 12 hours.</span>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-8">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
						<?php if (isset($_SESSION['departure'])): ?>
							<a class="btn btn-danger" href="deleteSearch_func.php"><i class="fa fa-times"></i> Cancel</a>
						<?php endif; ?>
					</div>
				</div>
			</form>
		</div>

		<?php $isAuth = $_SESSION['isAuth']; ?>

		<!-- If there is a query -->
		<?php if (isset($_SESSION['departure'])): ?>
			<!-- If no such ticket -->
			<?php if (!$rowCount): ?>
				No matches was found.
			<?php else: ?>
				<table class="table table-condensed" id="datalist">
					<thead id="datalist_head">
						<tr>
							<th style="width: 50px;">Result</th>
							<th style="width: 90px;">Flight number</th>
							<th style="width: 70px;">Departure</th>
							<th style="width: 80px;">Destination</th>
							<th style="width: 115px;">Departure Time<?php echo generateOrderHtml('dTime') ?></th>
							<th style="width: 95px;">Arrival Time<?php echo generateOrderHtml('aTime') ?></th>
							<th style="width: 75px;">Flight Time</th>
							<th style="width: 120px;">Total Flight Time<?php echo generateOrderHtml('flightTime') ?></th>
							<th style="width: 100px;">Transfer Time<?php echo generateOrderHtml('transferTime') ?></th>
							<th <?php if ($isAuth): ?>style="width: 80px;"<?php endif; ?>>Price<?php echo generateOrderHtml('price') ?></th>
							<?php if ($isAuth): ?>
								<th>Operation</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 1;
							while ($result = $sth->fetchObject()) {
						?>
								<?php if ($result->type == 0): ?>
									<tr>
										<td style="width: 50px;"><?php echo $i ?></td>
										<td style="width: 90px;"><?php echo $result->flight_number1 ?></td>
										<td style="width: 70px;"><?php echo $result->departure1 ?></td>
										<td style="width: 80px;"><?php echo $result->destination1 ?></td>
										<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->departure_date1) ?></td>
										<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->arrival_date1) ?></td>
										<td style="width: 75px;"><?php echo $result->flightTime1 ?></td>
										<td style="width: 120px;"><?php echo $result->flightTime ?></td>
										<td style="width: 100px;"> - </td>
										<td <?php if ($isAuth): ?>style="width: 80px;"<?php endif; ?>>$ <?php echo $result->price ?></td>
										<?php if ($isAuth): ?>
											<td>
												<?php if ($result->favoriteId): ?>
													<a class="btn btn-xs btn-warning" href="../favorite/deleteTicket_func.php?id=<?php echo $result->favoriteId ?>&redirect=s" title="Remove favorite"><i class="fa fa-heart"></i></a>
												<?php else: ?>
													<a class="btn btn-xs btn-default" href="../favorite/addTicket_func.php?number=<?php echo $result->flight_number1 ?>" title="Add to favorite"><i class="fa fa-heart"></i></a>
												<?php endif; ?>
											</td>
										<?php endif; ?>
									</tr>
								<?php elseif ($result->type == 1): ?>
									<tr>
										<td rowspan="2" style="width: 50px;"><?php echo $i ?></td>
										<td style="width: 90px;"><?php echo $result->flight_number1 ?></td>
										<td style="width: 70px;"><?php echo $result->departure1 ?></td>
										<td style="width: 80px;"><?php echo $result->destination1 ?></td>
										<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->departure_date1) ?></td>
										<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->arrival_date1) ?></td>
										<td style="width: 75px;"><?php echo $result->flightTime1 ?></td>
										<td rowspan="2" style="width: 120px;"><?php echo $result->flightTime ?></td>
										<td rowspan="2" style="width: 100px;"><?php echo $result->transferTime ?></td>
										<td rowspan="2" <?php if ($isAuth): ?>style="width: 80px;"<?php endif; ?>>$ <?php echo $result->price ?></td>
										<?php if ($isAuth): ?>
											<td rowspan="2">
												<?php if ($result->favoriteId): ?>
													<a class="btn btn-xs btn-warning" href="../favorite/deleteTicket_func.php?id=<?php echo $result->favoriteId ?>&redirect=s" title="Remove favorite"><i class="fa fa-heart"></i></a>
												<?php else: ?>
													<a class="btn btn-xs btn-default" href="../favorite/addTicket_func.php?number=<?php echo $result->flight_number1.','.$result->flight_number2 ?>" title="Add to favorite"><i class="fa fa-heart"></i></a>
												<?php endif; ?>
											</td>
										<?php endif; ?>
									</tr>
									<tr>
										<td style="width: 90px;"><?php echo $result->flight_number2 ?></td>
										<td style="width: 70px;"><?php echo $result->departure2 ?></td>
										<td style="width: 80px;"><?php echo $result->destination2 ?></td>
										<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->departure_date2) ?></td>
										<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->arrival_date2) ?></td>
										<td style="width: 75px;"><?php echo $result->flightTime2 ?></td>
									</tr>
								<?php elseif ($result->type == 2): ?>
									<tr>
										<td rowspan="3" style="width: 50px;"><?php echo $i ?></td>
										<td style="width: 90px;"><?php echo $result->flight_number1 ?></td>
										<td style="width: 70px;"><?php echo $result->departure1 ?></td>
										<td style="width: 80px;"><?php echo $result->destination1 ?></td>
										<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->departure_date1) ?></td>
										<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->arrival_date1) ?></td>
										<td style="width: 75px;"><?php echo $result->flightTime1 ?></td>
										<td rowspan="3" style="width: 120px;"><?php echo $result->flightTime ?></td>
										<td rowspan="3" style="width: 100px;"><?php echo $result->transferTime ?></td>
										<td rowspan="3" <?php if ($isAuth): ?>style="width: 80px;"<?php endif; ?>>$ <?php echo $result->price ?></td>
										<?php if ($isAuth): ?>
											<td rowspan="3">
												<?php if ($result->favoriteId): ?>
													<a class="btn btn-xs btn-warning" href="../favorite/deleteTicket_func.php?id=<?php echo $result->favoriteId ?>&redirect=s" title="Remove favorite"><i class="fa fa-heart"></i></a>
												<?php else: ?>
													<a class="btn btn-xs btn-default" href="../favorite/addTicket_func.php?number=<?php echo $result->flight_number1.','.$result->flight_number2.','.$result->flight_number3 ?>" title="Add to favorite"><i class="fa fa-heart"></i></a>
												<?php endif; ?>
											</td>
										<?php endif; ?>
									</tr>
									<tr>
										<td style="width: 90px;"><?php echo $result->flight_number2 ?></td>
										<td style="width: 70px;"><?php echo $result->departure2 ?></td>
										<td style="width: 80px;"><?php echo $result->destination2 ?></td>
										<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->departure_date2) ?></td>
										<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->arrival_date2) ?></td>
										<td style="width: 75px;"><?php echo $result->flightTime2 ?></td>
									</tr>
									<tr>
										<td style="width: 90px;"><?php echo $result->flight_number3 ?></td>
										<td style="width: 70px;"><?php echo $result->departure3 ?></td>
										<td style="width: 80px;"><?php echo $result->destination3 ?></td>
										<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->departure_date3) ?></td>
										<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->arrival_date3) ?></td>
										<td style="width: 75px;"><?php echo $result->flightTime3 ?></td>
									</tr>
								<?php endif ?>
						<?php
								$i++;
							}
						?>
					</tbody>
				</table>
			<?php endif; ?>

			<h2>Query</h2>
			<?php 
				// Print the query
				$sql =  "SELECT \n".
						"    *, \n".
						"    TIMEDIFF(totalTime, transferTime) AS flightTime \n".
						"FROM ( \n".
						"    SELECT \n".
						"        0 AS type, price, \n".
						"        flight_number1, departure1, destination1, departure_date1, arrival_date1, TIMEDIFF(UTC_A1, UTC_D1) AS flightTime1, \n".
						"        NULL AS flight_number2, NULL AS departure2, NULL AS destination2, NULL AS departure_date2, NULL AS arrival_date2, NULL AS flightTime2, \n".
						"        NULL AS flight_number3, NULL AS departure3, NULL AS destination3, NULL AS departure_date3, NULL AS arrival_date3, NULL AS flightTime3, \n".
						"        0 AS transferTime, TIMEDIFF(UTC_A1, UTC_D1) AS totalTime, \n".
						"        departure_date1 AS dTime, arrival_date1 AS aTime, \n".
						"        (SELECT id FROM favoriteTicket WHERE userId = ".$_SESSION['uid']." AND flightNumber1 = r1.flight_number1 AND flightNumber2 IS NULL AND flightNumber3 IS NULL) AS favoriteId, \n".
						"        UTC_A1, UTC_D1, NULL AS UTC_A2, NULL AS UTC_D2, NULL AS UTC_A3, NULL AS UTC_D3 \n".
						"    FROM ( \n".
						"        SELECT \n".
						"            flight_number AS flight_number1, \n".
						"            departure AS departure1, \n".
						"            destination AS destination1, \n".
						"            departure_date AS departure_date1, \n".
						"            arrival_date AS arrival_date1, \n".
						"            (arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = destination) MINUTE) AS UTC_A1, \n".
						"            (departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = departure) MINUTE) AS UTC_D1, \n".
						"            price \n".
						"        FROM \n".
						"            flight \n".
						"        WHERE \n".
						"            departure = ".$_SESSION['departure']." AND \n".
						"            destination = ".$_SESSION['destination']." \n".
						"    ) AS r1 UNION \n".
						"    SELECT \n".
						"        1 AS type, price, \n".
						"        flight_number1, departure1, destination1, departure_date1, arrival_date1, TIMEDIFF(UTC_A1, UTC_D1) AS flightTime1, \n".
						"        flight_number2, departure2, destination2, departure_date2, arrival_date2, TIMEDIFF(UTC_A2, UTC_D2) AS flightTime2, \n".
						"        NULL AS flight_number2, NULL AS departure2, NULL AS destination2, NULL AS departure_date2, NULL AS arrival_date2, NULL AS flightTime2, \n".
						"        TIMEDIFF(UTC_D2, UTC_A1) AS transferTime, TIMEDIFF(UTC_A2, UTC_D1) AS totalTime, \n".
						"        departure_date1 AS dTime, arrival_date2 AS aTime, \n".
						"        (SELECT id FROM favoriteTicket WHERE userId = ".$_SESSION['uid']." AND flightNumber1 = r2.flight_number1 AND flightNumber2 = r2.flight_number2 AND flightNumber3 IS NULL) AS favoriteId, \n".
						"        UTC_A1, UTC_D1, UTC_A2, UTC_D2, NULL AS UTC_A3, NULL AS UTC_D3 \n".
						"    FROM ( \n".
						"        SELECT \n".
						"            S.flight_number AS flight_number1, \n".
						"            S.departure AS departure1, \n".
						"            S.destination AS destination1, \n".
						"            S.departure_date AS departure_date1, \n".
						"            S.arrival_date AS arrival_date1, \n".
						"            (S.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.destination) MINUTE) AS UTC_A1, \n".
						"            (S.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.departure) MINUTE) AS UTC_D1, \n".
						"            T.flight_number AS flight_number2, \n".
						"            T.departure AS departure2, \n".
						"            T.destination AS destination2, \n".
						"            T.departure_date AS departure_date2, \n".
						"            T.arrival_date AS arrival_date2, \n".
						"            (T.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.destination) MINUTE) AS UTC_A2, \n".
						"            (T.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.departure) MINUTE) AS UTC_D2, \n".
						"            (S.price + T.price) * 0.9 AS price \n".
						"        FROM \n".
						"            flight AS S JOIN \n".
						"            flight AS T \n".
						"        WHERE \n".
						"            S.departure = ".$_SESSION['departure']." AND \n".
						"            T.destination = ".$_SESSION['destination']." AND \n".
						"            S.destination = T.departure AND \n".
						"            S.arrival_date + INTERVAL 2 HOUR <= T.departure_date \n".
						"    ) AS r2 UNION \n".
						"    SELECT \n".
						"        2 AS type, price, \n".
						"        flight_number1, departure1, destination1, departure_date1, arrival_date1, TIMEDIFF(UTC_A1, UTC_D1) AS flightTime1, \n".
						"        flight_number2, departure2, destination2, departure_date2, arrival_date2, TIMEDIFF(UTC_A2, UTC_D2) AS flightTime2, \n".
						"        flight_number3, departure3, destination3, departure_date3, arrival_date3, TIMEDIFF(UTC_A3, UTC_D3) AS flightTime3, \n".
						"        ADDTIME(TIMEDIFF(UTC_D2, UTC_A1), TIMEDIFF(UTC_D3, UTC_A2)) AS transferTime, TIMEDIFF(UTC_A3, UTC_D1) AS totalTime, \n".
						"        departure_date1 AS dTime, arrival_date3 AS aTime, \n".
						"        (SELECT id FROM favoriteTicket WHERE userId = ".$_SESSION['uid']." AND flightNumber1 = r3.flight_number1 AND flightNumber2 = r3.flight_number2 AND flightNumber3 = r3.flight_number3) AS favoriteId, \n".
						"        UTC_A1, UTC_D1, UTC_A2, UTC_D2, UTC_A3, UTC_D3 \n".
						"    FROM ( \n".
						"        SELECT \n".
						"            S.flight_number AS flight_number1, \n".
						"            S.departure AS departure1, \n".
						"            S.destination AS destination1, \n".
						"            S.departure_date AS departure_date1, \n".
						"            S.arrival_date AS arrival_date1, \n".
						"            (S.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.destination) MINUTE) AS UTC_A1, \n".
						"            (S.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = S.departure) MINUTE) AS UTC_D1, \n".
						"            T.flight_number AS flight_number2, \n".
						"            T.departure AS departure2, \n".
						"            T.destination AS destination2, \n".
						"            T.departure_date AS departure_date2, \n".
						"            T.arrival_date AS arrival_date2, \n".
						"            (T.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.destination) MINUTE) AS UTC_A2, \n".
						"            (T.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = T.departure) MINUTE) AS UTC_D2, \n".
						"            U.flight_number AS flight_number3, \n".
						"            U.departure AS departure3, \n".
						"            U.destination AS destination3, \n".
						"            U.departure_date AS departure_date3, \n".
						"            U.arrival_date AS arrival_date3, \n".
						"            (U.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = U.destination) MINUTE) AS UTC_A3, \n".
						"            (U.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = U.departure) MINUTE) AS UTC_D3, \n".
						"            (S.price + T.price + U.price) * 0.8 AS price \n".
						"        FROM \n".
						"            flight AS S JOIN \n".
						"            flight AS T JOIN \n".
						"            flight AS U \n".
						"        WHERE \n".
						"            S.departure = ".$_SESSION['departure']." AND \n".
						"            U.destination = ".$_SESSION['destination']." AND \n".
						"            S.destination = T.departure AND \n".
						"            T.destination = U.departure AND \n".
						"            T.destination != S.departure AND \n".
						"            U.destination != S.departure AND \n".
						"            S.arrival_date + INTERVAL 2 HOUR <= T.departure_date AND \n".
						"            T.arrival_date + INTERVAL 2 HOUR <= U.departure_date \n".
						"    ) AS r3 \n".
						") AS r \n".
						"WHERE \n".
						"    type <= ".$_SESSION['maxTransfer']." $overnight\n".
						"$order ";
			?>
			<pre><?php echo $sql; ?></pre>
		<?php endif; ?>
				
	</div>
	<!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<?php if (isset($_SESSION['departure']) && $rowCount): ?>
	<link href="<?php echo PATH_ROOT_URL; ?>/asset/css/table.css" rel="stylesheet">
	<script src="<?php echo PATH_ROOT_URL; ?>/asset/js/table.js"></script>
<?php endif; ?>

<link href="<?php echo PATH_ROOT_URL; ?>/asset/js/plugins/select2-3.4.8/select2.css" rel="stylesheet">
<script src="<?php echo PATH_ROOT_URL; ?>/asset/js/plugins/select2-3.4.8/select2.min.js"></script>

<style type="text/css">
	th {
		font-size: 10px;
	}
</style>
<?php if (isset($_SESSION['departure'])): ?>
	<script type="text/javascript">
		$(function () {
			$('select[name=departure] option[value=<?php echo $_SESSION['departure'] ?>]').attr('selected', 'selected');
			$('select[name=destination] option[value=<?php echo $_SESSION['destination'] ?>]').attr('selected', 'selected');
			// $('select[name=maxTransfer] option[value=<?php echo $_SESSION['maxTransfer'] ?>]').attr('selected', 'selected');
			$('input[name=maxTransfer][value=<?php echo $_SESSION['maxTransfer'] ?>]').attr('checked', 'checked');
			$('input[name=overnight][value=<?php echo $_SESSION['overnight'] ?>]').attr('checked', 'checked');
		})
	</script>
<?php endif; ?>

<script type="text/javascript">
	$(function () {
		$("select[name=departure]").select2();
		$("select[name=destination]").select2();
	})
</script>

<?php require_once('../layout/footer.php') ?>
