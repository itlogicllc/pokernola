<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/get_rankings.php'); ?>
<?php require('includes/get_payouts.php'); ?>
<?php
$top_3 = rankings_range(1, 3);
$money_makers = payout_range(1, 3);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('includes/set_head.php'); ?>
    </head>
    <body>
        <div data-role="page" id="home">
            <?php require('includes/set_panel_date.php'); ?>
            <?php require('includes/set_panel_login.php'); ?>
            <div data-role="header" data-position="fixed">
                <h1>Home</h1>
                <?php require('includes/set_home.php'); ?>
            </div>
            <div role="main" class="ui-content">
                <div class="grid_container">
                    <div class="ui-grid-b">
                        <div class="ui-block-a grid1"><h4><a href="#total_games" data-transition="pop" data-rel="popup">Games</a></h4><p><?php echo games_played_count(); ?></p></div>
                        <div class="ui-block-b grid2"><h4><a href="#total_players" data-transition="pop" data-rel="popup">Registered</a></h4><p><?php echo players_count(); ?></p></div>
                        <div class="ui-block-c grid3"><h4><a href="#most_players" data-transition="pop" data-rel="popup">Most Players</a></h4><p><?php echo games_most_players(); ?></p></div>
                    </div>
                    <div class="ui-grid-a">
                        <div class="ui-block-a grid1"><h4><a href="#largest_pot" data-transition="pop" data-rel="popup">Largest Pot</a></h4><p><?php echo games_largest_pot(); ?></p></div>
                        <div class="ui-block-b grid3"><h4><a href="#total_payout" data-transition="pop" data-rel="popup">Total Payout</a></h4><p><?php echo games_pot_sum(); ?></p></div>
                    </div>
                </div>
                <div id="largest_pot" data-role="popup" data-arrow="true">
                    <p>This is the largest pot to date of all games played</p>
                </div>
                <div id="most_players" data-role="popup" data-arrow="true">
                    <p>This is the most players in any one game to date of all games played</p>
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
                        <li><a href="player_details.php?player_id=<?php echo $money_makers[$i]['player_id']; ?>"><?php echo $money_makers[$i]['full_name']; ?><span class="ui-li-count"><?php echo money_format('%n', $money_makers[$i]['total_amount']); ?></span></a></li>
                            <?php } ?>
                </ol>
            </div>
            <div data-role="footer" data-position="fixed">
                <?php require('includes/set_footer.php'); ?>
            </div>
        </div>
    </body>
</html>
