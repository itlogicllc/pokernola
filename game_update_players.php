<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_game_players.php');
	require('includes/set_game_players.php');
	
	$page_access_type = 'admin';
	set_page_access($page_access_type);
	
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
		
		if (!$player_id) {
			$player_id = $_POST['players_select'];
		}
	} else {
		$action = "delete";
	}
	
	$game_players_id = game_players_player_by_game($game_id, $player_id);
	$game_players_id = $game_players_id['game_players_id'];
	
	$alternate_count = game_players_alternates_by_game($game_id);
	
	switch ($action) {
		// If the action is player_add, add a player.
		case "player_add":
			set_game_players_add($game_id, $player_id);
			break;
		
		// If the add input is alternate_add, add an alternate.
		case "alternate_add":
			if ($alternate_count) {
				$alternate_count = $alternate_count[count($alternate_count) - 1]['alternate_order'] + 1;
			} else {
				$alternate_count = 1;
			}

			set_game_players_add($game_id, $player_id, $alternate_count);
			break;
		
		// If the action is player_move, move the player to the alternates list.
		case "player_move":
			if ($alternate_count) {
				$alternate_count = $alternate_count[count($alternate_count) - 1]['alternate_order'] + 1;
			} else {
				$alternate_count = 1;
			}
			
			set_game_players_move($game_id, $player_id, $alternate_count, 0);
			break;
		
		// If the action is alternate_move, move the alternate to the players list.
		case "alternate_move":
			set_game_players_move($game_id, $player_id, 0, 1);
			break;
		
		// If the action is none of the above, delete the player.
		default:
			if (isset($_GET['alternate'])) {
				$is_alternate = $_GET['alternate'];
			} else {
				$is_alternate = 0;
			}

			set_game_players_delete($game_id, $player_id, $is_alternate);
	}

	// Redirect back to game_updates.
	header("Location: game_update.php?game_id=$game_id");
	exit();
	