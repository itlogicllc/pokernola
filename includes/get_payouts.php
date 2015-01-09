<?php 
mysql_select_db($database_poker_db, $poker_db);
$query_payouts = "SELECT *, CONCAT(players.first_name,' ',players.last_name) full_name, sum(winners.split_diff * games.total_pot) as total_amount
                  FROM winners, games, players
                  WHERE winners.game_id = games.game_id AND winners.player_id = players.player_id AND games.game_date BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] . "' AND players.player_id != 0
                  GROUP BY full_name
                  ORDER BY total_amount DESC";
$payouts = mysql_query($query_payouts, $poker_db) or die(mysql_error());
$totalRows_payouts = mysql_num_rows($payouts);
?>

<?php
$payout_array = array();

while($row_payout = mysql_fetch_assoc($payouts)) {
  $payout_array[] = $row_payout;
}
?>
<?php
function payout_range($from_rank = 1, $to_rank = 0) {	
	global $payout_array;
	$payout_range_array = array();
	$pointer = 0;
	
	if ($to_rank == 0) $to_rank = count($payout_array);
	
	for ($i=0; $i <= $to_rank - 1; $i++) {
		if ($from_rank - 1 <= $i && $to_rank - 1 >= $i) {
			$payout_range_array[$pointer] = $payout_array[$i];
			$pointer++;
		}
	}
	return $payout_range_array;
}
?>
<?php 
mysql_free_result($payouts);
?>