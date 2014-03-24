<?php
require_once('./config.php');
session_save_path($SESSION_STORE);
session_start();
?>
<?php require_once('layout/header.php') ?>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<h1>403 Forbidden</h1>
		<p>
			<?php if ($_SESSION['isAuth']): ?>
				You don't have permission to access.
			<?php else: ?>
				Please <a href="login.php">login</a> first.
			<?php endif; ?>
		</p>
		<a href="./">Go Home</a>
	</div>
</div>
<?php require_once('layout/footer.php') ?>
