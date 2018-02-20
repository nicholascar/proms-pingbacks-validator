<?php
	include 'settings.php';

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$title = 'Pingback message validator';
		$main_menu_file = 'nav.inc';
		include 'theme/template-header.inc';
		
		print '<h1>'. $title .'</h1>';
		
		if (isset($_GET['q'])) {
			if ($_GET['q'] == 'form') {
				include 'form.php';
			} elseif ($_GET['q'] == 'about') {
				include 'about.php';
			} elseif ($_GET['q'] == 'contact') {
				include 'contact.php';				
			} else {
				include 'home.php';
			}
		} else {
			include 'home.php';
		}
		include 'theme/template-footer.inc';
	}
