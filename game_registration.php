<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/get_game_players.php'); ?>
<?php
$game_id = $_GET['game_id'];
$player_id = $_SESSION['player_logged_in'];
$game_array = games_game($game_id);
$game_players_array = game_players_by_game($game_id);
$settings_array[0] = settings_current($game_array['settings_id']);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_POST["MM_insert"])) {
    if($_POST["MM_insert"] == "register") {
        $insertSQL = sprintf("INSERT INTO game_players (game_id, player_id) VALUES (%s, %s)",
             GetSQLValueString($game_id, "int"), 
             GetSQLValueString($player_id, "int"));
    } else {
        $insertSQL = sprintf("DELETE FROM game_players WHERE game_id=%s AND player_id=%s",
             GetSQLValueString($game_id, "int"), 
             GetSQLValueString($player_id, "int"));
    }
    mysql_select_db($database_poker_db, $poker_db);
    $Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());
    
    echo '<script> window.location = "' . $editFormAction . '"; </script>';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('includes/set_head.php'); ?>
    </head>
    <body>
        <div data-role="page" id="game_registration">
            <?php require('includes/set_panel_login.php'); ?>
            <div data-role="header" data-position="fixed">
                <h1>Game Registration</h1>
                <?php require('includes/set_game_details.php'); ?>
            </div>
            <div role="main" class="ui-content">
                <div class="ui-bar ui-bar-a ui-corner-all" align="center"><h2><?php echo date_to_php($game_array['game_name']); ?></h2></div>
                    <?php if ($player_id) { ?>
                        <?php if (game_players_registered($game_id, $player_id) == 1) { ?>
                            <form action="<?php echo $editFormAction; ?>" id="unregister" name="unregister" method="POST">
                            <input type="submit" value="Unregister" data-iconpos="top" data-icon="delete">
                            <input type="hidden" name="MM_insert" value="unregister">
                        <?php } else { ?>
                            <?php if (count($game_players_array) == $settings_array[0]['max_players']) { ?>
                                <br />
                                <div class="ui-body ui-body-a ui-corner-all alert" align="center">The maximum number of players has been reached and no new players are allowed. Please check back later in the event a new spot opens.</div>
                            <?php } else { ?>
                                <form action="<?php echo $editFormAction; ?>" id="register" name="register" method="POST">
                                <input type="submit" value="Register" data-iconpos="top" data-icon="check">
                                <input type="hidden" name="MM_insert" value="register">
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <?php if (count($game_players_array) == $settings_array[0]['max_players']) { ?>
                                <br />
                                <div class="ui-body ui-body-a ui-corner-all alert" align="center">The maximum number of players has been reached and no new players are allowed. Please check back later in the event a new spot opens.</div>
                        <?php } else { ?>
                                <form>
                                <input type="submit" value="Please log in to register or unregister" data-iconpos="top" data-icon="alert" disabled>
                        <?php } ?>
                    <?php } ?>
                    </form>
                <ol data-role="listview" data-inset="true">
                    <li data-role="list-divider">Registered Players (<?php echo count($game_players_array); ?> of <?php echo $settings_array[0]['max_players']; ?>)</li>
                    <?php for ($i = 0; $i <= count($game_players_array) - 1; $i++) { ?>
                        <li>
                            <a href="player_details.php?player_id=<?php echo $game_players_array[$i]['player_id']; ?>"> 
                                <h2><?php echo $game_players_array[$i]['full_name']; ?></h2>
                            </a>
                        </li>
                    <?php } ?>
                </ol>
            </div>
            <div data-role="footer" data-position="fixed">
                <?php require('includes/set_footer.php'); ?>
            </div>
        </div>
    </body>
</html>
