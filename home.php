<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/get_rankings.php'); ?>
<?php require('includes/get_payouts.php'); ?>
<?php require('includes/get_game_players.php'); ?>
<?php
$top_3 = rankings_range(1, 3);
$money_makers = payout_range(1, 3);
$number_games_played = games_played_count();
$number_registered = players_count();
$number_players_played = game_players_count();
$largest_pot = games_largest_pot();
$total_payout = games_pot_sum();
$total_payout_format = "$" . number_format($total_payout, 2);
$avg_players = number_format($number_players_played / $number_games_played, 0);
$avg_pot = "$" . number_format($total_payout / $number_games_played, 2);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('includes/set_head.php'); ?>
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
                    <p>This is the largest pot to date of all games played</p>
                </div>
                <div id="avg_players" data-role="popup" data-arrow="true">
                    <p>The average amount of players per game</p>
                </div>
                <div id="total_payout" data-role="popup" data-arrow="true">
                    <p>This is a cumulative sum of all pots per game</p>
                </div>
                <div id="total_players" data-role="popup" data-arrow="true">
                    <p>This is the total amount of registered players</p>
                </div>
                <div id="total_games" data-role="popup" data-arrow="true">
                    <p>This is the total amount of games played</p>
                </div>
                <ol data-role="listview" data-inset="true">
                    <li data-role="list-divider">Top Dogs</li>
                    <?php for ($i = 0; $i <= count($top_3) - 1; $i++) { ?>
                        <li><a href="player_details.php?player_id=<?php echo $top_3[$i]['player_id']; ?>"><?php echo $top_3[$i]['full_name']; ?><span class="ui-li-count"><?php echo(number_format($top_3[$i]['point_sum'], 2)); ?></span></a></li>
                    <?php } ?>
                </ol>
                <ol data-role="listview" data-inset="true">
                    <li data-role="list-divider">Money Makers</li>
                    <?php for ($i = 0; $i <= count($money_makers) - 1; $i++) { ?>
                        <li><a href="player_details.php?player_id=<?php echo $money_makers[$i]['player_id']; ?>"><?php echo $money_makers[$i]['full_name']; ?><span class="ui-li-count"><?php echo '$' . number_format($money_makers[$i]['total_amount'], 2); //echo money_format('%n', $money_makers[$i]['total_amount']); ?></span></a></li>
                            <?php } ?>
                </ol>
            </div>
            <div data-role="footer" data-position="fixed">
                <?php require('includes/set_footer.php'); ?>
            </div>
        </div>
    </body>
</html>
