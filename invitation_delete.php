<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php
  $insertSQL = sprintf("DELETE FROM invitations WHERE invitation_id=%s",
  GetSQLValueString($_GET['invitation_id'], "int"));

  mysql_select_db($database_poker_db, $poker_db);
  $Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());

$updateGoTo = "player_profile.php";
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