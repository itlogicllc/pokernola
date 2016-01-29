<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access(1);
	require('includes/get_game_players.php');
	require('includes/set_game_players.php');
	
	// Make sure the query string has a player_id, if not redirect to access denied.
	if (!empty($_GET['game_id'])) {
		$game_id = trim($_GET['game_id']);
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}
	
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
		if (isset($_GET['player_id'])) {
			$player_id = $_GET['player_id'];
		} else {
			$player_id = $_POST['players_select'];
		}
	} else {
		$action = "delete";
		$player_id = $_GET['player_id'];
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
			
			set_game_players_move($game_players_id, $game_id, $alternate_count, 0);
			break;
		
		// If the action is alternate_move, move the alternate to the players list.
		case "alternate_move":
			set_game_players_move($game_players_id, $game_id, 0, 1);
			break;
		
		// If the action is none of the above, delete the player.
		default:
			if (isset($_GET['alternate'])) {
				$is_alternate = $_GET['alternate'];
			} else {
				$is_alternate = 0;
			}

			set_game_players_delete($game_id, $game_players_id, $is_alternate);
	}
	// If the add input is player_add, add a player.
//	if (isset($_POST['add']) && $_POST['add'] == "player_add") {
//		$player_id = $_POST['players_select'];
//		
//		set_game_players_add($game_id, $player_id);
//	
//	// If the add input is alternate_add, add an alternate.
//	} elseif (isset($_POST['add']) && $_POST['add'] == "alternate_add") {
//		$player_id = $_POST['players_select'];
//		
//		// See if there are any alternates in the game
//		$alternate_count = game_players_alternates_by_game($game_id);
//		
//		if ($alternate_count) {
//			$alternate_count = $alternate_count[count($alternate_count) - 1]['alternate_order'] + 1;
//		} else {
//			$alternate_count = 1;
//		}
//		
//		set_game_players_add($game_id, $player_id, $alternate_count);
//		
//
//	// If there is no add posted, delete the player.	
//	} else {
//		$player_id = $_GET['player_id'];
//		$game_players_id = game_players_player_by_game($game_id, $player_id);
//		$game_players_id = $game_players_id['game_players_id'];
//		
//		if (isset($_GET['alternate'])) {
//			$is_alternate = 1;
//		} else {
//			$is_alternate = 0;
//		}
//		
//		set_game_players_delete($game_id, $game_players_id, $is_alternate);
//	}

	// Redirect back to game_updates.
	header("Location: game_update.php?game_id=$game_id");
	exit();
	