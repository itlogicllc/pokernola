<?php 
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_winners.php');
	require('includes/get_rankings.php');
	require('includes/get_game_players.php');
	require('includes/get_invitation.php');
	require('includes/get_payouts.php');
	$page_access_type = 'admin';
	set_page_access($page_access_type);
?>

<!DOCTYPE html>
<html>
<head>
<?php require('includes/set_head.php'); ?>
</head>
<body>

<div data-role="page" id="test">
	<?php
$game_name = date_to_php($game['game_name']);
$game_time = time_to_php($game['game_time']);
$game_start_time = strtotime((date_to_mysql($game_name) . ' ' . time_to_mysql($game_time)));
echo "Difference between two dates is " . $hour = abs(time() - $game_start_time)/(60*60) . " hour(s)";
?>



</div>
</body>
</html>
