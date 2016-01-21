<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	//get_access();
	require('includes/get_players.php');
	require('includes/get_winners.php');
	require('includes/get_games.php');
	require('includes/get_rankings.php');
	require('includes/get_game_players.php');
	
	// Make sure the query string has a player_id, if not redirect to access denied.
	if (!empty($_GET['player_id'])) {
		$player_id = trim($_GET['player_id']);
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}

	// Get the player details associated with the querystring's player_id
	$player = players_by_id($player_id);
	
	// Find the current player_id in the array of all players and point to it.
	// if there is a next element in the array set the next_player_id to the next game id
	// If there is a previous element in the array set the previous_player_id to the previous game _id.
	$player_pagation = rankings_range();
	for ($i = 0; $i <= count($player_pagation) - 1; $i++) {
		if ($player_pagation[$i]['player_id'] == $player_id) {
			break;
		}
	}
	
	if ($i < count($player_pagation) - 1) {
		$next_player_id = $player_pagation[$i + 1]['player_id'];
	} else {
		$next_player_id = false;
	}
	
	if ($i > 0) {
		$previous_player_id = $player_pagation[$i - 1]['player_id'];
	} else {
		$previous_player_id = false;
	}
	
	// If a player details array is returned then initalize all the variables.
	// If not, redirect to the access denied page.
	If ($player) {
		$player_rank = rankings_player($player_id);
		$top_10_count = winner_range_count($player_id, 1, 10);
		$top_3_count = winner_range_count($player_id, 1, 3);
		$total_payout = "$" . number_format(winner_total_payout($player_id), 2);
		$total_points = number_format(winner_total_points($player_id), 2);
		$games_count = count(games_played_all());
		$number_played = game_players_played($player_id);

		if ($number_played == 0) {
			$percent_top_10 = 0;
			$percent_top_3 = 0;
		} else {
			$percent_top_10 = $top_10_count / $number_played;
			$percent_top_3 = $top_3_count / $number_played;
		}
		
		// Calculate values using full percision
		$percent_played = $number_played / $games_count;
		$comp_percent_top_10 = $percent_top_10 * $percent_played;
		$comp_percent_top_3 = $percent_top_3 * $percent_played;
		
		// After calculated format and round to percision used in display
		$percent_played_f = number_format(($percent_played * 100), 0) . "%";
		$percent_top_10_f = number_format(($percent_top_10 * 100), 0) . "%";
		$percent_top_3_f = number_format(($percent_top_3 * 100), 0) . "%";
		$comp_percent_top_10_f = number_format(($comp_percent_top_10 * 100), 0) . "%";
		$comp_percent_top_3_f = number_format(($comp_percent_top_3 * 100), 0) . "%";
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
		<title>Poker NOLA Player Details</title>
	</head>
	<body>
		<div data-role="page" id="player_details">
			<?php require('includes/set_panel_date.php'); ?>
			<div data-role="header" data-position="fixed">
				<h1>Player Details</h1>
				<?php require('includes/set_players.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<div class="ui-bar ui-bar-a ui-corner-all normal">
					<?php if ($next_player_id) { ?>
						<div style="float:left"><a href="player_details.php?player_id=<?php echo $next_player_id ?>"><img src="images/icons/carat-l-white.png" alt="next player"/></a></div>
					<?php } ?>
					<h2><?php echo $player['full_name']; ?></h2>
					<?php if ($previous_player_id) { ?> 
						<div style="float:right"><a href="player_details.php?player_id=<?php echo $previous_player_id ?>"><img src="images/icons/carat-r-white.png" alt="previous player" /></a></div>
					<?php } ?>
				</div>
				<div class="comment ui-bar ui-bar-b ui-corner-all"><?php echo $player['comment']; ?></div>
				<div class="grid_container">
					<div class="ui-grid-b">
						<div class="ui-block-a grid1"><h4><a href="#rank" data-transition="pop" data-rel="popup">Rank</a></h4><p><?php echo $player_rank; ?></p></div>
						<div class="ui-block-b grid2"><h4><a href="#total_points" data-transition="pop" data-rel="popup">Total Points</a></h4><p><?php echo $total_points; ?></p></div>
						<div class="ui-block-c grid3"><h4><a href="#total_payouts" data-transition="pop" data-rel="popup">Total Payout</a></h4><p><?php echo $total_payout; ?></p></div>
					</div>
					<div class="ui-grid-b">
						<div class="ui-block-a grid3"><h4><a href="#played" data-transition="pop" data-rel="popup">Played</a></h4><p><?php echo $percent_played_f; ?></p></div>
						<div class="ui-block-b grid1"><h4><a href="#top_10" data-transition="pop" data-rel="popup">Scored</a></h4><p><?php echo $percent_top_10_f . '(' . $comp_percent_top_10_f . ')'; ?></p></div>
						<div class="ui-block-c grid2"><h4><a href="#itm" data-transition="pop" data-rel="popup">Paid</a></h4><p><?php echo $percent_top_3_f . '(' . $comp_percent_top_3_f . ')'; ?></p></div>
					</div>
				</div>
				<div id="rank" data-role="popup" data-arrow="true">
					<p>This is the player's overall ranking based on the sum of all points earned</p>
				</div>
				<div id="played" data-role="popup" data-arrow="true">
					<p>This is the percentage of times the player has played</p>
				</div>
				<div id="top_10" data-role="popup" data-arrow="true">
					<p>This is the percentage of times the player has scored points based on how many times they have played. The percentage in parentheses is the percentage of times points have been scored based on the percentage of total games they played in.</p>
				</div>
				<div id="itm" data-role="popup" data-arrow="true">
					<p>This is the percentage of times the player has been in the money based on how many times they have played. The percentage in parentheses is the percentage of times in the money based on the percentage of total games they played in.</p>
				</div>
				<div id="total_points" data-role="popup" data-arrow="true">
					<p>This is the total sum of all points earned</p>
				</div>
				<div id="total_payouts" data-role="popup" data-arrow="true">
					<p>This is the total sum of all payouts won</p>
				</div>
				<div data-role="controlgroup" data-type="vertical">
					<a class="ui-btn ui-corner-all ui-shadow ui-icon-eye ui-btn-icon-left" href="#chartpop" data-transition="pop" data-rel="popup" data-position-to="window" onClick="viewRankHistoryChart();">View Ranking History Chart</a>
				</div>
				<div data-role="collapsible-set">
				<?php
					// TODO: This will have to change to accomidate other place records than 10
					for ($i = 1; $i <= 10; $i++) {
						// Get an array of all games the player has finished in the given place
						$place = winner_by_place($player_id, $i);
						
						//  If place is false skip and check the next place value
						if ($place) {
							$place_amount = count($place);
							
							// Calculate the percentages for the given place
							if ($number_played == 0) {
								$place_percent = 0;
							} else {
								$place_percent = $place_amount / $number_played;
							}

							$comp_place_percent = $place_percent * $percent_played;
							
							// Format the calculations to display
							$place_percent_f = number_format(($place_percent * 100), 0) . "%";
							$comp_place_percent_f = number_format(($comp_place_percent * 100), 0) . "%";
				?>
						<div data-role="collapsible" data-collapsed="true">
							<h3>Placed <?php echo $i; ?>: <span class="placed"><?php echo $place_amount; ?> times - <?php echo $place_percent_f; ?> (<?php echo $comp_place_percent_f; ?>)</span></h3>
							<ul data-role="listview" data-inset="true">
								<?php for ($li = 0; $li <= $place_amount - 1; $li++) { ?>
								<li>
									<a href="game_details.php?game_id=<?php echo $place[$li]['game_id']; ?>">
										<?php echo date_to_php($place[$li]['game_name']); ?>
										<span class="ui-li-count"><?php echo $place[$li]['points']; ?></span>
										<p style="padding-left:5px; margin-top:5px">
											<?php $payout = "$" . number_format(winner_game_payout($player_id, $place[$li]['game_id']), 2); ?>
											Payout: <strong class="info"><?php echo $payout; ?></strong>
											<?php
												if ($place[$li]['split'] == 1) {
													$split_percentage = number_format(($place[$li]['split_diff'] * 100), 2) . "%";
													echo '<span class="alert"> (split ' . $split_percentage . ')</span>';
												}
											?>
										</p>
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>
				<?php } ?>
				</div>
			</div>
			<div id="chartpop" data-role="popup" style="width:300px; height:400px;">
				<a class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right" href="#" data-rel="back">Close</a>
				<div id="chartdiv"></div>
			</div>
			<script>
				function viewRankHistoryChart() {
					$('#chartdiv').highcharts({
						chart: {
							type: 'line'
						},
						plotOptions: {
							series: {
								lineWidth: 1,
								marker: {
									radius: 3
								}
							}
						},
						title: {
							text: null
						},
						xAxis: {
							labels: {enabled: false}
						},
						yAxis: {
							title: {
								text: null
							},
							reversed: true,
							min: 1,
							max: <?php echo count(players_all()); ?>,
							tickInterval: 1
						},
						legend: {
							enabled: false
						},
						series: [{
								name: 'Player\'s Rank',
								color: '#00FF00',
								data: [
								<?php
									$games_array = array_reverse(games_played_all());

									for ($i = 0; $i <= count($games_array) - 1; $i++) {
										$rankings_history = rankings_history($games_array[$i]['game_date'], $player_id);

										if ($i < count($games_array) - 1) {
											echo $rankings_history . ", ";
										} else {
											echo $rankings_history;
										}
									}
								?>
								]
							}]
					});
				}
			</script>
			<div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>
