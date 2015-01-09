<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/set_access.php'); ?>
<?php get_access(1); ?>
<?php require('includes/get_players.php'); ?>
<?php
if ((isset($_POST['player_id'])) && ($_POST['player_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM players WHERE player_id=%s",
                       GetSQLValueString($_POST['player_id'], "int"));

  mysql_select_db($database_poker_db, $poker_db);
  $Result1 = mysql_query($deleteSQL, $poker_db) or die(mysql_error());

  $deleteGoTo = "players.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$players_list = players_list();
?>
<!DOCTYPE html>
<html>
<head>
<?php require('includes/set_head.php'); ?>
</head>
<body>
<div data-role="page" id="delete_player">
	<?php require('includes/set_panel_login.php'); ?>
	<div data-role="header" data-position="fixed">
		<h1>Delete Player</h1>
		<?php require('includes/set_players.php'); ?>
	</div>
	<div role="main" class="ui-content">
		<form id="delete_player" name="delete_player" method="POST" action="">
			<div data-role="fieldcontain">
					<label for="player_id">Select Player to Delete:</label>
					<select name="player_id" id="player_id" data-native-menu="true">
						<?php for ($i = 0; $i <= count($players_list) - 1; $i++) { ?>
							<option value="<?php echo $players_list[$i]['player_id']?>"><?php echo $players_list[$i]['full_name'] ?></option>
						<?php	} ?>
					</select>
			</div>
			<input type="submit" value="Delete Player" data-inline="true" />		
		</form>
	</div>
	<div data-role="footer" data-position="fixed">
		<?php require('includes/set_footer.php'); ?>
	</div>
</div>
</body>
</html>
