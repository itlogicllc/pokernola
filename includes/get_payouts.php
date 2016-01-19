<?php

	// Select all winners records and joining players and games records
	// that are between the dates selected in the session variables.
	// The records are grouped by each player's full name then sorted by
	// total amount of their payouts in descending order.
	$query = "SELECT w.player_id, w.game_id, CONCAT(p.first_name,' ',p.last_name) full_name, sum(w.split_diff * g.total_pot) as total_amount
				 FROM winners AS w
					INNER JOIN players AS p USING (player_id)
					INNER JOIN games AS g USING (game_id)
				 WHERE g.game_name BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] . "' AND p.player_id != 0
				 GROUP BY full_name
				 ORDER BY total_amount DESC";

	$records = mysqli_query($db_connect, $query);

	while ($record = mysqli_fetch_array($records)) {
		$payout_array[] = $record;
	}

	mysqli_free_result($records);

	// Returns an array of players. The array is a range of how
	// many players need to be returned. If no arguments are given
	// for the to and from amounts, all players are rturned.
	function payout_range($from_rank = 1, $to_rank = 0) {
		global $payout_array;

		if ($to_rank == 0 || $to_rank > count($payout_array)) {
			$to_rank = count($payout_array);
		}

		for ($i = 0; $i <= $to_rank - 1; $i++) {
			if ($from_rank - 1 <= $i && $to_rank - 1 >= $i) {
				$payout_range_array[] = $payout_array[$i];
			}
		}
		
		if (empty($payout_array)) {
			return false;
		} else {
			return $payout_range_array;
		}
	}
	