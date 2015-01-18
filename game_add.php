<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/set_access.php'); ?>
<?php get_access(1); ?>
<?php require('includes/get_games.php'); ?>
<?php $current_settings = settings_current(); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "add_game")) {
    $insertSQL = sprintf("INSERT INTO games (game_date, game_name, settings_id) VALUES (%s, %s, %s)",
         GetSQLValueString($_POST['add_date'], "date"), 
         GetSQLValueString(date_to_mysql($_POST['game_name']), "date"), 
         GetSQLValueString($current_settings['settings_id'], "int"));

    mysql_select_db($database_poker_db, $poker_db);
    $Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());

    $insertGoTo = "winners_add.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('includes/set_head.php'); ?>
    </head>
    <body>
        <div data-role="page" id="add_game">
            <div data-role="header" data-position="fixed">
                <h1>Add Game</h1>
                <?php require('includes/set_games.php'); ?>
            </div>
            <div role="main" class="ui-content">
                <form action="<?php echo $editFormAction; ?>" id="add_game" name="add_game" method="POST">
                    <div data-role="fieldcontain">
                        <label for="game_name">Game Date:</label>
                        <input id="game_name" name="game_name" type="text" data-role="datebox" data-options='{"mode":"calbox", "useInline":true, "showInitialValue":true}'>
                        <label for="add_date" class="ui-hide-label"></label>
                        <input name="add_date" type="hidden" id="add_date" value="<?php echo date('Y-m-d', time()); ?>" />
                    </div>
                    <div data-role="controlgroup" data-type="horizontal">
                        <input name="Submit" type="submit" value="Submit" />
                        <input name="Reset" type="reset" value="Reset" />
                    </div>
                    <input type="hidden" name="MM_insert" value="add_game">
                </form>
            </div>
            <div data-role="footer" data-position="fixed">
                <?php require('includes/set_footer.php'); ?>
            </div>
        </div>
    </body>
</html>
