<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_rankings.php'); ?>
<?php require('includes/get_payouts.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_winners.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php
$rankings = rankings_range();
$payouts = payout_range();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('includes/set_head.php'); ?>
    </head>
    <body>
        <div data-role="page" id="players">
            <?php require('includes/set_panel_date.php'); ?>
            <div data-role="header" data-position="fixed">
                <h1>Players</h1>
                <?php require('includes/set_players.php'); ?>
            </div>
            <div role="main" class="ui-content">
                <div data-role="tabs">
                    <div data-role="navbar" data-iconpos="bottom">
                        <ul>
                            <li><a href="#points" data-ajax="false" class="ui-btn-active ui-state-persist" data-icon="star">By Points</a></li>
                            <li><a href="#payouts" data-ajax="false" data-icon="tag">By Payouts</a></li>
                        </ul>
                    </div>
                    <div id="points">
                        <ul data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Find Player...">
                            <li data-role="list-divider">Overall Point Rankings</li>
                            <?php $score_rank = 0; ?>
                            <?php $current_score = 0; ?>
                            <?php $previous_score = 0; ?>
                            <?php
                            for ($i = 0; $i <= count($rankings) - 1; $i++) {
                                $current_score = number_format($rankings[$i]['point_sum'], 2);
                                if ($current_score != $previous_score) {
                                    $score_rank++;
                                }
                                ?>
                                <li><a href="player_details.php?player_id=<?php echo $rankings[$i]['player_id']; ?>"><span class="ranking"><?php echo $score_rank . '.  '; ?></span><?php echo $rankings[$i]['full_name']; ?><span class="ui-li-count"><?php echo(number_format($rankings[$i]['point_sum'], 2)); ?></span></a></li>
                                <?php
                                $previous_score = $current_score;
                            }
                            ?>
                        </ul>
                    </div>
                    <div id="payouts">
                        <ul data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Find Player...">
                            <li data-role="list-divider">Overall Payout Rankings</li>
                            <?php $payout_rank = 1; ?>
                            <?php $current_payout = 0; ?>
                            <?php $previous_payout = 0; ?>
                            <?php
                            for ($i = 0; $i <= count($payouts) - 1; $i++) {
                                $current_payout = "$" . number_format($payouts[$i]['total_amount'], 2);
                                if ($current_payout != $previous_payout) {
                                    $payout_rank++;
                                }
                                ?>
                                <li><a href="player_details.php?player_id=<?php echo $payouts[$i]['player_id']; ?>"><span class="ranking"><?php echo $payout_rank . '.  '; ?></span><?php echo $payouts[$i]['full_name']; ?><span class="ui-li-count"><?php echo("$" . number_format($payouts[$i]['total_amount'], 2)); ?></span></a></li>
                                <?php
                                    $previous_payout = $current_payout;
                                }
                                ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div data-role="footer" data-position="fixed">
                <?php require('includes/set_footer.php'); ?>
            </div>
        </div>
    </body>
</html>
