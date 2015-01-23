<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/get_winners.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_rankings.php'); ?>
<?php require('includes/get_game_players.php'); ?>

<?php
$player_id = $_GET['player_id'];
$player = players_player($player_id);
$player_rank = rankings_player($player_id);
$top_10_count = winner_range_count($player_id, 1, 10);
$top_3_count = winner_range_count($player_id, 1, 3);
$total_payout = winner_total_payout($player_id);
$total_points = winner_total_points($player_id);
$games_count = games_played_count();
$number_played = game_players_played($player_id);

if ($number_played == 0) {
   $percent_top_10 = 0;
   $percent_top_3 = 0;
} else{
   $percent_top_10 = $top_10_count / $number_played;
   $percent_top_3 = $top_3_count / $number_played;
}

$percent_played = $number_played / $games_count;
$comp_percent_top_10 = $percent_top_10 * $percent_played;
$percent_top_3 = $top_3_count / $number_played;
$comp_percent_top_3 = $percent_top_3 * $percent_played;
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('includes/set_head.php'); ?>
    </head>
    <body>
        <div data-role="page" id="player_details">
            <?php require('includes/set_panel_date.php'); ?>
            <div data-role="header" data-position="fixed">
                <h1>Player Details</h1>
                <?php require('includes/set_players.php'); ?>
            </div>
            <div role="main" class="ui-content">
                <div class="ui-bar ui-bar-a ui-corner-all" align="center"><h2><?php echo $player['full_name']; ?></h2></div>
                <div class="comment ui-bar ui-bar-b ui-corner-all"><?php echo $player['nickname']; ?></div>
                <div class="grid_container">
                    <div class="ui-grid-b">
                        <div class="ui-block-a grid1"><h4><a href="#rank" data-transition="pop" data-rel="popup">Rank</a></h4><p><?php echo $player_rank;  ?></p></div>
                        <div class="ui-block-b grid2"><h4><a href="#total_points" data-transition="pop" data-rel="popup">Total Points</a></h4><p><?php echo $total_points; ?></p></div>
                        <div class="ui-block-c grid3"><h4><a href="#total_payouts" data-transition="pop" data-rel="popup">Total Payout</a></h4><p><?php echo $total_payout; ?></p></div>
                    </div>
                    <div class="ui-grid-b">
                        <div class="ui-block-a grid3"><h4><a href="#played" data-transition="pop" data-rel="popup">Played</a></h4><p><?php echo number_format($percent_played, 1) * 100 . '%'; ?></p></div>
                        <div class="ui-block-b grid1"><h4><a href="#top_10" data-transition="pop" data-rel="popup">Scored</a></h4><p><?php echo number_format($percent_top_10, 1) * 100 . '% (' . number_format($comp_percent_top_10, 1) * 100 . '%)'; ?></p></div>
                        <div class="ui-block-c grid2"><h4><a href="#itm" data-transition="pop" data-rel="popup">Paid</a></h4><p><?php echo number_format($percent_top_3, 1) * 100 . '% (' . number_format($comp_percent_top_3, 1) * 100 . '%)'; ?></p></div>
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
                    <a class="ui-btn ui-corner-all ui-shadow ui-icon-eye ui-btn-icon-left" href="#chartpop<?php echo $random_num; ?>" data-transition="pop" data-rel="popup" data-position-to="window" onClick="viewRankHistoryChart();">View Ranking History Chart</a>
                </div>
                <div data-role="collapsible-set">
                    <?php
                    for ($i = 1; $i <= 10; $i++) {
                        $place = winner_by_place($player_id, $i);
                        $place_amount = count($place);
                        
                        if ($number_played == 0) {
                           $place_percent = 0; 
                        } else {
                           $place_percent = $place_amount / $number_played;
                        }
                      
                        $comp_place_percent = $place_percent * $percent_played;
                        if ($place_amount > 0) {
                            ?>
                            <div data-role="collapsible" data-collapsed="true">
                                <h3>Placed <?php echo $i; ?>: <span class="placed"><?php echo $place_amount; ?> times - <?php echo number_format($place_percent, 1) * 100; ?>% (<?php echo number_format($comp_place_percent, 1) * 100; ?>%)</span></h3>
                                <ul data-role="listview" data-inset="true">
                                            <?php for ($li = 0; $li <= $place_amount - 1; $li++) { ?>
                                        <li>
                                            <a href="game_details.php?game_id=<?php echo $place[$li]['game_id']; ?>">
            <?php echo date_to_php($place[$li]['game_name']); ?><span class="ui-li-count"><?php echo $place[$li]['points']; ?></span>
                                                <p style="padding-left:5px; margin-top:5px">
                                                    Payout: 
                                                    <strong class="info"><?php echo winner_game_payout($player_id, $place[$li]['game_id']); ?></strong>
                                                    <?php
                                                    if ($place[$li]['split'] == 1) {
                                                        $split_percentage = $place[$li]['split_diff'] * 100;
                                                        echo '<span class="alert"> (split ' . $split_percentage . '%)</span>';
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
            <div id="chartpop<?php echo $random_num; ?>" data-role="popup" style="width:300px; height:400px;">
                <a class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right" href="#" data-rel="back">Close</a>
                <div id="chartdiv<?php echo $random_num; ?>"></div>
            </div>
            <script>
                function viewRankHistoryChart() {
                    $('#chartdiv<?php echo $random_num; ?>').highcharts({
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
                            max: <?php echo players_count(); ?>,
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
$games_array = array_reverse(games_played_list());

for ($i = 0; $i <= count($games_array) - 1; $i++) {
    $rankings_history = rankings_history($games_array[$i]['game_date'], $_GET['player_id']);

    if ($i < count($games_array) - 1)
        echo $rankings_history . ", ";
    else
        echo $rankings_history;
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
