<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_game_players.php'); ?>
<?php

if(!empty($_GET['game_id'])) {
	$game_id = $_GET['game_id'];
}
$game_players_array = $game_players_array = game_players_by_game($game_id);
  $insertSQL = sprintf("DELETE FROM game_players WHERE game_id=%s AND player_id=%s",
				GetSQLValueString($_GET['game_id'], "int"),
				GetSQLValueString($_GET['player_id'], "int"));

  mysql_select_db($database_poker_db, $poker_db);
  $Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());
  
  $updateSQL = sprintf("UPDATE games SET num_players=%s WHERE game_id=%s",
            GetSQLValueString(count($game_players_array) - 1, "int"),
			   GetSQLValueString($game_id, "int"));
  
   mysql_select_db($database_poker_db, $poker_db);
   $Result2 = mysql_query($updateSQL, $poker_db) or die(mysql_error());

$updateGoTo = "winners_update.php";
if (isset($_SERVER['QUERY_STRING'])) {
	$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
	$updateGoTo .= $_SERVER['QUERY_STRING'];
}
echo '<script> window.location = "' . $updateGoTo . '"; </script>';
exit();
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