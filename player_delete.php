<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access(1);
	require('includes/get_players.php');
	
	// Get list of all players
	$players_list = players_all();

	// If form is submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Get the select players details
		$player = players_by_id($_POST['player_id']);

		// Delete the select players invitation from the database
		$query1 = "DELETE FROM invitations
					 WHERE invitation_id='" . $player['invitation_id'] . "'";

		$db_action1 = mysqli_query($db_connect, $query1);

		// Delete the selected player from the database
		$query2 = "DELETE FROM players
					  WHERE player_id='" . $player['player_id'] . "'";

		$db_action2 = mysqli_query($db_connect, $query2);

		// Redirect to the players page
		header("Location: players.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
   <head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Delete PLayer</title>
   </head>
   <body>
      <div data-role="page" id="delete_player">
         <div data-role="header" data-position="fixed">
            <h1>Delete Player</h1>
				<?php require('includes/set_players.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <form action="player_delete.php" id="delete_player_form" name="delete_player_form" method="POST">
               <label for="player_id">Select Player to Delete:</label>
               <select name="player_id" id="player_id" data-native-menu="true">
					<?php for ($i = 0; $i <= count($players_list) - 1; $i++) { ?>
						<option value="<?php echo $players_list[$i]['player_id'] ?>">
						<?php echo $players_list[$i]['full_name'] ?>
						</option>
					<?php } ?>
               </select>
               <br />
               <input type="submit" value="Delete Player" data-inline="true" onclick="return getPlayerDeleteVerify();" <?php echo (count($playerss_list) == 0) ? 'disabled' : '' ?> />		
            </form>
         </div>
         <div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
