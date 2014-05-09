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
		<h1 class="page-header">Favorite <small>- Ticket</small></h1>

		<?php
			require_once('../module/db.php');

			$order = '';
			$sth = null;

			// If user defines some kind of sort
			if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
				$orderKey = addslashes($_GET['orderKey']);
				$orderDirection = addslashes($_GET['orderDirection']);
				$order = " ORDER BY $orderKey $orderDirection ";
			}

			$sql =  "SELECT ".
					"    s.*, ".
					"    ADDTIME(ADDTIME(fTime1, IF(fTime2 IS NULL, 0, fTime2)), IF(fTime3 IS NULL, 0, fTime3)) AS fTime, ".
					"    ADDTIME(IF(tTime1_2 IS NULL, 0, tTime1_2), IF(tTime2_3 IS NULL, 0, tTime2_3)) AS tTime, ".
					"    CASE type ".
					"        WHEN 0 THEN price1 ".
					"        WHEN 1 THEN (price1 + price2) * 0.9 ".
					"        WHEN 2 THEN (price1 + price2 + price3) * 0.8 ".
					"    END AS price, ".
					"    dTime1 AS dTime, ".
					"    CASE type ".
					"        WHEN 0 THEN aTime1 ".
					"        WHEN 1 THEN aTime2 ".
					"        WHEN 2 THEN aTime3 ".
					"    END AS aTime ".
					"FROM ( ".
					"    SELECT ".
					"        r.*, ".
					"        TIMEDIFF(r.UTC_A1, r.UTC_D1) AS fTime1, ".
					"        TIMEDIFF(r.UTC_A2, r.UTC_D2) AS fTime2, ".
					"        TIMEDIFF(r.UTC_A3, r.UTC_D3) AS fTime3, ".
					"        TIMEDIFF(r.UTC_D2, r.UTC_A1) AS tTime1_2, ".
					"        TIMEDIFF(r.UTC_D3, r.UTC_A2) AS tTime2_3, ".
					"        (fn2 IS NOT NULL) + (fn3 IS NOT NULL) AS type ".
					"    FROM ( ".
					"        SELECT ".
					"            r2.*, ".
					"            f.id AS id3, ".
					"            f.flight_number AS fn3, ".
					"            f.departure AS dep3, ".
					"            f.destination AS des3, ".
					"            f.departure_date AS dTime3, ".
					"            f.arrival_date AS aTime3, ".
					"            f.price AS price3, ".
					"            (f.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = f.destination) MINUTE) AS UTC_A3, ".
					"            (f.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = f.departure) MINUTE) AS UTC_D3 ".
					"        FROM ( ".
					"            SELECT ".
					"                r1.*, ".
					"                f.id AS id2, ".
					"                f.flight_number AS fn2, ".
					"                f.departure AS dep2, ".
					"                f.destination AS des2, ".
					"                f.departure_date AS dTime2, ".
					"                f.arrival_date AS aTime2, ".
					"                f.price AS price2, ".
					"                (f.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = f.destination) MINUTE) AS UTC_A2, ".
					"                (f.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = f.departure) MINUTE) AS UTC_D2 ".
					"            FROM ( ".
					"                SELECT ".
					"                    u.*, ".
					"                    f.id AS id1, ".
					"                    f.flight_number AS fn1, ".
					"                    f.departure AS dep1, ".
					"                    f.destination AS des1, ".
					"                    f.departure_date AS dTime1, ".
					"                    f.arrival_date AS aTime1, ".
					"                    f.price AS price1, ".
					"                    (f.arrival_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = f.destination) MINUTE) AS UTC_A1, ".
					"                    (f.departure_date - INTERVAL (SELECT timezone_minute FROM airport WHERE name = f.departure) MINUTE) AS UTC_D1 ".
					"                FROM ( ".
					"                    SELECT ".
					"                        * ".
					"                    FROM ".
					"                        favoriteTicket ".
					"                    WHERE ".
					"                        userID = ? ".
					"                ) AS u ".
					"                JOIN flight AS f ON u.flightNumber1 = f.flight_number ".
					"            ) AS r1 LEFT JOIN flight AS f ON flightNumber2 = f.flight_number ".
					"        ) AS r2 LEFT JOIN flight AS f ON flightNumber3 = f.flight_number ".
					"    ) AS r ".
					") AS s ".
					"$order ";
			$sth = $db->prepare($sql);
			$sth->execute(array($_SESSION['uid']));
		?>
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
					<th style="width: 120px;">Total Flight Time<?php echo generateOrderHtml('fTime') ?></th>
					<th style="width: 100px;">Transfer Time<?php echo generateOrderHtml('tTime') ?></th>
					<th style="width: 80px;">Price<?php echo generateOrderHtml('price') ?></th>
					<th>Operation</th>
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
								<td style="width: 90px;"><?php echo $result->fn1 ?></td>
								<td style="width: 70px;"><?php echo $result->dep1 ?></td>
								<td style="width: 80px;"><?php echo $result->des1 ?></td>
								<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->dTime1) ?></td>
								<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->aTime1) ?></td>
								<td style="width: 75px;"><?php echo $result->fTime1 ?></td>
								<td style="width: 120px;"><?php echo $result->fTime ?></td>
								<td style="width: 100px;"> - </td>
								<td style="width: 80px;">$ <?php echo $result->price ?></td>
								<td>
									<a class="btn btn-xs btn-danger" href="deleteTicket_func.php?id=<?php echo $result->id ?>" title="Delete"><i class="fa fa-trash-o"></i></a>
								</td>
							</tr>
						<?php elseif ($result->type == 1): ?>
							<tr>
								<td rowspan="2" style="width: 50px;"><?php echo $i ?></td>
								<td style="width: 90px;"><?php echo $result->fn1 ?></td>
								<td style="width: 70px;"><?php echo $result->dep1 ?></td>
								<td style="width: 80px;"><?php echo $result->des1 ?></td>
								<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->dTime1) ?></td>
								<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->aTime1) ?></td>
								<td style="width: 75px;"><?php echo $result->fTime1 ?></td>
								<td rowspan="2" style="width: 120px;"><?php echo $result->fTime ?></td>
								<td rowspan="2" style="width: 100px;"><?php echo $result->tTime ?></td>
								<td rowspan="2" style="width: 80px;">$ <?php echo $result->price ?></td>
								<td>
									<a class="btn btn-xs btn-danger" href="deleteTicket_func.php?id=<?php echo $result->id ?>" title="Delete"><i class="fa fa-trash-o"></i></a>
								</td>
							</tr>
							<tr>
								<td style="width: 90px;"><?php echo $result->fn2 ?></td>
								<td style="width: 70px;"><?php echo $result->dep2 ?></td>
								<td style="width: 80px;"><?php echo $result->des2 ?></td>
								<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->dTime2) ?></td>
								<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->aTime2) ?></td>
								<td style="width: 75px;"><?php echo $result->fTime2 ?></td>
							</tr>
						<?php elseif ($result->type == 2): ?>
							<tr>
								<td rowspan="3" style="width: 50px;"><?php echo $i ?></td>
								<td style="width: 90px;"><?php echo $result->fn1 ?></td>
								<td style="width: 70px;"><?php echo $result->dep1 ?></td>
								<td style="width: 80px;"><?php echo $result->des1 ?></td>
								<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->dTime1) ?></td>
								<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->aTime1) ?></td>
								<td style="width: 75px;"><?php echo $result->fTime1 ?></td>
								<td rowspan="3" style="width: 120px;"><?php echo $result->fTime ?></td>
								<td rowspan="3" style="width: 100px;"><?php echo $result->tTime ?></td>
								<td rowspan="3" style="width: 80px;">$ <?php echo $result->price ?></td>
								<td>
									<a class="btn btn-xs btn-danger" href="deleteTicket_func.php?id=<?php echo $result->id ?>" title="Delete"><i class="fa fa-trash-o"></i></a>
								</td>
							</tr>
							<tr>
								<td style="width: 90px;"><?php echo $result->fn2 ?></td>
								<td style="width: 70px;"><?php echo $result->dep2 ?></td>
								<td style="width: 80px;"><?php echo $result->des2 ?></td>
								<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->dTime2) ?></td>
								<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->aTime2) ?></td>
								<td style="width: 75px;"><?php echo $result->fTime2 ?></td>
							</tr>
							<tr>
								<td style="width: 90px;"><?php echo $result->fn3 ?></td>
								<td style="width: 70px;"><?php echo $result->dep3 ?></td>
								<td style="width: 80px;"><?php echo $result->des3 ?></td>
								<td style="width: 115px;"><?php echo str_replace(' ', '<br>', $result->dTime3) ?></td>
								<td style="width: 95px;"><?php echo str_replace(' ', '<br>', $result->aTime3) ?></td>
								<td style="width: 75px;"><?php echo $result->fTime3 ?></td>
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

<style type="text/css">
	#datalist th {
		font-size: 10px;
	}
</style>

<?php require_once('../layout/footer.php') ?>
