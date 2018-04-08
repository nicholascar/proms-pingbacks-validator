<?php
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$title = 'Pingback message validator';
        include '/srv/www/promsns.org/html/theme/header.inc';
		
		print '<h1>'. $title .'</h1>';
		
		if (isset($_GET['q'])) {
			if ($_GET['q'] == 'form') {
				include 'form.php';
			} elseif ($_GET['q'] == 'about') {
				include 'about.php';
			} else {
				include 'home.php';
			}
		} else {
			include 'home.php';
		}
		include '/srv/www/promsns.org/html/theme/footer.inc';
	}
