<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php
if (isset($_POST['add'])) {
  $insertSQL = sprintf("INSERT INTO game_players (game_id, player_id) VALUES (%s, %s)",
  GetSQLValueString($_GET['game_id'], "int"),
  GetSQLValueString($_POST['game_players_select'], "int"));

  mysql_select_db($database_poker_db, $poker_db);
  $Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());
}

$updateGoTo = "winners_update.php";
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