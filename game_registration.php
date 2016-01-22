<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	//get_access();
	require('includes/get_games.php');
	require('includes/get_players.php');
	require('includes/get_game_players.php');
	require('includes/set_game_players.php');

	$game_id = $_GET['game_id'];
	$player_id = $_SESSION['player_logged_in'];
	$game_players_id = game_players_get_id($game_id, $player_id);
	
	$game_array = games_by_id($game_id);
	$game_players_array = game_players_by_game($game_id);
	$max_players = $settings_array['max_players'];
	
	if ($game_players_array) {
		$num_players = count($game_players_array);
	} else {
		$num_players = 0;
	}
	

	// If the form is submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$action = $_POST['action'];
		
		// Add a record to game_players and update the num_players count
		// in the games table if the register button is selected. Otherwise,
		// delete record from game_players and update the num_players count in the games table.
		if ($action == "register") {
			set_game_players_add($game_id, $player_id);
		} else {
			set_game_players_delete($game_id, $game_players_id);
		}
		
		// Refresh the page to show the updates
		header("Location: game_registration.php?game_id=$game_id");
	}
	
	$form_action = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$form_action .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Game Registration</title>
	</head>
	<body>
		<div data-role="page" id="game_registration">
			<div data-role="header" data-position="fixed">
				<h1>Game Registration</h1>
				<?php require('includes/set_game_details.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<div class="ui-bar ui-bar-a ui-corner-all normal"><h2><?php echo date_to_php($game_array['game_name']); ?></h2></div>
				<?php if (isset($_SESSION['player_logged_in'])) { ?>
					<?php if ($game_players_id) { ?>
					<form action="<?php echo $form_action; ?>" id="unregister" name="unregister" method="POST">
						<input type="submit" value="Unregister" data-iconpos="top" data-icon="delete">
						<input type="hidden" name="action" value="unregister">
					</form>
					<?php } else { ?>
						<?php if ($num_players == $max_players) { ?>
						<br />
						<div class="ui-body ui-body-a ui-corner-all alert">
							<p>The maximum number of players has been reached and no new players are allowed. Please check back later in the event a new spot opens.</p>
						</div>
						<?php } else { ?>
						<form action="<?php echo $form_action; ?>" id="register" name="register" method="POST">
							<input type="submit" value="Register" data-iconpos="top" data-icon="check">
							<input type="hidden" name="action" value="register">
						</form>
						<?php } ?>
					<?php } ?>
				<?php } else { ?>
				<form action="<?php echo $form_action; ?>" id="not_loggedin" name="not_loggedin" method="POST">
					<input type="submit" value="Please log in to register or unregister" data-iconpos="top" data-icon="alert" disabled>
				</form>
				<?php } ?>
				<?php if ($num_players > 0) { ?>
				<ol data-role="listview" data-inset="true">
					<li data-role="list-divider">Registered Players (<?php echo $num_players; ?> of <?php echo $max_players; ?>)</li>
					<?php for ($i = 0; $i <= $num_players - 1; $i++) { ?>
					<li>
						<a href="player_details.php?player_id=<?php echo $game_players_array[$i]['player_id']; ?>"> 
							<h2><?php echo $game_players_array[$i]['full_name']; ?></h2>
						</a>
					</li>
					<?php } ?>
				</ol>
				<?php } ?>
			</div>
			<div data-role="footer" data-position="fixed">
			<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>
