<?php
function isDataInvalid () {
	foreach ($_POST as $key => $value) {
		if ($value == '' || preg_match("/ /", $value)) {
			return $key;
		}
	}
	return false;
}
?>
