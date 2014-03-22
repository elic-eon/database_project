<?php
session_start();
if (!$_SESSION['isAuth'] || !$_SESSION['isAdmin']):
	$redirectURL = '403.php';
	header('Location: '.$redirectURL);
else:
	require_once('db.php');
	$sql = "SELECT * FROM flight WHERE id = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($_GET['id']));
	$result = $sth->fetchObject();
	require_once('layout/header.php');
	require_once('layout/msg.php');
?>
  <div class="col-md-6">
    <?php require_once('layout/msg.php') ?>
    <form action="flight_edit_func.php" method="post" role="form">
      <div class="form-group">
        <input name="id" type="hidden" value="<?php echo $result->id ?>">
      </div>
      <div class="form-group">
        <label class="col-sm-4 control-label">Flight Number</label>
        <div class="col-sm-8">
          <input name="flightNumber" type="text" class="form-control" value="<?php echo $result->flight_number ?>"></p>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-4 control-label">Departure</label>
        <div class="col-sm-8">
          <input name="departure" type="text" class="form-control" value="<?php echo $result->departure ?>"></p>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-4 control-label">Destination</label>
        <div class="col-sm-8">
          <input name="destination" type="text" class="form-control" value="<?php echo $result->destination ?>"></p>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-4 control-label">Departure Date</label>
        <div class="col-sm-8">
          <input name="departureDate" type="datetime-local" class="form-control" value="<?php echo strftime('%Y-%m-%dT%H:%M:%S', strtotime($result->departure_date)) ?>"></p>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-4 control-label">Arrival Date</label>
        <div class="col-sm-8">
          <input name="arrivalDate" type="datetime-local" class = "form-control" value="<?php echo strftime('%Y-%m-%dT%H:%M:%S', strtotime($result->arrival_date)) ?>"></p>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8"><input type="submit" class="btn btn-default"value="Update">   |   <a href="flight.php">Cancel</a></div>
      </div>
    </form>
  </div>
<?php
	require_once('layout/footer.php');
endif;
?>
