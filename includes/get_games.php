<?php

	// Select all games that are between the dates selected in the session variables.
	// The games are ordered in decending order.
	$games_query = "SELECT *, CONCAT(game_name,' ',game_time) game_start_time
						 FROM games 
						 WHERE game_name BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] . "'
						 ORDER BY game_name DESC";

	$records = mysqli_query($db_connect, $games_query);

	while ($record = mysqli_fetch_array($records)) {
		$games_array[] = $record;
	}

	mysqli_free_result($records);
	
	// Refresh the recordset by rerunning the query and resetting the games_array
	// to the new recordset. Use after database transactions.
	function games_refresh() {
		global $db_connect;
		global $games_query;

		$records = mysqli_query($db_connect, $games_query);

		while ($record = mysqli_fetch_array($records)) {
			$games_array[] = $record;
		}

		$GLOBALS['games_array'] = $games_array;
		
		mysqli_free_result($records);
	}

	// Formerly games_game
	// Returns the game record associted with given game ID.
	function games_by_id($game_id) {
		global $games_array;

		for ($i = 0; $i <= count($games_array) - 1; $i++) {
			if ($games_array[$i]['game_id'] == $game_id) {
				return $games_array[$i];
			}
		}
		
		return false;
	}

	// Formerly games_list.
	// Return array of all games both played and future games scheduled.
	function games_all() {
		global $games_array;

		return $games_array;
	}

	// Returns an array of games played.
	// Only games with a completed status (0) are counted, not future
	// scheduled games.
	function games_played_all() {
		global $games_array;
		$games_played = array();
		$i2 = 0;

		for ($i = 0; $i <= count($games_array) - 1; $i++) {
			if ($games_array[$i]['status'] == 0) {
				$games_played[$i2] = $games_array[$i];
				$i2 = $i2 + 1;
			}
		}
		
		return $games_played;
	}

	// Returns an array of all pots from each game played.
	// Only games with a completed status (0) are counted, not future
	// scheduled games.
	function games_pots() {
		global $games_array;
		$pots = array();
		$i2 = 0;

		for ($i = 0; $i <= count($games_array) - 1; $i++) {
			if ($games_array[$i]['status'] == 0) {
				$pots[$i2] = $games_array[$i]['total_pot'];
				$i2 = $i2 + 1;
			}
		}
		
		return $pots;
	}

	// Returns an array of the number of players that played in each game.
	// Only games with a completed status (0) are counted, not future
	// scheduled games.
	function games_num_players() {
		global $games_array;
		$num_players = array();
		$i2 = 0;

		for ($i = 0; $i <= count($games_array) - 1; $i++) {
			if ($games_array[$i]['status'] == 0) {
				$num_players[$i2] = $games_array[$i]['num_players'];
				$i2 = $i2 + 1;
			}
		}
		
		return $num_players;
	}

//	function games_new_name() {
//		global $games_array;
//		$game_date = date('Y-m-d', time());
//		$count = 0;
//
//		for ($i = 0; $i <= count($games_array); $i++) {
//			if ($games_array[$i]['game_date'] == $game_date) {
//				$count++;
//			}
//		}
//
//		if ($count > 0) {
//			$game_name = date('m-d-Y', time()) . " (" . ($count + 1) . ")";
//		} else {
//			$game_name = date('m-d-Y', time());
//		}
//
//		return $game_name;
//	}
	