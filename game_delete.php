<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_credits.php');
	require('includes/set_emails.php');
	
	$page_access_type = 'admin';
	set_page_access($page_access_type);

	// Get a list of all games
	$games_list = games_all();

	// If form is submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$game_id = $_POST['game_id'];
		$game = games_by_id($game_id);
		
		if (isset($_POST['notify'])) {
			$notify_members = true;
		} else {
			$notify_members = false;
		}
		
		set_credits($game_id, 1);
		
		$query = "DELETE FROM games
					 WHERE game_id='$game_id'";

		$db_action = mysqli_query($db_connect, $query);
		
		// Send email notification to members if selected
		if ($notify_members) {
			// Set up email
			$bcc = players_all();

			foreach($bcc as $value) {
				$bcc_array[] = $value['email'];
			}

			$message = "<p>Sorry, but the " . date_to_php($game['game_name']) . " " . $game['game_name_more'] . " game has just been cancelled! To see what other games are available and upcoming at PokerNOLA, <a href='http://pokernola.com/games.php'>go to Games at pokernola.com</a>.</p><p>Good luck!</p>";

			// Send email distribution.
			player_emails("distribution", $bcc_array, array($message));
		}

		// Send system email
		system_emails("game_deleted", array(date_to_php($game['game_name'])));
			
		header("Location: games.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
   <head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Delete Game</title>
   </head>
   <body>
      <div data-role="page" id="delete_game">
         <div data-role="header" data-position="fixed">
            <h1>Delete Game</h1>
				<?php require('includes/set_games.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <form id="delete_game_form" name="delete_game_form" method="POST" action="">
               <label for="game_id">Select Game to Delete:</label>
               <select name="game_id" id="game_id" data-native-menu="true">
						<?php for ($i = 0; $i <= count($games_list) - 1; $i++) { ?>
							<option value="<?php echo $games_list[$i]['game_id'] ?>"><?php echo date_to_php($games_list[$i]['game_name']); ?></option>
						<?php } ?>
               </select>
               <br>
					<label for="notify"> Notify Members</label>
					<input name="notify" id="notify" type="checkbox">
					<br>
               <input type="submit" value="Delete Game" data-inline="true" onclick="return getGameDeleteVerify();" <?php echo (count($games_list) == 0) ? 'disabled' : '' ?> />		
            </form>
         </div>
         <div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
