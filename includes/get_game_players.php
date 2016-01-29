<?php

	// Select all game_player records and joining players and games records
	// that are between the dates selected in the session variables.
	// The records are orderd by the game dates and then the full name of the players.
	$game_players_query = "SELECT gp.game_players_id, gp.player_id, gp.game_id, gp.alternate_order, g.game_date, g.status, CONCAT(p.first_name,' ',p.last_name) AS full_name
								  FROM game_players AS gp 
										INNER JOIN players AS p USING (player_id)
										INNER JOIN games AS g USING (game_id)
								  WHERE g.game_name BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] . "'
								  ORDER BY g.game_date, gp.alternate_order, full_name";

	$records = mysqli_query($db_connect, $game_players_query);

	while ($record = mysqli_fetch_array($records)) {
		$game_players_array[] = $record;
	}
	
	mysqli_free_result($records);
	
	// Refresh the recordset by rerunning the query and resetting the players_array
	// to the new recordset. Use after database transactions.
	function game_players_refresh() {
		global $db_connect;
		global $game_players_query;

		$records = mysqli_query($db_connect, $game_players_query);

		while ($record = mysqli_fetch_array($records)) {
			$game_players_array[] = $record;
		}

		$GLOBALS['game_players_array'] = $game_players_array;
		
		mysqli_free_result($records);
	}
	
	// Returns an array of players who have played in the given game.
	function game_players_by_game($game_id) {
		global $game_players_array;
		$players_by_game_array = array();
		
//		echo "game players";
//		var_dump(count($game_players_array));

		for ($i = 0; $i <= count($game_players_array) - 1; $i++) {
			if ($game_players_array[$i]['game_id'] == $game_id && $game_players_array[$i]['alternate_order'] == 0) {
				$players_by_game_array[] = $game_players_array[$i];
			}
		}
		
		if (!empty($players_by_game_array)) {
			return $players_by_game_array;
		} else {
			return false;
		}
	}
	
	// Return an array of alternates registered for a given game.
	function game_players_alternates_by_game($game_id) {
		global $game_players_array;
		$alternates_by_game_array = array();
		
//	echo "game alternates";
//	var_dump(count($game_players_array));
//	exit();

		for ($i = 0; $i <= count($game_players_array) - 1; $i++) {
			if ($game_players_array[$i]['game_id'] == $game_id && $game_players_array[$i]['alternate_order'] != 0) {
				$alternates_by_game_array[] = $game_players_array[$i];
			}
		}
		if (!empty($alternates_by_game_array)) {
			return $alternates_by_game_array;
		} else {
			return false;
		}
	}

	// Checks to see if a given player is registered for a given game.
	// Including if they are registered as an alternate.
	// If they are the player is returned, otherwise it returns false.
	function game_players_player_by_game($game_id, $player_id) {
		global $game_players_array;

		for ($i = 0; $i <= count($game_players_array) - 1; $i++) {
			if (($game_players_array[$i]['player_id'] == $player_id) && ($game_players_array[$i]['game_id'] == $game_id)) {
				return $game_players_array[$i];
			}
		}
		
		return false;
	}

	// Returns the amount of times a player has played in games.
	// Only games with a completed status (0) are counted, not future
	// scheduled games.
	function game_players_played($player_id) {
		global $game_players_array;
		$count = 0;

		for ($i = 0; $i <= count($game_players_array) - 1; $i++) {
			if ($game_players_array[$i]['player_id'] == $player_id && $game_players_array[$i]['status'] == 0 && $game_players_array[$i]['alternate_order'] == 0) {
				$count++;
			}
		}
		
		return $count;
	}

	// Returns the total number of all players who have played in all games.
	// Only games with a completed status (0) are counted, not future
	// scheduled games.
	function game_players_count() {
		global $game_players_array;
		$count = 0;

		for ($i = 0; $i <= count($game_players_array) - 1; $i++) {
			if ($game_players_array[$i]['status'] == 0 && $game_players_array[$i]['alternate_order'] == 0) {
				$count++;
			}
		}
		
		return $count;
	}
	