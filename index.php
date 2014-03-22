<?php require_once('layout/header.php') ?>
<div class="col-sm-12">
  <H2>Flight Scheduld</H2>
</div>
<div class="col-sm-4">
  <ul class="nav nav-pills nav-stacked">
    <li><a href="flight.php">Flight</a></li>
    <li><a href="register.php">Register</a></li>
    <li>
      <?php if ($_SESSION['isAuth']): ?>
        <a href="logout.php">Logout</a>
      <?php else:?>
        <a href="login.php">Login</a>
      <?php endif;?>
    </li>
  </ul>
</div>
<?php require_once('layout/msg.php') ?>
<?php require_once('layout/footer.php') ?>
