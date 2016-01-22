<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access(1);
	require('includes/get_games.php');

	// Get a list of all games
	$games_list = games_all();

	// If form is submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$game_id = $_POST['game_id'];
		
		$query = "DELETE FROM games
					 WHERE game_id='$game_id'";

		$db_action = mysqli_query($db_connect, $query);

		header("Location: games.php");
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
               <br />
               <input type="submit" value="Delete Game" data-inline="true" />		
            </form>
         </div>
         <div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
