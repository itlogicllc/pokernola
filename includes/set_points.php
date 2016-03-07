<?php
	require_once('includes/get_winners.php');
	require_once('includes/get_games.php');
	require_once('includes/get_game_players.php');
	
	// Make sure the query string has a game_id, if not redirect to access denied.
	if (!empty($_GET['game_id'])) {
		$game_id = trim($_GET['game_id']);
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}
	
	// This function checks the database for the amount of players and updates
	// the point amounts accordingly.
	function set_points($game_id) {
		global $db_connect;
		// Get new games and winners recordsets after the tables have been updated.
		games_refresh();
		winners_refresh();
		game_players_refresh();

		$game_array = games_by_id($game_id);
		$settings_id = $game_array['settings_id'];
		$settings_array = settings_by_id($settings_id);
		$winners_array = winners_by_game($game_id);
		
		$game_players_array = game_players_by_game($game_id);
		if ($game_players_array) {
			$game_players_count = count($game_players_array) - 1;
		} else {
			$game_players_count = 0;
		}

		// Get the threshhold amount by getting the integer value of how many players are in the game
		// divided by the threshold number. Then get the multiplier amount by raising the  multiplier
		// to the power of the threshold amount.
		$threshold_amount = intval($game_players_count  / $settings_array['threshold']);
		
		// Limit the max amount of the points to be increased by the max_increase setting
		if ($settings_array['max_increase'] > 0 && $threshold_amount > $settings_array['max_increase']) {
			$threshold_amount = $settings_array['max_increase'];
		}
		
		$multiply_amount = pow($settings_array['multiplier'], $threshold_amount);

		// If the multiplier equals 0, force it to be 1.
		if ($multiply_amount == 0) {
			$multiply_amount = 1;
		}

		// Create an array of points for each place.
		$points = array(
			$settings_array['pt1'] * $multiply_amount,
			$settings_array['pt2'] * $multiply_amount,
			$settings_array['pt3'] * $multiply_amount,
			$settings_array['pt4'] * $multiply_amount,
			$settings_array['pt5'] * $multiply_amount,
			$settings_array['pt6'] * $multiply_amount,
			$settings_array['pt7'] * $multiply_amount,
			$settings_array['pt8'] * $multiply_amount,
			$settings_array['pt9'] * $multiply_amount,
			$settings_array['pt10'] * $multiply_amount);
		
		// Reset all points to those in the points array.
		for ($i = 0; $i <= count($winners_array) - 1; $i++) {
			$place_points = $points[$i];

			$query = "UPDATE winners
						 SET points='$place_points'
						 WHERE winner_id='" . $winners_array[$i]['winner_id'] . "'";

			$db_action = mysqli_query($db_connect, $query);
		}
		
		// Refresh the winners table to update the new point amounts
		winners_refresh();
		
		// If split_points is on in the settings
		if ($settings_array['split_points'] == 1) {
			// After all the points are reset, get the
			// sum of points of splitting players and update
			// only the splitting players per their splitting percentage.
			$split_sum = winners_split_points_sum($game_id);
			$split_count = winners_split_count($game_id);

			// Loop through each winner and update their record accordigly.
			for ($i = 0; $i <= count($winners_array) - 1; $i++) {
				// Check if the player is one a splitting players.
				// If they are get their split percentage from the database
				// and multiply it by the sum of splitting points.
				if ($winners_array[$i]['split'] == 1) {
					if ($split_count == 2) {
						$split_percent = $winners_array[$i]['original_split'];
					} else {
						$split_percent = $winners_array[$i]['split_diff'];
					}
					$place_points = $split_sum * $split_percent;

					$query = "UPDATE winners
								 SET points='$place_points'
								 WHERE winner_id='" . $winners_array[$i]['winner_id'] . "'";

					$db_action = mysqli_query($db_connect, $query);
				}
			}
		}
	}

