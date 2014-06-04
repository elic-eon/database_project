<?php
require_once('../config.php');
require_once('../module/checkData.php');
session_save_path(PATH_SESSION_STORE);
session_start();

if (!$_SESSION['isAuth']) {
	$redirectURL = PATH_ROOT_URL.'/error/403.php';
	header('Location: '.$redirectURL);
	exit;
}

$key = isDataInvalid();
if ($key) {
	$_SESSION['msg'] = "$key cannot be empty.";
	$redirectURL = './';
	header('Location: '.$redirectURL);
	exit;
}
?>

<?php require_once('../module/generateOrderHtml.php') ?>
<?php require_once('../layout/header.php') ?>
<?php require_once('../layout/msg.php') ?>

<?php
	require_once('../module/db.php');

	$order = 'flight_number ASC';
	$search = '';
	$sth = null;

	if ((isset($_POST['searchField']) && isset($_POST['searchKeyword'])) || (isset($_SESSION['searchKeyword']) && isset($_SESSION['searchField']))) {
		$searchField = isset($_POST['searchField'])? addslashes($_POST['searchField']): $_SESSION['searchField'];
		$searchKeyword = isset($_POST['searchKeyword'])? addslashes($_POST['searchKeyword']): $_SESSION['searchKeyword'];
		$searchKeyword = strtolower($searchKeyword);
		$_SESSION['searchField'] = $searchField;
		$_SESSION['searchKeyword'] = $searchKeyword;
		if ($searchField == 'destination')
			$searchField = 'destination';
		$search = " AND LOWER($searchField) LIKE '%$searchKeyword%' ";
	}

	// If user defines some kind of sort
	if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
		$orderKey = addslashes($_GET['orderKey']);
		$orderDirection = addslashes($_GET['orderDirection']);
		$order = "$orderKey $orderDirection, $order";
	}

	$sql = "SELECT *, flight_number IN (SELECT flightNumber FROM favoriteFlight WHERE userId = ?) AS favorite FROM flight WHERE TRUE $search ORDER BY $order";
	$sth = $db->prepare($sql);
	$sth->execute(array($_SESSION['uid']));
?>

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Schedule</h1>
		<div class="well">
			<?php if ($isAdmin): ?>
				<div class="col-sm-3">
					<a class="btn btn-default btn-block" href="add.php"><i class="fa fa-plus"></i> Add New Schedule</a>
				</div>
			<?php endif; ?>

			<form class="form-inline" role="form" action="index.php" method="post">
				<div class="form-group">
					<select name="searchField" class="form-control" id="searchBy">
						<option value="flight_number">Flight Number</option>
						<option value="departure">Departure</option>
						<option value="destination">Destination</option>
					</select>
				</div>
				<div class="form-group">
					<input name="searchKeyword" type="text" class="form-control" placeholder="Keyword" value="<?php echo $_SESSION['searchKeyword']; ?>">
				</div>
				<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
				<?php if (isset($_SESSION['searchKeyword'])): ?>
					<a class="btn btn-danger" href="deleteSearch_func.php"><i class="fa fa-times"></i> Cancel</a>
				<?php endif; ?>
			</form>
		</div>

		<?php $isAdmin = $_SESSION['isAdmin']; ?>
		<table class="table table-condensed table-hover" id="datalist">
			<thead id="datalist_head">
				<tr>
					<th style="width: 140px;">Flight number<?php echo generateOrderHtml('flight_number') ?></th>
					<th style="width: 110px;">Departure<?php echo generateOrderHtml('departure') ?></th>
					<th style="width: 120px;">Destination<?php echo generateOrderHtml('destination') ?></th>
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
							<td style="width: 140px;"><?php echo $result->flight_number ?></td>
							<td style="width: 110px;"><?php echo $result->departure ?></td>
							<td style="width: 120px;"><?php echo $result->destination ?></td>
							<td style="width: 160px;"><?php echo $result->departure_date ?></td>
							<td style="width: 160px;"><?php echo $result->arrival_date ?></td>
							<td style="width: 80px;">$ <?php echo $result->price ?></td>
							<td>
								<?php if ($result->favorite): ?>
									<a class="btn btn-xs btn-warning" href="../favorite/deleteFlight_func.php?number=<?php echo $result->flight_number ?>&redirect=s" title="Remove favorite"><i class="fa fa-heart"></i></a>
								<?php else: ?>
									<a class="btn btn-xs btn-default" href="../favorite/addFlight_func.php?number=<?php echo $result->flight_number ?>" title="Add to favorite"><i class="fa fa-heart"></i></a>
								<?php endif; ?>
								<?php if ($isAdmin): ?>
									<a class="btn btn-xs btn-default" href="edit.php?number=<?php echo $result->flight_number ?>" title="Edit"><i class="fa fa-pencil"></i></a>
									<a class="btn btn-xs btn-danger" href="delete_func.php?number=<?php echo $result->flight_number ?>" title="Delete"><i class="fa fa-trash-o"></i></a>
								<?php endif; ?>
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

<?php if (isset($_SESSION['searchKeyword'])): ?>
	<script type="text/javascript">
		$(function () {
			$('select[name=searchField] option[value=<?php echo $_SESSION['searchField'] ?>]').attr('selected', 'selected');
		})
	</script>
<?php endif; ?>
<?php require_once('../layout/footer.php') ?>
