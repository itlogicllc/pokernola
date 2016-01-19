<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access(1);
	require('includes/get_winners.php');
	require('includes/get_games.php');
	require('includes/set_points.php');
	require('includes/set_splits.php');
	
	// Make sure the query string has a game_id, if not redirect to access denied.
	if (!empty($_GET['game_id'])) {
		$game_id = trim($_GET['game_id']);
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}

	// If the game_id is not found redirect to unauthorized.
	$winners_array = winners_by_game($game_id);
	if (empty($winners_array)) {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}
	
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
	
	// set_splits is only called when A) The number of splitting players =< the amount of payouts, and B) the lowest
	// chipcount percentage is < the percentage they would have gotten if the game were played out.
	// TODO: the split count is hard coaded to 3 because there can only be at most 3 players in the money. This will need to change if that setting is made dynamic.
	if (($split_count == 2 && $min_split < $settings_array['second_pay']) || ($split_count == 3 && $min_split < $settings_array['third_pay'])) {
		set_splits($game_id);
	}
	
	set_points($game_id);
	
	// Redirect back to game_updates.
	header("Location: game_update.php?game_id=$game_id");
	exit();
	
