<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access(1);
	require('includes/set_game_players.php');
	
	// Make sure the query string has a player_id, if not redirect to access denied.
	if (!empty($_GET['game_id'])) {
		$game_id = trim($_GET['game_id']);
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}
	
	// If the add input is posted add the player.
	if (isset($_POST['add'])) {
		$player_id = $_POST['players_select'];
		
		set_game_players_add($game_id, $player_id);
	// If there is no add posted, delete the player.
	} else {
		$player_id = $_GET['player_id'];
		$game_players_id = game_players_get_id($game_id, $player_id);
		
		set_game_players_delete($game_id, $game_players_id);
	}

	// Redirect back to game_updates.
	header("Location: game_update.php?game_id=$game_id");
	exit();
	