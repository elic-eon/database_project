<?php require_once('layout/header_general.php') ?>

<div class="col-sm-4 col-sm-offset-4">
  <?php require_once('layout/msg.php') ?>
  <H2>Flight Schedule</H2>
</div>
<div class="col-sm-4 col-sm-offset-4">
  <ul class="nav nav-pills nav-stacked">
    <li><a href="user/register.php">Register</a></li>
    <li>
      <?php if ($_SESSION['isAuth']): ?>
        <a href="user/logout.php">Logout</a>
      <?php else:?>
        <a href="user/login.php">Login</a>
      <?php endif;?>
    </li>
  </ul>
</div>

<?php require_once('layout/footer_general.php') ?>
