<?php
function isDataInvalid () {
	foreach ($_POST as $key => $value) {
		if ($value == '') {
			return $key;
		}
	}
	return false;
}
?>
