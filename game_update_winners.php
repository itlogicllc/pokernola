<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_winners.php');
	require('includes/set_points.php');
	require('includes/set_splits.php');
	
	$page_access_type = 'admin';
	set_page_access($page_access_type);

	$winners_array = winners_by_game($game_id);
	$game_array = games_by_id($game_id);
	$settings_id = $game_array['settings_id'];
	$settings_array = settings_current($settings_id);
	
	// Set game_winners to the array of winners_select from the game_update_winners form
	// then reverse the order of the array so that the winners are in place order from 10 - 1
	$game_winners = array_reverse($_POST['winners_select']);
	$split_percentages = array_reverse($_POST['chipcounts']);
	$original_splits = array_reverse($_POST['original_splits']);
	$split_count = 0;
	$min_split = 0;
	
	// Update the winners table with the winners of the game. Update the player_id and mark the
	// splitting players for the given winner_id.
	for ($i = 0; $i <= count($winners_array) - 1; $i++) {
		$player_id = $game_winners[$i];
		$winner_id = $winners_array[$i]['winner_id'];
		$split_diff = $split_percentages[$i];
		$original_split = $original_splits[$i];

		if (isset($_POST['splits'][$i])) {
			$split = 1;
			$split_count = $split_count + 1;
			$min_split = $split_diff;
		} else {
			$split = 0;
		}

		// Update the winners table.
		$query = "UPDATE winners
					 SET player_id='$player_id', split_diff='$split_diff', original_split='$original_split', split='$split' 
					 WHERE winner_id='$winner_id'";

		$db_action = mysqli_query($db_connect, $query);
	}
	
	set_splits($game_id);
	set_points($game_id);
	
	// Redirect back to game_updates.
	header("Location: game_update.php?game_id=$game_id");
	exit();
	
