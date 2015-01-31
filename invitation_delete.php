<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_invitation.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/set_emails.php'); ?>
<?php
$invitation = invitations_by_id($_GET['invitation_id']);
$player = players_player($invitation['player_id']);

$insertSQL = sprintf("DELETE FROM invitations WHERE invitation_id=%s",
GetSQLValueString($_GET['invitation_id'], "int"));

mysql_select_db($database_poker_db, $poker_db);
$Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());
  
$system_arg_array = array($player['full_name'], $invitation['full_name'], "", "", "", "");
system_emails($system_arg_array, "invitation_deleted"); 

$updateGoTo = "player_profile.php";
if (isset($_SERVER['QUERY_STRING'])) {
	$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
	$updateGoTo .= $_SERVER['QUERY_STRING'];
}
header(sprintf("Location: %s", $updateGoTo));
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