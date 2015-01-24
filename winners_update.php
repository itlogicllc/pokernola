<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/set_access.php'); ?>
<?php get_access(1); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_winners.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/get_game_players.php'); ?>
<?php
$random_num = mt_rand(1, 10000);

//$editFormAction = $_SERVER['PHP_SELF'];
//if (isset($_SERVER['QUERY_STRING'])) {
//    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
//}
//
//if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "game_update")) {
//    $updateSQL = sprintf("UPDATE games SET status=%s, registration=%s, game_name=%s, num_players=%s, total_pot=%s WHERE game_id=%s",
//            GetSQLValueString(isset($_POST['status']) ? "true" : "", "defined", "1", "0"),
//            GetSQLValueString(isset($_POST['registration']) ? "true" : "", "defined", "1", "0"),
//            GetSQLValueString(date_to_mysql($_POST['game_name']), "date"),
//            GetSQLValueString($_POST['total_players'], "int"),
//            GetSQLValueString($_POST['total_pot'], "int"),
//            GetSQLValueString($_POST['game_id'], "int"));
//
//    mysql_select_db($database_poker_db, $poker_db);
//    $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());

//    $updateGoTo = "winners_update_action.php?game_id=" . $_POST['game_id'] . '&update_details=1';
//    if (isset($_SERVER['QUERY_STRING'])) {
//        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
//        $updateGoTo .= $_SERVER['QUERY_STRING'];
//    }
//    header(sprintf("Location: %s", $updateGoTo));
//    exit();

//}

