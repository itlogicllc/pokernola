<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/set_access.php'); ?>
<?php get_access(1); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "settings")) {
    $insertSQL = sprintf("INSERT INTO settings (season_name, start_date, end_date, max_players, first_pay, second_pay, third_pay, pt1, pt2, pt3, pt4, pt5, pt6, pt7, pt8, pt9, pt10, threshold, multiplier, ptplay, split_type, ko, bounty) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
         GetSQLValueString($_POST['season_name'], "text"),
         GetSQLValueString(date_to_mysql($_POST['start_date']), "date"),
         GetSQLValueString(date_to_mysql($_POST['end_date']), "date"),
         GetSQLValueString($_POST['max_players'], "int"),
         GetSQLValueString($_POST['first_pay'], "double"),
         GetSQLValueString($_POST['second_pay'], "double"),
         GetSQLValueString($_POST['third_pay'], "double"),
         GetSQLValueString($_POST['pt1'], "double"),
         GetSQLValueString($_POST['pt2'], "double"),
         GetSQLValueString($_POST['pt3'], "double"),
         GetSQLValueString($_POST['pt4'], "double"),
         GetSQLValueString($_POST['pt5'], "double"),
         GetSQLValueString($_POST['pt6'], "double"),
         GetSQLValueString($_POST['pt7'], "double"),
         GetSQLValueString($_POST['pt8'], "double"),
         GetSQLValueString($_POST['pt9'], "double"),
         GetSQLValueString($_POST['pt10'], "double"),
         GetSQLValueString($_POST['threshold'], "int"),
         GetSQLValueString($_POST['multiplier'], "double"),
         GetSQLValueString($_POST['ptplay'], "double"),
         GetSQLValueString($_POST['split_type'], "text"),
         GetSQLValueString($_POST['ko'], "int"),
         GetSQLValueString($_POST['bounty'], "int"));

    mysql_select_db($database_poker_db, $poker_db);
    $Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());

    $insertGoTo = "tools_settings.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}

$current_settings = settings_current();
?>
<!DOCTYPE html>
<html>
    <head>
<?php require('includes/set_head.php'); ?>
    </head>
    <body>
        <div data-role="page" id="setings">
<?php require('includes/set_panel_login.php'); ?>
            <div data-role="header" data-position="fixed">
                <h1>Create New Season</h1>
