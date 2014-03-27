<?php
require_once('../config.php');
session_save_path(PATH_SESSION_STORE);
session_start();
?>
<?php require_once('../layout/header_general.php') ?>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<h1>403 Forbidden</h1>
		<p>
			<?php if ($_SESSION['isAuth']): ?>
				You don't have permission to access.
			<?php else: ?>
				Please <a href="<?php echo PATH_ROOT_URL; ?>/user/login.php">login</a> first.
			<?php endif; ?>
		</p>
		<a href="<?php echo PATH_ROOT_URL; ?>">Go Home</a>
	</div>
</div>
<?php require_once('../layout/footer_general.php') ?>
