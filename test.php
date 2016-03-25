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
<body onload="javascript_countdown.init(320567, 'javascript_countdown_time');">

<div data-role="page" id="test">
	<?php

	?>
	<div id="javascript_countdown_time"></div>


</div>
</body>
</html>
