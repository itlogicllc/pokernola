<?php 
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_players.php');
	require('includes/get_winners.php');
	require('includes/get_games.php');
	require('includes/get_rankings.php');
	require('includes/get_game_players.php');
	require('includes/get_invitation.php');
	require('includes/get_payouts.php');
?>

<!DOCTYPE html>
<html>
<head>
<?php require('includes/set_head.php'); ?>
</head>
<body>

<div data-role="page" id="test">
	<?php
$game_players_array = game_players_by_game(298);
	if ($game_players_array) {
		$num_players = count($game_players_array);
	} else {
		$num_players = 0;
	}
	
	$credits_per_degree = $settings_array['credits_per_degree'];
	// add the level and degree of each player in the game to the array
	for ($i = 0; $i <= $num_players - 1; $i++) {
		$players_level = players_level($game_players_array[$i]['player_id'], $credits_per_degree);
		
		$game_players_array[$i]['level'] = $players_level['level'];
		$game_players_array[$i]['degree'] = $players_level['degree'];
	}

var_dump($game_players_array);

foreach ($game_players_array as $key => $row) {
    $level[$key]  = $row['level'];
    $degree[$key] = $row['degree'];
	 $alternate_order[$key] = $row['alternate_order'];
}

var_dump($level);
var_dump($degree);
var_dump($alternate_order);

array_multisort($level, SORT_DESC, $degree, SORT_DESC, $game_players_array);

var_dump($game_players_array);
	?>
</div>
</body>
</html>
