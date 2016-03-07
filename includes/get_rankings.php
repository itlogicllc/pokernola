<?php

	// Select winners joining players and games between the session from date and session to date.
	// Sum the points scored grouped by each players full name ordered by the point sum and then the full name
	$query = "SELECT w.player_id, w.game_id, g.game_name, SUM(w.points) AS point_sum, CONCAT(p.first_name,' ',p.last_name) AS full_name
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
	
	// Once the rankings array has been created add the rank number to array. The rank number will be calculated to account for ties.
	$score_rank = 0;
	$score_tie_number = 0;
	$current_score = 0;
	$previous_score = 0;
	
	for ($i = 0; $i <= count($rankings_array) - 1; $i++) {
		$current_score = $rankings_array[$i]['point_sum'];
		if ($current_score != $previous_score) {
			$score_rank = $score_rank + 1;
			$score_rank = $score_rank + $score_tie_number;
			$score_tie_number = 0;
			$rankings_array[$i]['rank'] = $score_rank;
		} else {
			$score_tie_number = $score_tie_number + 1;
			$rankings_array[$i]['rank'] = $score_rank;
		}
		$previous_score = $current_score;
	}

	// Return the ranking of a player by the given player_id.
	function rankings_player($player_id) {
		global $rankings_array;

		for ($i = 0; $i <= count($rankings_array) - 1; $i++) {
			if ($rankings_array[$i]['player_id'] == $player_id) {
				return $rankings_array[$i]['rank'];
			}
		}

		return 0;
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
	
	// Returns an array of top ranked players from 1 to the given rank number.
	function rankings_top($to_rank) {
		global $rankings_array;
		$rankings_top_array = array();

		for ($i = 0; $i <= count($rankings_array) - 1; $i++) {
			if ($rankings_array[$i]['rank'] <= $to_rank) {
				$rankings_top_array[] = $rankings_array[$i];
			}
		}

		if (empty($rankings_array)) {
			return false;
		} else {
			return $rankings_top_array;
		}
	}

	// Returns a player's rank from the session from date to a given to date associated with that
	// player's player_id. This is used to get the given players ranking history to be used when
	// charting their ranking history. The query is run again because a new recordset is needed
	// for each to date.	
	function rankings_history($to_date, $player_id) {
		global $db_connect;
		
		$query = "SELECT w.player_id, w.game_id, g.game_name, SUM(w.points) AS point_sum, CONCAT(p.first_name,' ',p.last_name) AS full_name
				 FROM winners AS w
					INNER JOIN players AS p USING (player_id)
					INNER JOIN games AS g USING (game_id)
				 WHERE p.player_id > 0 AND g.game_name BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $to_date . "'
				 GROUP BY full_name
				 ORDER BY point_sum DESC, full_name";

		$records = mysqli_query($db_connect, $query);

		while ($record = mysqli_fetch_array($records)) {
			$rankings_array[] = $record;
		}

		mysqli_free_result($records);

		// Once the rankings array has been created add the rank number to array. The rank number will be calculated to account for ties.
		$score_rank = 0;
		$score_tie_number = 0;
		$current_score = 0;
		$previous_score = 0;

		for ($i = 0; $i <= count($rankings_array) - 1; $i++) {
			$current_score = $rankings_array[$i]['point_sum'];
			if ($current_score != $previous_score) {
				$score_rank = $score_rank + 1;
				$score_rank = $score_rank + $score_tie_number;
				$score_tie_number = 0;
				$rankings_array[$i]['rank'] = $score_rank;
			} else {
				$score_tie_number = $score_tie_number + 1;
				$rankings_array[$i]['rank'] = $score_rank;
			}
			$previous_score = $current_score;
		}
		
		for ($i = 0; $i <= count($rankings_array) - 1; $i++) {
			if ($rankings_array[$i]['player_id'] == $player_id) {
				return $rankings_array[$i]['rank'];
			}
		}

		return "null";
	}
	