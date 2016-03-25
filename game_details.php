<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_winners.php');
	require('includes/get_game_players.php');
	
	$page_access_type = 'player';
	set_page_access($page_access_type);
	if ($game['registration'] == 1 && $game['status'] == 1) {
		$page_access_type = 'admin';
		set_page_access($page_access_type);
	}
	

	$game_name = date_to_php($game['game_name']);
	$game_name_more = $game['game_name_more'];
	
	// Find the current game_id in the array of all games played and point to it.
	// if there is a next element in the array set the next_game_id to the next game id
	// If there is a previous element in the array set the previous_game_id to the previous game _id.
	$game_pagation = games_played_all();
	for ($i = 0; $i <= count($game_pagation) - 1; $i++) {
		if ($game_pagation[$i]['game_id'] == $game_id) {
			break;
		}
	}
	
	if ($i < count($game_pagation) - 1) {
		$next_game_id = $game_pagation[$i + 1]['game_id'];
	} else {
		$next_game_id = false;
	}
	
	if ($i > 0) {
		$previous_game_id = $game_pagation[$i - 1]['game_id'];
	} else {
		$previous_game_id = false;
	}
	
	$total_pot = $game['total_pot'];
	$game_pot = "$" . number_format($total_pot, 2);

	$game_winners_array = winners_by_game($game_id);

	$game_alternates_array = game_players_alternates_by_game($game_id);
	if ($game_alternates_array) {
		$game_alternates_count = count($game_alternates_array);
	} else {
		$game_alternates_count = 0;
	}

	$game_players_array = game_players_by_game($game_id);
	if ($game_players_array) {
		$game_players_count = count($game_players_array);
	} else {
		$game_players_count = 0;
	}

	$first_pot = "$" . number_format($game_winners_array[0]['amount'] * $total_pot, 2);
	$second_pot = "$" . number_format($game_winners_array[1]['amount'] * $total_pot, 2);
	$third_pot = "$" . number_format($game_winners_array[2]['amount'] * $total_pot, 2);

	$settings_array[0] = settings_by_id($game['settings_id']);
