<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access(1);
	require('includes/get_games.php');
	require('includes/get_game_players.php');
	require('includes/get_winners.php');
	require('includes/set_points.php');

	// Make sure the query string has a game_id, if not redirect to access denied.
	if (!empty($_GET['game_id'])) {
		$game_id = trim($_GET['game_id']);
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}

	// If the game_id is not found redirect to unauthorized.
	$game = games_by_id($game_id);
	if (empty($game)) {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}

	if (isset($_POST['registration'])) {
		$registration = 1;
	} else {
		$registration = 0;
	}

	$game_name = date_to_mysql($_POST['game_name']);
	$game_name_more = $_POST['game_name_more'];
	$game_time = time_to_mysql($_POST['game_time']);
	$total_players = $_POST['total_players'];
	$total_pot = $_POST['total_pot'];

	// Update the game table
	$query = "UPDATE games
				 SET registration='$registration', game_name='$game_name', game_name_more='$game_name_more', game_time='$game_time', num_players='$total_players', total_pot='$total_pot'
				 WHERE game_id='$game_id'";

	$db_action = mysqli_query($db_connect, $query);

	// If the posted number of players does not equal the number of players in the database before the update,
	// recalculate the points based on the number of players.
	if ($total_players != $game['num_players']) {
		// Get count of players registered for game.
		$game_players_array = game_players_by_game($game_id);

		if ($game_players_array) {
			$num_players = count(game_players_by_game($game_id));
		} else {
			$num_players = 0;
		}
		
		// If the number of players is greater than the registered players count
		// fill in the registered players difference with guests.
		if ($total_players > $num_players) {
			$player_diff = $total_players - $num_players;

			for ($i = 0; $i <= $player_diff - 1; $i++) {
				$query = "INSERT INTO game_players
							  (game_id, player_id)
							  VALUES ('$game_id', 0)";

				$db_action = mysqli_query($db_connect, $query);
			}
		}
		
		// Update the points based on the number of players.
		set_points($game_id);
	}
	
	header("Location: game_update.php?game_id=$game_id");
	exit();
