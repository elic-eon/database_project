<?php
if (isset($_SESSION['msg'])) {
?>
  <div class="alert alert-info col-md-4"><?php echo $_SESSION['msg'] ?></div>
<?php
	unset($_SESSION['msg']);
}
?>
