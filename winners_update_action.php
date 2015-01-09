<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_winners.php'); ?>
<?php
// UPDATE WINNERS
if ($_GET['update_details'] != '1') {
    for ($i = 1; $i <= $_POST['counter']; $i++) {
        $updateSQL = sprintf("UPDATE winners SET player_id=%s, split=%s WHERE winner_id=%s", GetSQLValueString($_POST['player' . $i], "int"), GetSQLValueString(isset($_POST['split' . $i]) ? "true" : "", "defined", "1", "0"), GetSQLValueString($_POST['winner_id' . $i], "int"));

        mysql_select_db($database_poker_db, $poker_db);
        $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());
    }
}

$game_id = $_GET['game_id'];
$game_array = games_game($game_id);
$settings_id = $game_array['settings_id'];
$settings_array = settings_current($settings_id);
$winners_array = winners_by_game($game_id);

// RESET POINTS AND PAYOUTS BACK TO ORIGINAL
$threshold_amount = intval($game_array['num_players'] / $settings_array['threshold']);
$multiply_amount = pow($settings_array['multiplier'], $threshold_amount); //$threshold_amount * $settings_array['multiplier'];

if ($multiply_amount == 0)
    $multiply_amount = 1;

$points = array(
     $settings_array['pt1'] * $multiply_amount,
     $settings_array['pt2'] * $multiply_amount,
     $settings_array['pt3'] * $multiply_amount,
     $settings_array['pt4'] * $multiply_amount,
     $settings_array['pt5'] * $multiply_amount,
     $settings_array['pt6'] * $multiply_amount,
     $settings_array['pt7'] * $multiply_amount,
     $settings_array['pt8'] * $multiply_amount,
     $settings_array['pt9'] * $multiply_amount,
     $settings_array['pt10'] * $multiply_amount);

for ($i = 0; $i <= count($winners_array) - 1; $i++) {
    switch ($i) {
        case 0:
            if ($settings_array['split_type'] == 'percent') {
                if ($_GET['update_details'] == '1') {
                    $split_diff = $winners_array[$i]['split_diff'];
                } else {
                    if ($_POST['split' . ($i + 1)] == 1)
                        $split_diff = $winners_array[$i]['split_diff'];
                    else
                        $split_diff = $settings_array['first_pay'];
                }
            } else
                $split_diff = $settings_array['first_pay'];

            $updateSQL = sprintf("UPDATE winners SET points=%s, amount=%s, split_diff=%s WHERE winner_id=%s", GetSQLValueString($points[$i], "double"), GetSQLValueString($settings_array['first_pay'], "double"), GetSQLValueString($split_diff, "double"), GetSQLValueString($winners_array[$i]['winner_id']));

            mysql_select_db($database_poker_db, $poker_db);
            $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());
            break;

        case 1:
            if ($settings_array['split_type'] == 'percent') {
                if ($_GET['update_details'] == '1') {
                    $split_diff = $winners_array[$i]['split_diff'];
                } else {
                    if ($_POST['split' . ($i + 1)] == 1)
                        $split_diff = $winners_array[$i]['split_diff'];
                    else
                        $split_diff = $settings_array['second_pay'];
                }
            } else
                $split_diff = $settings_array['second_pay'];

            $updateSQL = sprintf("UPDATE winners SET points=%s, amount=%s, split_diff=%s WHERE winner_id=%s", GetSQLValueString($points[$i], "double"), GetSQLValueString($settings_array['second_pay'], "double"), GetSQLValueString($split_diff, "double"), GetSQLValueString($winners_array[$i]['winner_id']));

            mysql_select_db($database_poker_db, $poker_db);
            $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());
            break;

        case 2:
            if ($settings_array['split_type'] == 'percent') {
                if ($_GET['update_details'] == '1') {
                    $split_diff = $winners_array[$i]['split_diff'];
                } else {
                    if ($_POST['split' . ($i + 1)] == 1)
                        $split_diff = $winners_array[$i]['split_diff'];
                    else
                        $split_diff = $settings_array['third_pay'];
                }
            } else
                $split_diff = $settings_array['third_pay'];

            $updateSQL = sprintf("UPDATE winners SET points=%s, amount=%s, split_diff=%s WHERE winner_id=%s", GetSQLValueString($points[$i], "double"), GetSQLValueString($settings_array['third_pay'], "double"), GetSQLValueString($split_diff, "double"), GetSQLValueString($winners_array[$i]['winner_id']));

            mysql_select_db($database_poker_db, $poker_db);
            $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());
            break;

        default:
            if ($settings_array['split_type'] == 'percent') {
                if ($_GET['update_details'] == '1') {
                    $split_diff = $winners_array[$i]['split_diff'];
                } else {
                    if ($_POST['split' . ($i + 1)] == 1)
                        $split_diff = $winners_array[$i]['split_diff'];
                    else
                        $split_diff = 0;
                }
            } else
                $split_diff = 0;

            $updateSQL = sprintf("UPDATE winners SET points=%s, amount=0, split_diff=%s WHERE winner_id=%s", GetSQLValueString($points[$i], "double"), GetSQLValueString($split_diff, "double"), GetSQLValueString($winners_array[$i]['winner_id']));

            mysql_select_db($database_poker_db, $poker_db);
            $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());
    }
}
?>
<?php
// RECALCULATE POINT AND PAYOUT SPLITS AND UPDATE FIELD
$colname_player_splits = "-1";
if (isset($_GET['game_id'])) {
    $colname_player_splits = $_GET['game_id'];
}
mysql_select_db($database_poker_db, $poker_db);
$query_player_splits = sprintf("SELECT winner_id, split_diff FROM winners WHERE game_id = %s AND split = 1", GetSQLValueString($colname_player_splits, "int"));
$player_splits = mysql_query($query_player_splits, $poker_db) or die(mysql_error());
$row_player_splits = mysql_fetch_assoc($player_splits);
$totalRows_player_splits = mysql_num_rows($player_splits);

