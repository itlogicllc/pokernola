<?php 
mysql_select_db($database_poker_db, $poker_db);
$query_rankings = "SELECT *, SUM(winners.points) AS point_sum, COUNT(winners.game_id) AS game_count, CONCAT(players.first_name,' ',players.last_name) AS full_name
				  FROM players, winners
				  WHERE players.player_id = winners.player_id AND players.player_id > 0 AND winners.game_id IN
					  (SELECT game_id
					   FROM games
					   WHERE game_date BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] ."')
				  GROUP BY players.player_id
				  ORDER BY point_sum DESC, full_name";
$rankings = mysql_query($query_rankings, $poker_db) or die(mysql_error());
?>
<?php
$rankings_array = array();

while($row_rankings = mysql_fetch_assoc($rankings)) {
  $rankings_array[] = $row_rankings;
}
?>
<?php
function rankings_player($player) {	
	global $rankings_array;
	
	for ($i=0; $i <= count($rankings_array) - 1; $i++) {
		if ($rankings_array[$i]['player_id'] == $player)
			return ++$i; 
	}
}

function rankings_range($from_rank = 1, $to_rank = 0) {	
	global $rankings_array;
	$rankings_range_array = array();
	$pointer = 0;
	
	if ($to_rank == 0) $to_rank = count($rankings_array);
	
	for ($i=0; $i <= $to_rank - 1; $i++) {
		if ($from_rank - 1 <= $i && $to_rank - 1 >= $i) {
			$rankings_range_array[$pointer] = $rankings_array[$i];
			$pointer++;
		}
	}
	return $rankings_range_array;
}

function rankings_history($to_date, $player_id) {
	global $database_poker_db;
	global $poker_db;
	
	$rankings_history_array = array();
	
	mysql_select_db($database_poker_db, $poker_db);
	$query_rankings_history = "SELECT *, SUM(winners.points) AS point_sum, COUNT(winners.game_id) AS game_count, CONCAT(players.first_name,' ',players.last_name) AS full_name
				  FROM players, winners
				  WHERE players.player_id = winners.player_id AND players.player_id > 0 AND winners.game_id IN
					  (SELECT game_id
					   FROM games
					   WHERE game_date BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $to_date . "')
				  GROUP BY players.player_id
				  ORDER BY point_sum DESC, full_name";
	$rankings_history = mysql_query($query_rankings_history, $poker_db) or die(mysql_error());

	while($row_rankings_history = mysql_fetch_assoc($rankings_history)) {
  		if ($row_rankings_history['player_id'] != $player_id) {
			$rankings_history_array[] = $row_rankings_history['point_sum'];
			$found_player = 0;
		}
		else {
			$rankings_history_array[] = $row_rankings_history['point_sum'];
			$found_player = 1;
			break;
		}
	}

	mysql_free_result($rankings_history);
	
	if ($found_player == 1) { 
		$rankings_history_array = array_unique($rankings_history_array);
		return count($rankings_history_array);
	}
	else {
		return "null";
	}
}
?>
<?php 
mysql_free_result($rankings);
?>