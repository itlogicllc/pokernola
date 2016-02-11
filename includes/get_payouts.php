<?php

	// Select all winners records and joining players and games records
	// that are between the dates selected in the session variables.
	// The records are grouped by each player's full name then sorted by
	// total amount of their payouts in descending order.
	$query = "SELECT w.player_id, w.game_id, CONCAT(p.first_name,' ',p.last_name) full_name, SUM(w.split_diff * g.total_pot) as total_amount
				 FROM winners AS w
					INNER JOIN players AS p USING (player_id)
					INNER JOIN games AS g USING (game_id)
				 WHERE g.game_name BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] . "' AND p.player_id != 0
				 GROUP BY full_name
				 ORDER BY total_amount DESC, full_name";

	$records = mysqli_query($db_connect, $query);

	while ($record = mysqli_fetch_array($records)) {
		$payouts_array[] = $record;
	}

	mysqli_free_result($records);
	
	// Once the payout array has been created add the rank number to array. The rank number will be calculated to account for ties.
	$payout_rank = 0;
	$payout_tie_number = 0;
	$current_payout = 0;
	$previous_payout = 0;
	
	for ($i = 0; $i <= count($payouts_array) - 1; $i++) {
		$current_payout = $payouts_array[$i]['total_amount'];
		if ($current_payout != $previous_payout) {
			$payout_rank = $payout_rank + 1;
			$payout_rank = $payout_rank + $payout_tie_number;
			$payout_tie_number = 0;
			$payouts_array[$i]['rank'] = $payout_rank;
		} else {
			$payout_tie_number = $payout_tie_number + 1;
			$payouts_array[$i]['rank'] = $payout_rank;
		}
		$previous_payout = $current_payout;
	}

	// Returns an array of players. The array is a range of how
	// many players need to be returned. If no arguments are given
	// for the to and from amounts, all players are rturned.
	function payouts_range($from_rank = 1, $to_rank = 0) {
		global $payouts_array;

		if ($to_rank == 0 || $to_rank > count($payouts_array)) {
			$to_rank = count($payouts_array);
		}

		for ($i = 0; $i <= $to_rank - 1; $i++) {
			if ($from_rank - 1 <= $i && $to_rank - 1 >= $i) {
				$payout_range_array[] = $payouts_array[$i];
			}
		}
		
		if (empty($payouts_array)) {
			return false;
		} else {
			return $payout_range_array;
		}
	}
	
	// Returns an array of top payout players from 1 to the given rank number.
	function payouts_top($to_rank) {
		global $payouts_array;
		$payouts_top_array = array();

		for ($i = 0; $i <= count($payouts_array) - 1; $i++) {
			if ($payouts_array[$i]['rank'] <= $to_rank) {
				$payouts_top_array[] = $payouts_array[$i];
			}
		}

		if (empty($payouts_array)) {
			return false;
		} else {
			return $payouts_top_array;
		}
	}
	