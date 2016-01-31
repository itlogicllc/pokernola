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
	
	if (isset($_SESSION['player_logged_in'])) {
		$player_id = $_SESSION['player_logged_in'];
	} else {
		$player_id = false;
	}
	
	$game_player = game_players_player_by_game($game_id, $player_id);
	
	$game_array = games_by_id($game_id);
	
	$game_alternates_array = game_players_alternates_by_game($game_id);
	if ($game_alternates_array) {
		$num_alternates = count($game_alternates_array);
	} else {
		$num_alternates = 0;
	}
	
	$game_players_array = game_players_by_game($game_id);
	if ($game_players_array) {
		$num_players = count($game_players_array);
	} else {
		$num_players = 0;
	}
	
	$max_players = $settings_array['max_players'];
	
	// If the form is submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$action = $_POST['action'];
		
		// Add a record to game_players and update the num_players count
		// in the games table if the register button is selected. Otherwise,
		// delete record from game_players and update the num_players count in the games table.
		switch ($action) {
			case "register":
				set_game_players_add($game_id, $player_id);
				break;
			case "unregister":
				if ($game_player['alternate_order'] == 0) {
					$is_alternate = 0;
				} else {
					$is_alternate = 1;
				}
				set_game_players_delete($game_id, $game_player['game_players_id'], $is_alternate);
				
				if ($game_player['alternate_order'] == 0 && $num_alternates > 0) {
					set_game_players_alternate_to_player($game_id, $game_alternates_array[0]['game_players_id'], $game_alternates_array[0]['player_id']);
				}
				break;
			default:
				set_game_players_add($game_id, $player_id, $game_alternates_array[$num_alternates - 1]['alternate_order'] + 1);
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
				<?php if ($player_id) { ?>
					<?php if ($game_player) { ?>
					<form action="<?php echo $form_action; ?>" id="unregister" name="unregister" method="POST">
						<input type="submit" value="<?php echo ($game_player['alternate_order'] == 0) ? 'Unregister' : 'Unregister Alternate'; ?>" data-iconpos="top" data-icon="delete">
						<input type="hidden" name="action" value="unregister">
					</form>
					<?php } else { ?>
						<?php if ($num_players == $max_players) { ?>
						<form action="<?php echo $form_action; ?>" id="register_alternate" name="register_alternate" method="POST">
							<input type="submit" value="Register Alternate" data-iconpos="top" data-icon="check">
							<input type="hidden" name="action" value="register_alternate">
						</form>
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
				<div data-role="tabs">
					<div data-role="navbar" data-iconpos="bottom">
						<ul>
							<li><a href="#registered" data-ajax="false" class="ui-btn-active ui-state-persist" data-icon="registered">Registered</a></li>
							<li><a href="#alternates" data-ajax="false" data-icon="alternate">Alternates</a></li>
						</ul>
					</div>
					<div id='registered'>
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
					</div>
					<div id='alternates'>
						<ol data-role="listview" data-inset="true">
							<li data-role="list-divider">Alternate Players</li>
							<?php for ($i = 0; $i <= $num_alternates - 1; $i++) { ?>
							<li>
								<a href="player_details.php?player_id=<?php echo $game_alternates_array[$i]['player_id']; ?>"> 
									<h2><?php echo $game_alternates_array[$i]['full_name']; ?></h2>
								</a>
							</li>
							<?php } ?>
						</ol>
					</div>
				</div>
			</div>
			<div data-role="footer" data-position="fixed">
			<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>
