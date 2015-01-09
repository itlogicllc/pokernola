<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_winners.php'); ?>
<?php require('includes/get_game_players.php'); ?>
<?php
$game_id = $_GET['game_id'];
$game_array = games_game($game_id);
$total_pot = $game_array['total_pot'];
$game_pot = money_format('%n', $total_pot);
$game_winners_array = winners_by_game($game_id);
$game_players_array = game_players_by_game($game_id);
$first_pot = money_format('%n', $game_winners_array[0]['amount'] * $total_pot);
$second_pot = money_format('%n', $game_winners_array[1]['amount'] * $total_pot);
$third_pot = money_format('%n', $game_winners_array[2]['amount'] * $total_pot);
$settings_array[0] = settings_current($game_array['settings_id']);

if ($game_array['registration'] == 1) {
    header('Location: game_registration.php?game_id=' . $game_id);
    die();
}
?>
<!DOCTYPE html>
<html>
    <head>
<?php require('includes/set_head.php'); ?>
    </head>
    <body>
        <div data-role="page" id="game_details">
            <?php require('includes/set_panel_login.php'); ?>
            <div data-role="header" data-position="fixed">
                <h1>Game Details</h1>
<?php require('includes/set_games.php'); ?>
            </div>
            <div role="main" class="ui-content">
                <div class="ui-bar ui-bar-a ui-corner-all" align="center"><h2><?php echo date_to_php($game_array['game_name']); ?></h2></div>
                <div class="grid_container">
                    <div class="ui-grid-a">
                        <div class="ui-block-a grid1">
                            <h4><a href="#game_players" data-transition="pop" data-rel="popup">Total Players</a></h4>
                            <p><?php echo $game_array['num_players']; ?></p>
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
                    <p>This is the total amount of players in the game</p>
                </div>
                <div id="game_pot" data-role="popup" data-arrow="true">
                    <p>This is the total pot for the game</p>
                </div>
                <div id="first_payout" data-role="popup" data-arrow="true">
                    <p>This is the payout for first place</p>
                </div>
                <div id="second_payout" data-role="popup" data-arrow="true">
                    <p>This is the payout for second place</p>
                </div>
                <div id="third_payout" data-role="popup" data-arrow="true">
                    <p>This is the payout for third place</p>
                </div>
                <div data-role="collapsible-set">
                    <div data-role="collapsible" data-collapsed="true">
                        <h3>Winners</h3>
                        <ol data-role="listview" data-inset="false" data-count-theme="a">
<?php for ($i = 0; $i <= count($game_winners_array) - 1; $i++) { ?>
                                <li>
                                    <a href="player_details.php?player_id=<?php echo $game_winners_array[$i]['player_id']; ?>"> 
                                        <h2><?php echo $game_winners_array[$i]['full_name']; ?><span class="ui-li-count"><?php echo trim(number_format($game_winners_array[$i]['points'], 2)); ?></span></h2>
                                        <p style="margin-top:5px">Payout: <strong class="info"><?php echo money_format('%n', $game_winners_array[$i]['split_diff'] * $total_pot); ?></strong>
                                            <?php
                                            if ($game_winners_array[$i]['split'] == 1) {
                                                $split_percentage = $game_winners_array[$i]['split_diff'] * 100;
                                                echo '<span class="alert"> (split ' . $split_percentage . '%)</span>';
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
                        <ul data-role="listview" data-inset="false" data-count-theme="a">
                            <?php for ($i = 0; $i <= count($game_players_array) - 1; $i++) { ?>
                            <li>
                                <a href="player_details.php?player_id=<?php echo $game_players_array[$i]['player_id']; ?>"> 
                                    <h2><?php echo $game_players_array[$i]['full_name']; ?></h2>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
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
