<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php
$game_array = games_game();
$settings_id = $games_array['settings_id'];
$settings_array = settings_current($settings_id);
?>
<?php 
$threshold_amount = intval($game_array['num_players'] / $settings_array['threshold']);
$multiply_amount = $threshold_amount * $settings_array['multiplier'];

if ($multiply_amount == 0) $multiply_amount = 1;

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

$i = 0;	
do {
	switch ($i) {
 		case 0:
			$insertSQL = sprintf("INSERT INTO winners (game_id, points, amount, split_diff, place) VALUES (%s, %s, %s, %s, %s)",
				GetSQLValueString($game_array['game_id'], "int"),
				GetSQLValueString($points[$i], "double"),
				GetSQLValueString($settings_array['first_pay'], "double"),
				GetSQLValueString($settings_array['first_pay'], "double"),
				GetSQLValueString($i + 1, "int"));
	
			mysql_select_db($database_poker_db, $poker_db);
			$Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());
 			break;
			
		case 1:
			$insertSQL = sprintf("INSERT INTO winners (game_id, points, amount, split_diff, place) VALUES (%s, %s, %s, %s, %s)",
				GetSQLValueString($game_array['game_id'], "int"),
				GetSQLValueString($points[$i], "double"),
				GetSQLValueString($settings_array['second_pay'], "double"),
				GetSQLValueString($settings_array['second_pay'], "double"),
				GetSQLValueString($i + 1, "int"));
	
			mysql_select_db($database_poker_db, $poker_db);
			$Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());
 			break;
			break;
			
		case 2:
			$insertSQL = sprintf("INSERT INTO winners (game_id, points, amount, split_diff, place) VALUES (%s, %s, %s, %s, %s)",
				GetSQLValueString($game_array['game_id'], "int"),
				GetSQLValueString($points[$i], "double"),
				GetSQLValueString($settings_array['third_pay'], "double"),
				GetSQLValueString($settings_array['third_pay'], "double"),
				GetSQLValueString($i + 1, "int"));
	
			mysql_select_db($database_poker_db, $poker_db);
			$Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());
 			break;
			
		default:
			$insertSQL = sprintf("INSERT INTO winners (points, game_id, place) VALUES (%s, %s, %s)",
				GetSQLValueString($points[$i], "double"),
				GetSQLValueString($game_array['game_id'], "int"),
				GetSQLValueString($i + 1, "int"));
	
			mysql_select_db($database_poker_db, $poker_db);
			$Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());
 			break;
 	}
	$i++;
}while ($i < 10);

// SETUP KO RECORDS
$insertSQL = sprintf("INSERT INTO winners (game_id) VALUES (%s)",
	GetSQLValueString($game_array['game_id'], "int"));

mysql_select_db($database_poker_db, $poker_db);
$Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());

header ("Location: games.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
</head>

<body>
</body>
</html>
