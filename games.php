<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	//get_access();
	require('includes/get_games.php');
	require('includes/get_game_players.php');

	$games_list = games_all();
	//$current_settings = settings_current();
?>
<!DOCTYPE html>
<html>
   <head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Games</title>
   </head>
   <body>
      <div data-role="page" id="players">
			<?php require('includes/set_panel_date.php'); ?>
         <div data-role="header" data-position="fixed">
            <h1>Games</h1>
				<?php require('includes/set_games.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <ul data-role="listview" data-inset="true" data-split-icon="edit">
               <li data-role="list-divider">Game Schedule</li>
					<?php
						for ($i = 0; $i <= count($games_list) - 1; $i++) {
							if ($games_list[$i]['status'] == 0) {
								$game_played = true;
							} else {
								$game_played = false;
							}
					?>
					<li>
						<a href="<?php echo ($games_list[$i]['registration'] == 1 && $games_list[$i]['status'] == 1) ? 'game_registration.php' : 'game_details.php'; ?>?game_id=<?php echo $games_list[$i]['game_id']; ?>">
						<?php
							// If the game has already been played.
							if ($game_played) {
								// If a player is logged in.
								if (!empty($_SESSION['player_logged_in'])) {
									// If they played in the game show the played icon, if not show the notplayed icon.
									if (game_players_get_id($games_list[$i]['game_id'], $_SESSION['player_logged_in'])) {
										echo '<img class="ui-li-icon" alt="open" src="images/played.png" >';
									} else {
										echo '<img class="ui-li-icon" alt="open" src="images/notplayed.png" >';
									}
								// If the user is not logged in show the notplayed icon.
								} else {
									echo '<img class="ui-li-icon" alt="open" src="images/notplayed.png" >';
								}
								
								// display the game name as expired
								echo '<span class="expired">' . date_to_php($games_list[$i]['game_name']) . '</span>';
							// If the game is scheduled to be played, but not yet played.
							} else {
								// If the user is logged in.
								if (!empty($_SESSION['player_logged_in'])) {
									// If the game is a private game.
									if ($games_list[$i]['registration'] == 1) {
										// If the logged in player is registered to play the private game show the rigistered icon.
										// If not, show the private game icon
										if (game_players_get_id($games_list[$i]['game_id'], $_SESSION['player_logged_in'])) {
											echo '<img class="ui-li-icon" alt="open" src="images/registered.png" >';
										} else {
											echo '<img class="ui-li-icon" alt="open" src="images/private.png" >';
										}
									// If the game is not a private game, show the open game icon
									} else {
										echo '<img class="ui-li-icon" alt="open" src="images/open.png" >';
									}
								// If the user is not logged in.
								} else {
									// If it is a private game show the private game icon, otherwise show the open game icon.
									if ($games_list[$i]['registration'] == 1) {
										echo '<img class="ui-li-icon" alt="open" src="images/private.png" >';
									} else {
										echo '<img class="ui-li-icon" alt="open" src="images/open.png" >';
									}
								}
								
								// display the game name as not played yet
								echo '<span>' . date_to_php($games_list[$i]['game_name']) . '</span>';
							}
						?>
						</a>
						<?php if (isset($_SESSION['player_access']) && $_SESSION['player_access'] == 'admin') { ?>
						<a href="game_update.php?game_id=<?php echo $games_list[$i]['game_id']; ?>" title="Game Update"></a>
						<?php } ?>
					</li>
					<?php } ?>
            </ul>
         </div>
         <div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>