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
	
	// Get the days between the season starting date and ending date
	$total_days = date_diff(date_create($settings_array['start_date']), date_create($settings_array['end_date']));
	$total_days = $total_days->days;
	var_dump($total_days);
	
	// Get how many days today is from the season starting date and see if todays day is inverted, meaning a negative vale from the starting date.
	// Also get the value of the ended_early date. If the value is anything other that 000-00-00 then the season was ended before the end_date.
	$todays_day_obj = date_diff(date_create($settings_array['start_date']), date_create(date("Y-m-d")));
	$todays_day = $todays_day_obj->days - 1;
	$todays_day_inverted = $todays_day_obj->invert;
	$ended_early_date = $settings_array['ended_early'];
	var_dump($todays_day_obj);
	
	// This is the amount of days left in the season from today
	$max_days = $total_days - $todays_day;
	var_dump($max_days);
	
	?>
</div>
</body>
</html>
