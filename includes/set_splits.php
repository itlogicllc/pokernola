<?php
	require_once('includes/get_winners.php');
	require_once('includes/get_games.php');
	
	// Make sure the query string has a game_id, if not redirect to access denied.
	if (!empty($_GET['game_id'])) {
		$game_id = trim($_GET['game_id']);
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}

	// This function calculates and updates the split_diff of the winners by percentage of chips held when splitting.
	// 
	// L = % of lowest splitting player's payout
	// X = sum of percentages being split
	// P = Total amount of pot
	// N = Number of players splitting
	// S[i] = % of player's chip count
	//
	// L(P) + ( (X-N(L)) * (S[i](P)) )
	//
	function set_splits($game_id) {
		global $db_connect;
		// Get new winners recordsets after the tables have been updated.
		winners_refresh();
	
		$game_array = games_by_id($game_id);
		$settings_id = $game_array['settings_id'];
		$settings_array = settings_by_id($settings_id);
		$winners_array = winners_by_game($game_id);
		
		// Get an array of players splitting in the game
		$splitting_players = winners_split_players_by_game($game_id);
		
		// Get value for L as defined in the comments
		$L = end($splitting_players);
		$L = $L['amount'];
		
		// Get value of X as defined above
		$X = 0;
		for ($i = 0; $i <= count($splitting_players) - 1; $i++) {
			$X = $X + $splitting_players[$i]['amount'];
		}
		
		// Get value for P as defined in the comments
		$P = $game_array['total_pot'];
		
		//Get value for N as defined in the comments
		$N = count($splitting_players);
		
		for ($i = 0; $i <= count($splitting_players) - 1; $i++) {
			// This S is the S[i] value defined in the comments
			$S = $splitting_players[$i]['original_split'];
			$split_diff = $L * $P + (($X - ($N * $L)) * ($S * $P));
			
			// split_diff is the dollar amount. It needs to be converted the the percentage of the total pot
			$split_diff = $split_diff / $game_array['total_pot'];
			
			$query = "UPDATE winners
						 SET split_diff='$split_diff'
						 WHERE winner_id='" . $winners_array[$i]['winner_id'] . "'";

			$db_action = mysqli_query($db_connect, $query);
		}
	}
