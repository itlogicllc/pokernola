<?php
	require_once('includes/get_games.php');
	require_once('includes/get_game_players.php');
	require_once('includes/set_points.php');
	
	// Add the given player to the game players table for the given game
	// and update the number of players in the games table.
	function set_game_players_add($game_id, $player_id){
		global $db_connect;
		
		$game_players_array = game_players_by_game($game_id);
		
		if (!empty($game_players_array)) {
			$num_players = count($game_players_array);
		} else {
			$num_players = 0;
		}
		
		$add_player = $num_players + 1;
		
		$query1 = "INSERT INTO game_players
						  (game_id, player_id)
						  VALUES
						  ('$game_id', '$player_id')";

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
	function set_game_players_delete($game_id, $game_players_id) {
		global $db_connect;
		
		$game_players_array = game_players_by_game($game_id);
		$num_players = count($game_players_array);
		
		if (!empty($game_players_array)) {
			$num_players = count($game_players_array);
		} else {
			$num_players = 0;
		}
		
		$subtract_player = $num_players - 1;
				
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
	

