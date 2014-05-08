<?php

function generateOrderHtml ($key) {
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$exe_name = $parts[count($parts) - 1];

	// default html
	$html = '<a title="Sort" href="./'.$exe_name.'?orderKey='.$key.'&orderDirection=asc">'.
				' <i class="fa fa-sort"></i>'.
			'</a>';

	// If user defines sort
	if (isset($_GET['orderKey']) && isset($_GET['orderDirection'])) {
		$orderKey = $_GET['orderKey'];
		$orderDirection = $_GET['orderDirection'];

		$params = "?orderKey=$key&orderDirection=asc";
		$newIcon = 'fa fa-sort';
		$newDirection = 'asc';

		if ($key == $orderKey) {
			if ($orderDirection == 'asc') {
				$newIcon = 'fa fa-sort-asc';
				$newDirection = 'desc';
			} else if ($orderDirection == 'desc') {
				$newIcon = 'fa fa-sort-desc';
				$newDirection = 'asc';
			}
			$html = '<a title="Sort" href="./'.$exe_name.'?orderKey='.$key.'&orderDirection='.$newDirection.'">'.
						' <i class="'.$newIcon.'"></i>'.
					'</a>';
		}
	}

	return $html;
}

?>