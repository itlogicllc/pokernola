<?php
	require_once('includes/get_game_players.php');
	
	// This function updates each playing players credits who played in the given game.
	// If the status is 1, a credit is subtracted from the players credits. If 0, a credit is added.
	function set_credits($game_id, $status) {
		global $db_connect;
		
		$players = game_players_by_game($game_id);
		
		for ($i=0; $i <= count($players) - 1; $i++) {
			if ($status == 1) {
				$credits = $players[$i]['credits'] - 1;
			} else {
				$credits = $players[$i]['credits'] + 1;
			}
			
			$player_id = $players[$i]['player_id'];
			
			// Update the players table
			$query = "UPDATE players
						 SET credits='$credits'
						 WHERE player_id='$player_id'";

			$db_action = mysqli_query($db_connect, $query);
		}
	}

