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
	
	var_dump(time_to_datepicker("19:30:00"));
	
	?>
</div>
</body>
</html>
