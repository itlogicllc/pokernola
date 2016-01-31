<?php
	require_once('includes/get_games.php');
	require_once('includes/get_players.php');
	require_once('includes/get_game_players.php');
	require_once('includes/set_points.php');
	require_once('includes/set_emails.php');
	
	// Add the given player to the game players table for the given game
	// and update the number of players in the games table.
	function set_game_players_add($game_id, $player_id, $alternate_order = 0){
		global $db_connect;
		
		$game_players_array = game_players_by_game($game_id);
		
		if (!empty($game_players_array)) {
			$num_players = count($game_players_array);
		} else {
			$num_players = 0;
		}
		
		if ($alternate_order == 0) {
			$add_player = $num_players + 1;
		} else {
			$add_player = $num_players;
		}
		
		$query1 = "INSERT INTO game_players
						  (game_id, player_id, alternate_order)
						  VALUES
						  ('$game_id', '$player_id', '$alternate_order')";

		$query2 = "UPDATE games
					  SET num_players='$add_player' 
					  WHERE game_id='$game_id'";
				
		// Execute the queries
		$db_action1 = mysqli_query($db_connect, $query1);
		$db_action2 = mysqli_query($db_connect, $query2);
		
		// Update points depending on how many registered players.
		set_points($game_id);
		
		return true;
	}
	
	// Delete the given game player from the game players table for the given game player id
	// and update the number of players in the games table.
	function set_game_players_delete($game_id, $game_players_id, $is_alternate = 0) {
		global $db_connect;
		
		$game_players_array = game_players_by_game($game_id);
		
		if (!empty($game_players_array)) {
			$num_players = count($game_players_array);
		} else {
			$num_players = 0;
		}
		
		if ($is_alternate == 0) {
			$subtract_player = $num_players - 1;
		} else {
			$subtract_player = $num_players;
		}
				
		$query1 = "DELETE FROM game_players
					  WHERE game_players_id='$game_players_id'";

		$query2 = "UPDATE games
					  SET num_players='$subtract_player' 
					  WHERE game_id='$game_id'";

		// Execute the queries
		$db_action1 = mysqli_query($db_connect, $query1);
		$db_action2 = mysqli_query($db_connect, $query2);
		
		// Update points depending on how many registered players.
		set_points($game_id);
		
		return true;
	}
	
	// Change the alternate_order of the first alternate to 0 to move them into the registered players
	function set_game_players_alternate_to_player($game_id, $game_players_id, $alternate_id) {
		global $db_connect;
		
		$game_players_array = game_players_by_game($game_id);
		
		if (!empty($game_players_array)) {
			$num_players = count($game_players_array);
		} else {
			$num_players = 0;
		}
		
		$query1 = "UPDATE game_players
					  SET alternate_order='0' 
					  WHERE game_players_id='$game_players_id'";
		
		$query2 = "UPDATE games
					  SET num_players='$num_players' 
					  WHERE game_id='$game_id'";

		// Execute the queries
		$db_action1 = mysqli_query($db_connect, $query1);
		$db_action2 = mysqli_query($db_connect, $query2);
		
		// Setup the email information
		$alternate_player = players_by_id($alternate_id);
		$game = games_by_id($game_id);
		
		// Send email to new registered player
		player_emails("alternate_change", array($alternate_player['email']), array($alternate_player['first_name'], date_to_php($game['game_name'])));
		
		// Send system email
		system_emails("alternate_change", array($alternate_player['full_name'], date_to_php($game['game_name'])));
		
		return true;
	}
	
	// Move a player from alternates to registered players or from registered players to alternates
	function set_game_players_move($game_players_id, $game_id, $alternate_order, $player_move) {
		global $db_connect;
		
		$game_players_array = game_players_by_game($game_id);
		
		if (!empty($game_players_array)) {
			$num_players = count($game_players_array);
		} else {
			$num_players = 0;
		}
		
		if ($player_move == 0) {
			$player_total = $num_players - 1;
		} else {
			$player_total = $num_players + 1;
		}
		
		$query1 = "UPDATE game_players
					  SET alternate_order='$alternate_order' 
					  WHERE game_players_id='$game_players_id'";

		$query2 = "UPDATE games
					  SET num_players='$player_total' 
					  WHERE game_id='$game_id'";

		// Execute the queries
		$db_action1 = mysqli_query($db_connect, $query1);
		$db_action2 = mysqli_query($db_connect, $query2);
		
		// Update points depending on how many registered players.
		set_points($game_id);
		
		return true;
	}
	

