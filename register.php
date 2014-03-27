<?php require_once('layout/header.php'); ?>
<div class="col-md-6">
  <h2>Register a New Account</h2>
  <form action="register_func.php" method="post" role="form">
    <div class="form-group">
      <label for="account" class="control-label">Account</label>
      <div>
        <input name="account" type="text" class="form-control" id="account" placeholder="Your account" required>
      </div>
    </div>
    <div class="form-group">
      <label for="password" class="control-label">Password</label>
      <input name="password" type="password" class="form-control" id="form-group" placeholder="Your password" required>
    </div>
    <div class="checkbox">
    <input name="is_admin" type="checkbox">Register as admin
    </div>
    <div class="form-group">
      <input type="submit" value="Register" class="btn btn-default"></p>
    </div>
  </form>
  <a href="./">Go Home</a>
</div>
<?php require_once('layout/msg.php') ?>
<?php require_once('layout/footer.php') ?>