$colname_split_sums = "-1";
if (isset($_GET['game_id'])) {
    $colname_split_sums = $_GET['game_id'];
}
mysql_select_db($database_poker_db, $poker_db);
$query_split_sums = sprintf("SELECT SUM(split_diff) AS split_sum, SUM(points) AS point_sum, SUM(amount) AS amount_sum FROM winners WHERE game_id = %s AND split = 1", GetSQLValueString($colname_split_sums, "int"));
$split_sums = mysql_query($query_split_sums, $poker_db) or die(mysql_error());
$row_split_sums = mysql_fetch_assoc($split_sums);
$totalRows_split_sums = mysql_num_rows($split_sums);

// IF SPLIT TYPE IS EVEN
if ($settings_array['split_type'] == 'even') {
    $payout_split = $row_split_sums['split_sum'] / $totalRows_player_splits;
    $points_split = $row_split_sums['point_sum'] / $totalRows_player_splits;

    do {
        $updateSQL = sprintf("UPDATE winners SET split_diff=%s, points=%s WHERE winner_id=%s", GetSQLValueString($payout_split, "double"), GetSQLValueString($points_split, "double"), GetSQLValueString($row_player_splits['winner_id'], "int"));

        mysql_select_db($database_poker_db, $poker_db);
        $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());
    } while ($row_player_splits = mysql_fetch_assoc($player_splits));
} else {
    // IF SPLIT TYPE IS PERCENT
    $i = 1;
    $A = $settings_array['first_pay'];
    $B = $settings_array['second_pay'];
    $C = $settings_array['third_pay'];
    $game_pot = $game_array['total_pot'];
    $game_points = array_sum($points);
    $AP = $points[0] / $game_points;
    $BP = $points[1] / $game_points;
    $CP = $points[2] / $game_points;

    do {
        if (isset($_POST['split_percent' . $i])) {
            $split_diff = $_POST['split_percent' . $i] / 100; // THIS CONVERTS THE PLAYERS SPLIT PERCENTAGE TO A DECIMAL VALUE
            //$points_split = $row_split_sums['point_sum'] * $split_diff;

            switch ($totalRows_player_splits) {
                case 0:
                    break;
                case 1:
                    break;
                case 2:
                    $payout_split = (($B * $game_pot) + (($A - $B) * ($game_pot * $split_diff))) / $game_pot;
                    $points_split = ($BP * $game_points) + (($AP - $BP) * ($game_points * $split_diff));
                    break;
                case 3:
                    $payout_split = (($C * $game_pot) + ((1 - (3 * $C)) * ($game_pot * $split_diff))) / $game_pot;
                    $points_split = $row_split_sums['point_sum'] * $payout_split;
                    break;
                default:
                    $payout_split = ($game_pot * $split_diff) / $game_pot;
                    $points_split = $row_split_sums['point_sum'] * $payout_split;
            }
        } else {
            $split_diff = $row_player_splits['split_diff'];
            $payout_split = $split_diff;
            $points_split = $row_split_sums['point_sum'] * $split_diff;
            $points_split = $points_split / $row_split_sums['amount_sum'];
        }

        $updateSQL = sprintf("UPDATE winners SET split_diff=%s, points=%s WHERE winner_id=%s", GetSQLValueString($payout_split, "double"), GetSQLValueString($points_split, "double"), GetSQLValueString($row_player_splits['winner_id'], "int"));

        mysql_select_db($database_poker_db, $poker_db);
        $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());

        $i++;
    } while ($row_player_splits = mysql_fetch_assoc($player_splits));
}

$updateGoTo = "games.php";
if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
}
header(sprintf("Location: %s", $updateGoTo));
?>
<!DOCTYPE html>
<html>
    <head>
        <script>
        </script>
    </head>
    <body>

    </body>
</html>
<?php
mysql_free_result($player_splits);

mysql_free_result($split_sums);
?>
