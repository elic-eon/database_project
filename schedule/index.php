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
		if ($searchField == 'destination_name')
			$searchField = 'airport.name';
		$search = " AND LOWER($searchField) LIKE '%$searchKeyword%' ";
	}

	// If user defines some kind of sort
	if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
		$orderKey = addslashes($_GET['orderKey']);
		$orderDirection = addslashes($_GET['orderDirection']);
		$order = "$orderKey $orderDirection, $order";
	}

	$sql = "SELECT tmp.*, airport.name AS destination_name ".
		   "FROM ".
		   "(".
		   "		SELECT flight.*, airport.name AS departure_name ".
		   "		FROM flight, airport ".
		   "		WHERE flight.departure_id = airport.id ".
		   ") AS tmp, airport ".
		   "WHERE tmp.destination_id = airport.id $search ".
		   "ORDER BY $order";
	$sth = $db->prepare($sql);
	$sth->execute();
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
						<option value="departure_name">Departure</option>
						<option value="destination_name">Destination</option>
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
		<table class="table table-condensed table-hover" id="schedule">
			<thead id="schedule_head">
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
							<td style="width: 80px;">$ <?php echo $result->price ?></td>
							<td>
								<?php if ($isAdmin): ?>
									<a class="btn btn-xs btn-warning" href="edit.php?id=<?php echo $result->id ?>">Edit</a>
									<div class="btn-group">
										<button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">
											Other <span class="caret"></span>
										</button>
										<ul class="dropdown-menu pull-right" role="menu">
											<li><a href="../comparison/add_func.php?id=<?php echo $result->id ?>">Add to comparison sheet</a></li>
											<li class="divider"></li>
											<li><a href="delete_func.php?id=<?php echo $result->id ?>">Delete</a></li>
										</ul>
									</div>
								<?php else: ?>
									<a class="btn btn-xs btn-default" href="../comparison/add_func.php?id=<?php echo $result->id ?>">Add to comparison sheet</a>
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

<style type="text/css">
	@media(min-width:767px) {
		.affix-top {
			/*position: inherit;*/
		}

		.affix {
			position: fixed;
			top: 51px;
			left: 281px;
			right: 30px;
			background-color: #fff;
			border-bottom: 2px solid #ddd;
			z-index: 999;
		}

		.affix>tr>th {
			border-bottom: 0px solid #ddd !important;
		}

		.affix-bottom {
			position: absolute;
		}
	}
</style>

<script type="text/javascript">
	$(function () {
		// top: 51 + $('#schedule_head').outerHeight(true),
		$('#schedule_head').affix({
			offset: {
				top: $('#schedule_head').position().top,
				bottom: $('#page-wrapper').height() - $('#schedule').position().top - $('#schedule').outerHeight()
			}
		});
	});
</script>

<?php if (isset($_SESSION['searchKeyword'])): ?>
	<script type="text/javascript">
		$(function () {
			$('select[name=searchField] option[value=<?php echo $_SESSION['searchField'] ?>]').attr('selected', 'selected');
		})
	</script>
<?php endif; ?>
<?php require_once('../layout/footer.php') ?>
