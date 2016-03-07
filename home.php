<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access();
	require('includes/get_games.php');
	require('includes/get_players.php');
	require('includes/get_rankings.php');
	require('includes/get_payouts.php');
	require('includes/get_game_players.php');

	$top_3 = rankings_top(3);
	$money_makers = payouts_top(3);
	$number_games_played = count(games_played_all());
	$number_registered = count(players_all());
	$number_players_played = game_players_count();
	$total_payout = array_sum(games_pots());
	$total_payout_format = "$" . number_format($total_payout, 2);
	
	// if number of games played is 0, set depending variables to 0 to avoid division by zero error
	if ($number_games_played > 0) {
		$avg_players = number_format($number_players_played / $number_games_played, 0);
		$avg_pot = "$" . number_format($total_payout / $number_games_played, 2);
		$largest_pot = "$" . number_format(max(games_pots()), 2);
	} else {
		$avg_players = 0;
		$avg_pot = "$0.00";
		$largest_pot = "$0.00";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Home</title> 
	</head>
	<body>
		<div data-role="page" id="home">
			<?php require('includes/set_panel_date.php'); ?>
			<div data-role="header" data-position="fixed">
				<h1>Home</h1>
				<?php require('includes/set_home.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<div class="grid_container">
					<div class="ui-grid-b">
						<div class="ui-block-a grid1"><h4><a href="#total_games" data-transition="pop" data-rel="popup">Games</a></h4><p><?php echo $number_games_played; ?></p></div>
						<div class="ui-block-b grid2"><h4><a href="#total_players" data-transition="pop" data-rel="popup">Registered</a></h4><p><?php echo $number_registered; ?></p></div>
						<div class="ui-block-c grid3"><h4><a href="#avg_players" data-transition="pop" data-rel="popup">Avg. Players</a></h4><p><?php echo $avg_players; ?></p></div>
					</div>
					<div class="ui-grid-b">
						<div class="ui-block-a grid3"><h4><a href="#largest_pot" data-transition="pop" data-rel="popup">Largest Pot</a></h4><p><?php echo $largest_pot; ?></p></div>
						<div class="ui-block-b grid1"><h4><a href="#avg_pot" data-transition="pop" data-rel="popup">Average Pot</a></h4><p><?php echo $avg_pot; ?></p></div>
						<div class="ui-block-c grid2"><h4><a href="#total_payout" data-transition="pop" data-rel="popup">Total Payout</a></h4><p><?php echo $total_payout_format; ?></p></div>
					</div>
				</div>
				<div id="largest_pot" data-role="popup" data-arrow="true">
					<p>The largest pot of all games played</p>
				</div>
				<div id="avg_players" data-role="popup" data-arrow="true">
					<p>The average number of players per game</p>
				</div>
				<div id="avg_pot" data-role="popup" data-arrow="true">
					<p>The average pot per game</p>
				</div>
				<div id="total_payout" data-role="popup" data-arrow="true">
					<p>The sum of all pots per game</p>
				</div>
				<div id="total_players" data-role="popup" data-arrow="true">
					<p>The total number of registered players</p>
				</div>
				<div id="total_games" data-role="popup" data-arrow="true">
					<p>The total number of games played</p>
				</div>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">Top Dogs</li>
					<?php for ($i = 0; $i <= count($top_3) - 1; $i++) { ?>
					<li>
						<a href="player_details.php?player_id=<?php echo $top_3[$i]['player_id']; ?>">
							<span class="ranking"><?php echo $top_3[$i]['rank'] . '.  '; ?></span>
							<?php echo $top_3[$i]['full_name']; ?>
							<span class="ui-li-count"><?php echo(number_format($top_3[$i]['point_sum'], 2)); ?></span>
						</a>
					</li>
					<?php } ?>
				</ul>
				<ul data-role="listview" data-inset="true">
					<li data-role="list-divider">Money Makers</li>
					<?php for ($i = 0; $i <= count($money_makers) - 1; $i++) { ?>
					<li>
						<a href="player_details.php?player_id=<?php echo $money_makers[$i]['player_id']; ?>">
							<span class="ranking"><?php echo $money_makers[$i]['rank'] . '.  '; ?></span>
							<?php echo $money_makers[$i]['full_name']; ?>
							<span class="ui-li-count"><?php echo '$' . number_format($money_makers[$i]['total_amount'], 2); ?></span>
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
			<div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>