<?php require('includes/set_tools.php'); ?>
            </div>
            <div role="main" class="ui-content">
                <form action="<?php echo $editFormAction; ?>" id="settings" name="settings" method="POST" novalidate>
                    <div data-role="fieldcontain">
                        <label for="season_name">Season Name:</label>
                        <input id="season_name" name="season_name" type="text" value="<?php echo $current_settings['season_name']; ?>">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="start_date">Season Start Date:</label>
                        <input id="start_date" name="start_date" type="text" data-role="datebox" data-options='{"mode":"calbox", "useFocus":true, "defaultValue":"<?php echo $current_settings['start_date']; ?>", "showInitialValue":true}'>
                        <label for="end_date">Season End Date:</label>
                        <input id="end_date" name="end_date" type="text" data-role="datebox" data-options='{"mode":"calbox", "useFocus":true, "defaultValue":"<?php echo $current_settings['end_date']; ?>", "showInitialValue":true}'>
                    </div>
                    <div data-role="fieldcontain">
                        <label for="max_players">Maximum Players:</label>
                        <input name="max_players" id="max_players" type="number" value="<?php echo $current_settings['max_players']; ?>"  />    
                    </div>
                    <div data-role="fieldcontain">
                        <label for="first_pay">First Payout:</label>
                        <input name="first_pay" id="first_pay" type="number" value="<?php echo $current_settings['first_pay']; ?>"  />
                        <label for="second_pay">Second Payout:</label>
                        <input name="second_pay" id="second_pay" type="number" value="<?php echo $current_settings['second_pay']; ?>"  />
                        <label for="third_pay">Third Payout:</label>
                        <input type="number" name="third_pay" id="third_pay" value="<?php echo $current_settings['third_pay']; ?>"  />
                    </div>
                    <div data-role="fieldcontain">
                        <div class="label_div">Points Per Place:</div>
                        <div class="ui-grid-b">
                            <div class="ui-block-a">
                                <label for="pt1">1st:</label>
                                <input type="number" name="pt1" id="pt1" value="<?php echo $current_settings['pt1']; ?>" />
                            </div>
                            <div class="ui-block-b">
                                <label for="pt2">2nd:</label>
                                <input type="number" name="pt2" id="pt2" value="<?php echo $current_settings['pt2']; ?>" />	
                            </div>
                            <div class="ui-block-c">
                                <label for="pt3">3rd:</label>
                                <input type="number" name="pt3" id="pt3" value="<?php echo $current_settings['pt3']; ?>" />
                            </div>
                            <div class="ui-block-a">
                                <label for="pt4">4th:</label>
                                <input type="number" name="pt4" id="pt4" value="<?php echo $current_settings['pt4']; ?>" />
                            </div>
                            <div class="ui-block-b">
                                <label for="pt5">5th:</label>
                                <input type="number" name="pt5" id="pt5" value="<?php echo $current_settings['pt5']; ?>" />
                            </div>
                            <div class="ui-block-c">
                                <label for="pt6">6th:</label>
                                <input type="number" name="pt6" id="pt6" value="<?php echo $current_settings['pt6']; ?>" />
                            </div>
                            <div class="ui-block-a">
                                <label for="pt7">7th:</label>
                                <input type="number" name="pt7" id="pt7" value="<?php echo $current_settings['pt7']; ?>" />
                            </div>
                            <div class="ui-block-b">
                                <label for="pt8">8th:</label>
                                <input type="number" name="pt8" id="pt8" value="<?php echo $current_settings['pt8']; ?>" />
                            </div>
                            <div class="ui-block-c">
                                <label for="pt9">9th:</label>
                                <input type="number" name="pt9" id="pt9" value="<?php echo $current_settings['pt9']; ?>" />
                            </div>
                            <div class="ui-block-a">
                                <label for="pt10">10th:</label>
                                <input type="number" name="pt10" id="pt10" value="<?php echo $current_settings['pt10']; ?>" />
                            </div>
                            <div class="ui-block-b">
                            </div>
                            <div class="ui-block-c">
                            </div>
                        </div>
                    </div>
                    <div data-role="fieldcontain">
                        <label for="threshold">Player Threshold:</label>
                        <input type="number" name="threshold" id="threshold" value="<?php echo $current_settings['threshold']; ?>" />
                        <label for="multiplier">Point Multiplier:</label>
                        <input type="number" name="multiplier" id="multiplier" value="<?php echo $current_settings['multiplier']; ?>" />
                    </div>
                     <div data-role="fieldcontain">
                        <label for="ptplay">Points for Playing:</label>
                        <input type="number" name="ptplay" id="ptplay" value="<?php echo $current_settings['ptplay']; ?>" />
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup" data-type="horizontal">
                            <legend>Split Type:</legend>
                            <input type="radio" name="split_type" id="evenly" value="even" <?php echo ($current_settings['split_type'] == 'even' ? 'checked' : ''); ?>/>
                            <label for="evenly">Evenly</label>
                            <input type="radio" name="split_type" id="percentage" value="percent" <?php echo ($current_settings['split_type'] == 'percent' ? 'checked' : ''); ?>/>
                            <label for="percentage">Percent</label>
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <label for="ko">Knock Out Points:</label>
                        <input type="number" name="ko" id="ko" value="<?php echo $current_settings['ko']; ?>" />
                        <label for="bounty">Bounty Points:</label>
                        <input type="number" name="bounty" id="bounty" value="<?php echo $current_settings['bounty']; ?>" />	
                    </div>
                    <div data-role="controlgroup" data-type="horizontal">
                        <label for="settings_id" class="ui-hidden-accessible">Text Input:</label>
                        <input type="hidden" name="settings_id" id="settings_id" value="<?php echo $current_settings['settings_id']; ?>"  />
                        <input name="Submit" type="submit" value="Create New Season" />
                        <input name="Reset" type="reset" value="Reset" />
                    </div>
                    <input type="hidden" name="MM_insert" value="settings">
                </form>
            </div>
            <div data-role="footer" data-position="fixed">
<?php require('includes/set_footer.php'); ?>
            </div>
        </div>
    </body>
</html>