?>
<!DOCTYPE html>
<html>
   <head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Game Details</title>
   </head>
   <body>
      <div data-role="page" id="game_details">
         <div data-role="header" data-position="fixed">
            <h1>Game Details</h1>
				<?php require('includes/set_game_details.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <div class="ui-bar ui-bar-a ui-corner-all normal">
					<?php if ($next_game_id && $game['status'] == 0) { ?>
						<div style="float:left"><a href="game_details.php?game_id=<?php echo $next_game_id ?>"><img src="images/icons/carat-l-white.png" alt="next game"/></a></div>
					<?php } ?>
					<h2><?php echo $game_name; ?><span class="game_name"><?php echo (!empty($game_name_more)) ? '  [' . $game_name_more . ']' : ''; ?></span></h2>
					<?php if ($previous_game_id && $game['status'] == 0) { ?> 
						<div style="float:right"><a href="game_details.php?game_id=<?php echo $previous_game_id ?>"><img src="images/icons/carat-r-white.png" alt="previous game" /></a></div>
					<?php } ?>
				</div>
				<?php if ($game['status'] == 1) { ?>
					<div class="comment ui-bar ui-corner-all"><?php echo 'Game starts at ' . time_to_php($game['game_time']); ?></div>
				<?php } else { ?>
					<div class="alert2 ui-bar ui-corner-all"><?php echo 'This game has ended'; ?></div>
				<?php } ?>
            <div class="grid_container">
               <div class="ui-grid-a">
                  <div class="ui-block-a grid1">
                     <h4><a href="#game_players" data-transition="pop" data-rel="popup">Total Players</a></h4>
                     <p><?php echo $game_players_count; ?></p>
                  </div>
                  <div class="ui-block-b grid3">
                     <h4><a href="#game_pot" data-transition="pop" data-rel="popup">Total Pot</a></h4>
                     <p><?php echo $game_pot; ?></p>
                  </div>
               </div>
               <div class="ui-grid-b">
                  <div class="ui-block-a grid1">
                     <h4><a href="#first_payout" data-transition="pop" data-rel="popup">1st Payout</a></h4>
                     <p><?php echo $first_pot; ?></p>
                  </div>
                  <div class="ui-block-b grid2">
                     <h4><a href="#second_payout" data-transition="pop" data-rel="popup">2nd Payout</a></h4>
                     <p><?php echo $second_pot; ?></p>
                  </div>
                  <div class="ui-block-c grid3">
                     <h4><a href="#third_payout" data-transition="pop" data-rel="popup">3rd Payout</a></h4>
                     <p><?php echo $third_pot; ?></p>
                  </div>
               </div>
            </div>
            <div id="game_players" data-role="popup" data-arrow="true">
               <p>The total number of players in the game</p>
            </div>
            <div id="game_pot" data-role="popup" data-arrow="true">
               <p>The total pot for the game</p>
            </div>
            <div id="first_payout" data-role="popup" data-arrow="true">
               <p>The payout for first place</p>
            </div>
            <div id="second_payout" data-role="popup" data-arrow="true">
               <p>The payout for second place</p>
            </div>
            <div id="third_payout" data-role="popup" data-arrow="true">
               <p>The payout for third place</p>
            </div>
            <div data-role="collapsible-set">
               <div data-role="collapsible" data-collapsed="true">
                  <h3>Winners</h3>
                  <ol data-role="listview" data-inset="true" data-count-theme="a">
							<?php for ($i = 0; $i <= count($game_winners_array) - 1; $i++) { ?>
							<li>
								<a href="player_details.php?player_id=<?php echo $game_winners_array[$i]['player_id']; ?>"> 
									<h2><?php echo $game_winners_array[$i]['full_name']; ?>
										<span class="ui-li-count"><?php echo trim(number_format($game_winners_array[$i]['points'], 2)); ?></span>
									</h2>
									<p style="margin-top:5px">Payout: <span class="info"><?php echo "$" . number_format($game_winners_array[$i]['split_diff'] * $total_pot, 2); ?></span>
										<?php
										if ($game_winners_array[$i]['split'] == 1) {
											$split_percentage = $game_winners_array[$i]['split_diff'] * 100;
											echo '<span class="alert"> (split ' . number_format($split_percentage, 2) . '%)</span>';
										}
										?>
									</p>
								</a>
							</li>
							<?php } ?>
                  </ol>
               </div>
               <div data-role="collapsible" data-collapsed="true">
                  <h3>Players</h3>
                  <ol data-role="listview" data-inset="true" data-count-theme="a">
							<?php for ($i = 0; $i <= $game_players_count - 1; $i++) { ?>
							<li>
								<a href="player_details.php?player_id=<?php echo $game_players_array[$i]['player_id']; ?>"> 
									<h2><?php echo $game_players_array[$i]['full_name']; ?></h2>
								</a>
							</li>
							<?php } ?>
                  </ol>
               </div>
					 <div data-role="collapsible" data-collapsed="true">
                  <h3>Alternates</h3>
                  <ol data-role="listview" data-inset="true" data-count-theme="a">
							<?php for ($i = 0; $i <= $game_alternates_count - 1; $i++) { ?>
							<li>
								<a href="player_details.php?player_id=<?php echo $game_alternates_array[$i]['player_id']; ?>"> 
									<h2><?php echo $game_alternates_array[$i]['full_name']; ?></h2>
								</a>
							</li>
							<?php } ?>
                  </ol>
               </div>
               <div data-role="collapsible" data-collapsed="true">
                  <h3>Season Rules</h3>
						<?php $i = 0; ?>
						<?php require('includes/get_scoring.php'); ?>
               </div>
            </div>
         </div>
         <div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
