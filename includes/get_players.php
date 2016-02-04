<?php

	// Select all the players ordered by their full name
	$players_query = "SELECT *, CONCAT(first_name,' ',last_name) full_name
				 FROM players
				 WHERE player_id > 0
				 ORDER BY full_name";

	$records = mysqli_query($db_connect, $players_query);

	while ($record = mysqli_fetch_array($records)) {
		$players_array[] = $record;
	}

	mysqli_free_result($records);
	
	// Refresh the recordset by rerunning the query and resetting the players_array
	// to the new recordset. Use after database transactions.
	function players_refresh() {
		global $db_connect;
		global $players_query;

		$records = mysqli_query($db_connect, $players_query);

		while ($record = mysqli_fetch_array($records)) {
			$players_array[] = $record;
		}

		$GLOBALS['players_array'] = $players_array;
		
		mysqli_free_result($records);
	}

	// formerly Players_player
	// Return an array of the player associated with the given player ID.
	function players_by_id($player_id) {
		global $players_array;

		for ($i = 0; $i <= count($players_array) - 1; $i++) {
			if ($players_array[$i]['player_id'] == $player_id) {
				return $players_array[$i];
			}
		}
		
		return false;
	}

	// Return an array of the player associated with the given email.
	function players_by_email($email) {
		global $players_array;

		for ($i = 0; $i <= count($players_array) - 1; $i++) {
			if (strtolower($players_array[$i]['email']) == strtolower($email)) {
				return $players_array[$i];
			}
		}
		
		return false;
	}

	// Return an array of the player associated with the given email and password.
	function players_login($email, $password) {
		global $players_array;

		for ($i = 0; $i <= count($players_array) - 1; $i++) {
			if (strtolower($players_array[$i]['email']) == strtolower($email) && $players_array[$i]['password'] == $password) {
				return $players_array[$i];
			}
		}
		
		return false;
	}

	// Check if the given email already exists in the database.
	// Return 1 if true, otherwise 0 if false.
	function players_email_duplicate($email) {
		global $players_array;

		for ($i = 0; $i <= count($players_array) - 1; $i++) {
			if (strtolower($players_array[$i]['email']) == strtolower($email)) {
				return true;
			}
		}

		return false;
	}

	// Return an array of players with admin access
	function players_admins() {
		global $players_array;

		for ($i = 0; $i <= count($players_array) - 1; $i++) {
			if ($players_array[$i]['access_level'] == "admin") {
				$admin_array[] = $players_array[$i];
			}
		}
		
		if (!empty($admin_array)) {
			return $admin_array;
		} else {
			return false;
		}
	}

	// formerly players_list,
	// Returnes an array of all players.
	function players_all() {
		global $players_array;

		return $players_array;
	}
	