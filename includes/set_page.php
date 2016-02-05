<?php

	require('includes/get_settings.php');
	
	$settings_array = settings_current();

	if (!isset($_SESSION)) {
		session_start();
	}

	if (!isset($_SESSION['from_date'])) {
		$_SESSION['from_date'] = $settings_array['start_date'];
	}

	if (!isset($_SESSION['to_date'])) {
		$_SESSION['to_date'] = $settings_array['end_date'];
	}

	$date_pick_on = 0;

	if (isset($_POST['email']) && isset($_POST['password'])) {
		if (isset($_POST['remember']) && $_POST['remember'] == "yes") {
			setcookie("pokernola_player", $_POST['email'], time() + (86400 * 365), "/"); // 86400 = 1 day
			setcookie("pokernola_pass", $_POST['password'], time() + (86400 * 365), "/"); // 86400 = 1 day
		} else {
			setcookie("pokernola_player", "", time() + (86400 * 365), "/"); // 86400 = 1 day
			setcookie("pokernola_pass", "", time() + (86400 * 365), "/"); // 86400 = 1 day
		}
	}
	
	// Convert date functions
	function date_to_php($date) {
		$php_date = date_format(date_create($date), "m-d-Y");

		return $php_date;
	}

	function date_to_mysql($date) {
		$mysql_date = date('Y-m-d', strtotime($date));

		return $mysql_date;
	}

	function date_to_datepicker($date) {
		$datepicker_date = date('m/d/Y', strtotime($date));

		return $datepicker_date;
	}
	
	// Convert time functions
	function time_to_php($time) {
		$php_time = date_format(date_create($time), "h:i:s A");

		return $php_time;
	}

	function time_to_mysql($time) {
		$mysql_time = date('H:i:s', strtotime($time));

		return $mysql_time;
	}
	
	function time_to_datepicker($time) {
		$datepicker_time = date('h:i:s A', strtotime($time));
		
		return $datepicker_time;
	}
	