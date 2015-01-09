<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_winners.php'); ?>
<?php
$game_array = games_game();
$ko_num = $game_array['num_players'] - 1;
$ko_count = $_POST['ko_counter'];
$settings = settings_current();
$ko_points = $settings['ko'];
$bounty_points = $settings['bounty'];

if (isset($_POST['update'])) {
  for ($i = 0; $i <= $ko_count - 1; $i++) {
	  $updateSQL = sprintf("UPDATE winners SET player_id=%s, ko_id=%s WHERE winner_id=%s",
	  GetSQLValueString($_POST['koa' . $i], "int"),
	  GetSQLValueString($_POST['kob' . $i], "int"),
	  GetSQLValueString($_POST['ko_winner_id' . $i], "int"));
  
	  mysql_select_db($database_poker_db, $poker_db);
	  $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());
  }
}

if (isset($_POST['add'])) {
  $insertSQL = sprintf("INSERT INTO winners (game_id) VALUES (%s)",
  GetSQLValueString($_GET['game_id'], "int"));

  mysql_select_db($database_poker_db, $poker_db);
  $Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());
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