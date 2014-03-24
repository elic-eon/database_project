<?php
if (isset($_SESSION['msg'])) {
?>
  <div class="alert alert-info"><?php echo $_SESSION['msg'] ?></div>
<?php
	unset($_SESSION['msg']);
}
?>
