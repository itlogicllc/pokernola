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
	$game_id = $_GET['game_id'];
	
	$game_alternates_array = game_players_alternates_by_game($game_id);
	var_dump($game_alternates_array);
	
	?>
</div>
</body>
</html>
