<?php
	require('includes/get_settings.php');
	
	date_default_timezone_set("America/Chicago");
	
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
	
	require('includes/get_players.php');
	require('includes/get_games.php');

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
		$mysql_date = date_create_from_format("m-d-Y", $date);
		$mysql_date = date_format($mysql_date, "Y-m-d");

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
	
	// Convert mysql time stamp to php timestamp
	function timestamp_to_php($timestamp) {
		$php_timestamp = date_format(date_create($timestamp), "m-d-Y h:i:s A");

		return $php_timestamp;
	}
	
	// This function recieves the access level of the page that calls it and only allows players with that
	// level to access it. It also checks to make sure a player_id or game_id exists in the query string and
	// that the id is valid when access level is not 'all'.
	function set_page_access($access_level) {
		$player_logged_in_id = false;
		$player_id = false;
		$game_id = false;
		$player = false;
		$game = false;
		
		if ($access_level == 'member' || $access_level == 'player' || $access_level == 'admin') {
			// Make sure the user is logged in
			if (!isset($_SESSION['player_logged_in'])) {
				header("Location: index.php?message=must_login&referer=" . $_SERVER['REQUEST_URI']);
				exit();
			} else {
				$player_logged_in_id = $_SESSION['player_logged_in'];
			}
		}
		
		if ($access_level == 'player' || $access_level == 'admin') {
			// Make sure there is a player_id or game_id in the query string
			if (!empty($_GET['game_id']) || !empty($_GET['player_id'])) {
				if (!empty($_GET['game_id'])) {
					$game_id = trim($_GET['game_id']);
					$game = games_by_id($game_id);
				}
				if (!empty($_GET['player_id'])) {
					$player_id = trim($_GET['player_id']);
					$player = players_by_id($player_id);
				}
			} else {
				header("Location: access_denied.php?message=unauthorized");
				exit();
			}
			
			// Make sure the game and player are valid
			if (!$player && !$game) {
				header("Location: access_denied.php?message=unauthorized");
				exit();
			}
		}
		
		if ($access_level == 'admin') {
			// Make sure the user has admin access
			if ($_SESSION['player_access'] != 'admin') {
				header("Location: access_denied.php?message=admin_only");
				exit();
			}
		}
		
		// Set global variables to be accessed outside the function
		$GLOBALS['player_logged_in_id'] = $player_logged_in_id;
		$GLOBALS['player_id'] = $player_id;
		$GLOBALS['game_id'] = $game_id;
		$GLOBALS['player'] = $player;
		$GLOBALS['game'] = $game;
	}
	
	// Check how many seconds between difference in game start time and hours given
	function get_seconds_between($game_start_time, $hours) {
		$start_time = strtotime($game_start_time);
		$seconds = ($start_time - time()) - ($hours * 3600);
		
		if ($seconds <= 0) {
			return 0;
		} else {
			return $seconds;
		}
	}