$game_id = $_GET['game_id'];
$winners_list = winners_by_game($game_id);
$ko_list = winners_ko_by_game($game_id);
$players_list = players_list();
$game = games_game($game_id);
$game_players_array = game_players_by_game($game_id);
$settings_array[0] = settings_current($game['settings_id']);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('includes/set_head.php'); ?>
    </head>
    <body>
        <div data-role="page" id="winner_update" data-dom-cache="false">
            <div data-role="header" data-position="fixed">
                <h1>Game Update</h1>
                <?php require('includes/set_winners_update.php'); ?>
            </div>
            <div role="main" class="ui-content">
                <div class="ui-bar ui-bar-a ui-corner-all" align="center"><h2><?php echo date_to_php($game['game_name']); ?></h2></div>
                <div data-role="collapsible-set">
                    <div data-role="collapsible" data-collapsed="true">
                        <h3>Game Details</h3>
                        <form action="<?php echo 'winners_update_action.php?game_id=' . $game_id . '&update_details=1' //echo $editFormAction; ?>" method="POST" name="game_update" id="game_update<?php echo $random_num; ?>">
                            <label for="status<?php echo $random_num; ?>">Game Status:</label>
                            <input name="status" id="status<?php echo $random_num; ?>" type="checkbox" data-role="flipswitch" <?php
                            if ($game['status'] == 1) {
                                echo 'checked=""';
                            }
                            ?>>
                            <label for="registration<?php echo $random_num; ?>">Registration:</label>
                            <input name="registration" id="registration<?php echo $random_num; ?>" type="checkbox" data-role="flipswitch" <?php
                            if ($game['registration'] == 1) {
                                echo 'checked=""';
                            }
                            ?>>

                            <label for="game_name<?php echo $random_num; ?>">Game Date:</label>
                            <input id="game_name<?php echo $random_num; ?>" name="game_name" type="text" data-role="datebox" data-options='{"mode":"calbox", "useFocus":true, "defaultValue":"<?php echo $game['game_name']; ?>",  "showInitialValue":true}'>

                            <label for="total_players<?php echo $random_num; ?>">Total Players:</label>
                            <input type="number" name="total_players" id="total_players<?php echo $random_num; ?>" value="<?php echo $game['num_players']; ?>"  />

                            <label for="total_pot<?php echo $random_num; ?>">Total Pot:</label>
                            <input type="number" name="total_pot" id="total_pot<?php echo $random_num; ?>" value="<?php echo $game['total_pot']; ?>"  />

                            <label for="game_id<?php echo $random_num; ?>" class="ui-hide-label"></label>
                            <input type="hidden" name="game_id" id="game_id<?php echo $random_num; ?>" value="<?php echo $game_id; ?>"  />

                            <input type="submit" name="submit" value="Update" data-inline="true" />
                            <input type="hidden" name="MM_update" value="game_update">
                        </form>
                    </div>
                    <div data-role="collapsible" data-collapsed="true">
                        <h3>Players</h3>
                        <form method="POST" name="players" id="players<?php echo $random_num; ?>" action="winners_add_players_action.php?game_id=<?php echo $game_id; ?>">
                            <select name="game_players_select" id="game_players_select<?php echo $random_num; ?>" onFocus="set_player_select(this, 'game_players');" data-native-menu="true">
                                <option value="0">Guest</option>
                                <?php //require('includes/get_game_players_select.php'); ?>
                            </select>
                            <input type="submit" name="submit" value="Add" data-inline="true" />
                            <input type="hidden" name="add" value="player_add">
                        </form>
                        <br />
                        <ul data-role="listview" data-inset="true" data-count-theme="a" data-icon="delete">
                           <?php for ($i = 0; $i <= count($game_players_array) - 1; $i++) { ?>
                                <li>
                                    <a href="winners_delete_players_action.php?game_id=<?php echo $game_id; ?>&player_id=<?php echo $game_players_array[$i]['player_id']; ?>"> 
                                        <h3><?php echo $game_players_array[$i]['full_name']; ?></h3>
                                        <input type="hidden" name="game_players" id="<?php echo 'game_players'.$i ?>" value="<?php echo $game_players_array[$i]['player_id']; ?>"  />
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div data-role="collapsible" data-collapsed="true">
                        <h3>Winners</h3>
                        <form name="winners" id="winners<?php echo $random_num; ?>" method="POST" action="winners_update_action.php?game_id=<?php echo $game_id; ?>">
                            <?php for ($i = 0; $i <= count($winners_list) - 1; $i++) { ?>
                                <label for="winner_id<?php echo $i + 1; ?><?php echo $random_num; ?>" class="ui-hide-label"></label>
                                <input type="hidden" name="winner_id<?php echo $i + 1; ?>" id="winner_id<?php echo $i + 1; ?><?php echo $random_num; ?>" value="<?php echo $winners_list[$i]['winner_id']; ?>"  />
                                <label for="counter<?php echo $random_num; ?>" class="ui-hide-label"></label>
                                <input type="hidden" name="counter" id="counter<?php echo $random_num; ?>" value="<?php echo $i + 1 ?>"  />
                                <div data-type="vertical" data-role="controlgroup">
                                    <label for="player_select<?php echo $i + 1; ?><?php echo $random_num; ?>"><strong>Winner <?php echo $i + 1; ?>:</strong></label>
                                    <select name="player_select" id="player_select<?php echo $i + 1; ?><?php echo $random_num; ?>" onFocus="set_player_select(this, 'player_select');" onChange="set_player_selected(this, getElementById('player<?php echo $i + 1; ?><?php echo $random_num; ?>'));" data-native-menu="true">
                                        <option value="0">Guest</option>
                                        <?php require('includes/get_player_select.php'); ?>
                                    </select>
                                    <label for="player<?php echo $i + 1; ?><?php echo $random_num; ?>" class="ui-hide-label"></label>
                                    <input type="hidden" name="player<?php echo $i + 1; ?>" id="player<?php echo $i + 1; ?><?php echo $random_num; ?>" value="<?php echo $winners_list[$i]['player_id']; ?>"  />
                                    <label for="split<?php echo $i + 1; ?><?php echo $random_num; ?>">Split: Winner <?php echo $i + 1; ?></label>
                                    <input type="checkbox" name="split<?php echo $i + 1; ?>" id="split<?php echo $i + 1; ?><?php echo $random_num; ?>" class="custom" value="<?php echo $winners_list[$i]['split']; ?>" <?php if ($winners_list[$i]['split'] == 1 && $settings_array[0]['split_type'] == 'even') echo 'checked' ?> <?php echo ($settings_array[0]['split_type'] == 'percent' ? 'onClick="setSplitPercent(this, getElementById(\'split_percent' . ($i + 1) . $random_num . '\'));" ' : ''); ?>/>
                                    <?php if ($settings_array[0]['split_type'] == 'percent') { ?>
                                        <label for="split_percent<?php echo $i + 1; ?><?php echo $random_num; ?>">&nbsp;% of remaining:</label>
                                        <input type="text" name="split_percent<?php echo $i + 1; ?>" id="split_percent<?php echo $i + 1; ?><?php echo $random_num; ?>" value="<?php echo $winners_list[$i]['split_diff'] * 100; ?>" />
                                        <br />
                                    <?php } ?> 
                                </div>
                            <?php } ?>
                            <input type="submit" name="submit" value="Update" data-inline="true" onClick="return addSplits();"/>
                        </form>
                        <script type="text/javascript">
                            function setSplitPercent(x, y) {
                                if (x.checked) {
                                    y.disabled = false;
                                    y.style.color = "#FFF";
                                    return true;
                                }
                                else {
                                    y.disabled = true;
                                    y.style.color = "#333";
                                    return false;
                                }
                            }
                            function addSplits() {
<?php if ($settings_array[0]['split_type'] == 'percent') { ?>
                                    var sum = 0;
<?php } else { ?>
                                    var sum = 100;
<?php } ?>
                                var checked = 0;
<?php for ($i = 0; $i <= count($winners_list) - 1; $i++) { ?>
                                    if (document.getElementById("split<?php echo($i + 1); ?><?php echo $random_num; ?>").checked) {
    <?php if ($settings_array[0]['split_type'] == 'percent') { ?>sum = parseInt(document.getElementById("split_percent<?php echo($i + 1); ?><?php echo $random_num; ?>").value) + sum;<?php } ?>
                    checked = checked + 1;
                }
<?php } ?>
            switch (checked) {
                case 0:
                    return true;
                    break;
                case 1:
                    alert("Please select more than one player to split");
                    return false;
                    break;
                default:
                    <?php for ($i = 0; $i <= count($winners_list) - 1; $i++) { ?>
                     if (document.getElementById("split<?php echo($i + 1); ?><?php echo $random_num; ?>").checked) {
                        document.getElementById("split_percent<?php echo($i + 1); ?><?php echo $random_num; ?>").value = (document.getElementById("split_percent<?php echo($i + 1); ?><?php echo $random_num; ?>").value / sum) * 100;
                     }
                     <?php } ?>
                     return true;
            }
         }
            
        function disableSplits() {
<?php if ($settings_array[0]['split_type'] == 'percent') { ?>
                var y = 0;

    <?php for ($i = 0; $i <= count($winners_list) - 1; $i++) { ?>
                    y = document.getElementById("split_percent<?php echo($i + 1); ?><?php echo $random_num; ?>");
                    y.disabled = true;
                    y.style.color = "#333";
    <?php } ?>
<?php } ?>
        }
        function set_player_select(x,y) {
            var selected_val = x.value;
            var remove_player = document.getElementsByName(y);
            var all_players = [
<?php for ($i = 0; $i < count($players_list) - 1; $i++) { ?>
                    ["<?php echo $players_list[$i]['player_id'] ?>", "<?php echo trim($players_list[$i]['full_name']) ?>"],
<?php } ?>
                ["<?php echo $players_list[$i]['player_id'] ?>", "<?php echo trim($players_list[$i]['full_name']) ?>"]];

            x.options.length = 1;

            for (i = 0; i <= remove_player.length - 1; i++) {
                for (i2 = 0; i2 <= all_players.length - 1; i2++) {
                    if (all_players[i2][0] == remove_player[i].value) {
                        all_players.splice(i2, 1);
                        break;
                    }
                }
            }
            for (i = 0; i <= all_players.length; i++) {
                if (all_players[i][0] == selected_val)
                    x.options[i + 1] = new Option(all_players[i][1], all_players[i][0], false, true);
                else
                    x.options[i + 1] = new Option(all_players[i][1], all_players[i][0], false, false);
            }
        }
        function set_player_selected(x, y) {
            y.value = x.value;
        }
                        </script>
                    </div>
                    <div data-role="collapsible">
                        <h3>Knock Outs</h3>
                        <form method="POST" name="knockouts" id="knockouts<?php echo $random_num; ?>" action="winners_update_ko_action.php?game_id=<?php echo $game_id; ?>">
                            <?php for ($i = 0; $i <= count($ko_list) - 1; $i++) { ?>
                                <label for="ko_winner_id<?php echo $i . $random_num; ?>" class="ui-hide-label"></label>
                                <input type="hidden" name="ko_winner_id<?php echo $i; ?>" id="ko_winner_id<?php echo $i . $random_num; ?>" value="<?php echo $ko_list[$i]['winner_id']; ?>"  />
                                <label for="ko_counter<?php echo $random_num; ?>" class="ui-hide-label"></label>
                                <input type="hidden" name="ko_counter" id="ko_counter<?php echo $random_num; ?>" value="<?php echo $i + 1; ?>"  />
                                <div data-type="vertical" data-role="controlgroup">
                                    <label for="<?php echo 'koa' . $i . $random_num ?>" class="ui-hide-label"></label>
                                    <select name="<?php echo 'koa' . $i ?>" id="<?php echo 'koa' . $i . $random_num ?>" onFocus="set_player_select(this);" onChange="set_player_selected(this, getElementById('player<?php echo $i + 1; ?><?php echo $random_num; ?>'));" data-native-menu="true">
                                        <option value="0">Guest</option>
                                        <?php require('includes/get_koer_select.php'); ?>
                                    </select>
                                    <label for="<?php echo 'kob' . $i . $random_num ?>" class="ui-hide-label"></label>
                                    <select name="<?php echo 'kob' . $i ?>" id="<?php echo 'kob' . $i . $random_num ?>" onFocus="set_player_select(this);" onChange="set_player_selected(this, getElementById('player<?php echo $i + 1; ?><?php echo $random_num; ?>'));" data-native-menu="true">
                                        <option value="0">Guest</option>
                                        <?php require('includes/get_ko_select.php'); ?>
                                    </select>                     
                                </div>
                            <?php } ?>
                            <div data-type="horizontal" data-role="controlgroup">
                                <input type="submit" name="update" value="Update" data-inline="true" />	
                                <input type="submit" name="add" value="Add" data-inline="true" />	
                                <input type="submit" name="delete" value="Delete" data-inline="true" />	
                            </div>
                        </form>
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

