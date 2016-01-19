<?php

	// Select all winners joing the players and games between the session from date and session to date.
	// They are ordered by the game name in descending order then by each winners place.
	$winners_query = "SELECT *, CONCAT(p.first_name,' ',p.last_name) full_name
				 FROM winners AS w
					INNER JOIN players AS p USING (player_id)
					INNER JOIN games AS g USING (game_id)
				 WHERE g.game_name BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] . "'
				 ORDER BY g.game_name DESC, w.place";

	$records = mysqli_query($db_connect, $winners_query);

	while ($record = mysqli_fetch_array($records)) {
		$winners_array[] = $record;
	}

	mysqli_free_result($records);
	
	// Refresh the recordset by rerunning the query and resetting the winners_array
	// to the new recordset. Use after database transactions.
	function winners_refresh() {
		global $db_connect;
		global $winners_query;

		$records = mysqli_query($db_connect, $winners_query);

		while ($record = mysqli_fetch_array($records)) {
			$winners_array[] = $record;
		}

		$GLOBALS['winners_array'] = $winners_array;
		
		mysqli_free_result($records);
	}

	// This returns a count of how many times the given player has placed between the
	// given from and to places. For example It is used to find how many time a player has been 
	// in the top 10 places.
	function winner_range_count($player_id, $from_place, $to_place) {
		global $winners_array;
		$win_count = 0;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['player_id'] == $player_id && $winners_array[$i]['place'] >= $from_place && $winners_array[$i]['place'] <= $to_place) {
				$win_count++;
			}
		}
		
		return $win_count;
	}

	// Return the payout a given player recieved in a given game
	function winner_game_payout($player_id, $game_id) {
		global $winners_array;
		$payout = 0;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['player_id'] == $player_id && $winners_array[$i]['game_id'] == $game_id) {
				// Take the total pot of the game * the split difference the player had in that game
				$payout = ($winners_array[$i]['total_pot'] * $winners_array[$i]['split_diff']);
				return $payout;
			}
		}
		
		// If the above statement fails, return 0.
		return $payout;
	}

	// Return the points a given player received in a given game
	function winner_game_points($player_id, $game_id) {
		global $winners_array;
		$points = 0;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['player_id'] == $player_id && $winners_array[$i]['game_id'] == $game_id) {
				$points = $winners_array[$i]['points'];
				return $points;
			}
		}
		
		// If the above statement fails, return 0.
		return $points;
	}
	
	// Returns the total payout a given player has won in all games
	function winner_total_payout($player_id) {
		global $winners_array;
		$payout = 0;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['player_id'] == $player_id) {
				// Take the total pot of the game * the split difference the player had in a game and + it to any previous payout
				$payout = ($winners_array[$i]['total_pot'] * $winners_array[$i]['split_diff']) + $payout;
			}
		}
		
		return $payout;
	}

	// Returns the total amount of points a given player has won in all games.
	function winner_total_points($player_id) {
		global $winners_array;
		$points = 0;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['player_id'] == $player_id) {
				$points = $winners_array[$i]['points'] + $points;
			}
		}
		
		return $points;
	}

	// Returns an array of each time a given player has placed in a given place
	function winner_by_place($player_id, $place) {
		global $winners_array;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['player_id'] == $player_id && $winners_array[$i]['place'] == $place) {
				$winner_place_array[] = $winners_array[$i];
			}
		}
		
		if (!empty($winner_place_array)) {
			return $winner_place_array;
		} else {
			return false;
		}
	}

	// Returns an array of all players who have placed in a given game
	function winners_by_game($game_id) {
		global $winners_array;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['game_id'] == $game_id && $winners_array[$i]['place'] != 0) {
				$winners_game_array[] = $winners_array[$i];
			}
		}
		
		if (!empty($winners_game_array)) {
			return $winners_game_array;
		} else {
			return false;
		}
	}

	// Returns an array of all players who split in a given game
	function winners_split_players_by_game($game_id) {
		global $winners_array;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['game_id'] == $game_id && $winners_array[$i]['split'] == 1) {
				$split_players[] = $winners_array[$i];
			}
		}
		
		if (!empty($split_players)) {
			return $split_players;
		} else {
			return false;
		}
	}

	// TODO: This function may no longer be needed
	// Returns a payout amount each player gets when split evenly in a given game.
	// The number of splitting players is gotten by calling the above function.
	function winners_split_even_payout_by_game($game_id) {
		global $winners_array;
		$split_payout = 0;
		$split_amount = count(winners_split_players_by_game($game));

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['game_id'] == $game_id && $winners_array[$i]['split'] == 1) {
				$split_payout = $split_payout + $winners_array[$i]['split_diff'];
			}
		}
		
		return $split_payout / $split_amount;
	}
	
	// Returns the count of splitting players.
	function winners_split_count($game_id) {
		global $winners_array;
		$split_count = 0;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['game_id'] == $game_id && $winners_array[$i]['split'] == 1) {
				$split_count = $split_count + 1;
			}
		}
		
		return $split_count;
	}
	
	// Returns the sum of points of each splitting player.
	function winners_split_points_sum($game_id) {
		global $winners_array;
		$split_sum = 0;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['game_id'] == $game_id && $winners_array[$i]['split'] == 1) {
				$split_sum = $split_sum + $winners_array[$i]['points'];
			}
		}
		
		return $split_sum;
	}

	// TODO: This function may not be needed
	// Returns the points each player gets when split evenly in a given game.
	// The number of splitting players is gotten by calling the above function.
	function winners_split_even_points_by_game($game_id) {
		global $winners_array;
		$split_points = 0;
		$split_amount = count(winners_split_players_by_game($game_id));

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['game_id'] == $game_id && $winners_array[$i]['split'] == 1) {
				$split_points = $split_points + $winners_array[$i]['points'];
			}
		}
		
		return $split_points / $split_amount;
	}

	// TODO: Complete the KO system. This fuction probably isn't correct.
	// Returns an array of all players who did not place in a given game
	function winners_ko_by_game($game_id) {
		global $winners_array;

		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			if ($winners_array[$i]['game_id'] == $game_id && $winners_array[$i]['place'] == 0) {
				$winners_game_array[] = $winners_array[$i];
			}
		}
		
		if (!empty($winners_game_array)) {
			return $winners_game_array;
		} else {
			return false;
		}
	}
	