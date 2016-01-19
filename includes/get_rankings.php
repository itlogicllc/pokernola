<?php

	// Select winners joining players and games between the session from date and session to date.
	// Sum the points scored grouped by each players full name ordered by the point sum and then the full name
	$query = "SELECT w.player_id, w.game_id, g.game_date, SUM(w.points) AS point_sum, CONCAT(p.first_name,' ',p.last_name) AS full_name
				 FROM winners AS w
					INNER JOIN players AS p USING (player_id)
					INNER JOIN games AS g USING (game_id)
				 WHERE p.player_id > 0 AND g.game_name BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] . "'
				 GROUP BY full_name
				 ORDER BY point_sum DESC, full_name";

	$records = mysqli_query($db_connect, $query);

	while ($record = mysqli_fetch_array($records)) {
		$rankings_array[] = $record;
	}

	mysqli_free_result($records);

	// Return the ranking of a player by the given player_id.
	// The number returned needs to add 1 to it because the count
	// is 0 based.
	function rankings_player($player_id) {
		global $rankings_array;

		for ($i = 0; $i <= count($rankings_array) - 1; $i++) {
			if ($rankings_array[$i]['player_id'] == $player_id) {
				return ++$i;
			}
		}

		return false;
	}

	// Returns an array of ranked players. The array is a range of how
	// many players need to be returned. If no arguments are given
	// for the to and from amounts, all players are rturned.
	function rankings_range($from_rank = 1, $to_rank = 0) {
		global $rankings_array;

		if ($to_rank == 0 || $to_rank > count($rankings_array)) {
			$to_rank = count($rankings_array);
		}

		for ($i = 0; $i <= $to_rank - 1; $i++) {
			if ($from_rank - 1 <= $i && $to_rank - 1 >= $i) {
				$rankings_range_array[] = $rankings_array[$i];
			}
		}

		if (empty($rankings_array)) {
			return false;
		} else {
			return $rankings_range_array;
		}
	}

	// Returns a player's rank from the session from date to a given to date associated with that
	// player's player_id. This is used to get the given players ranking history to be used when
	// charting their ranking history. The query is run again because a new recordset is needed
	// for each to date.
	function rankings_history($to_date, $player_id) {
		global $db_connect;

		// Select winners joining players and games between the session from date and given to date.
		// Sum the points scored grouped by each layers full name ordered by the point sum and then the full name
		$query = "SELECT w.player_id, w.game_id, g.game_date, SUM(w.points) AS point_sum, CONCAT(p.first_name,' ',p.last_name) AS full_name
					 FROM winners AS w
						INNER JOIN players AS p USING (player_id)
						INNER JOIN games AS g USING (game_id)
					WHERE p.player_id > 0 AND g.game_name BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $to_date . "'
					GROUP BY full_name
					ORDER BY point_sum DESC, full_name";

		$records = mysqli_query($db_connect, $query);

		// Loop through the recordset looking for the given player_id.
		// Add the player's point sum to an array and set a flag if the player
		// was found in the current recorset
		while ($record = mysqli_fetch_array($records)) {
			if ($record['player_id'] != $player_id) {
				$rankings_history_array[] = $record['point_sum'];
				$found_player = 0;
			} else {
				$rankings_history_array[] = $record['point_sum'];
				$found_player = 1;
				break;
			}
		}

		mysqli_free_result($records);

		// If the found player flag is 1, remove duplicate values from the array
		// then return the count of the array which represents the ranking of
		// the player for that time period. Otherwise, return null which means
		// the player did not rank in that time period.
		if ($found_player == 1) {
			$rankings_history_array = array_unique($rankings_history_array);
			return count($rankings_history_array);
		} else {
			return "null";
		}
	}
	