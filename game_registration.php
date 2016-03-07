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
	$game_name = date_to_php($game_array['game_name']);
	$game_name_more = $game_array['game_name_more'];
	
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
	
	$credits_per_degree = $settings_array['credits_per_degree'];
	
	// if the priority system is turned on, rearrange the game players and alternates array with the
	// needed information based on the players amount of credits.
	if ($credits_per_degree > 0) {
		if ($game_players_array) {
			// get logged in players priority
			$this_player_priority = players_priority($player_id, $credits_per_degree);
			
			// add the level and degree of each player in the game to the array
			for ($i = 0; $i <= $num_players - 1; $i++) {
				$players_level = players_priority($game_players_array[$i]['player_id'], $credits_per_degree);

				$game_players_array[$i]['level'] = $players_level['level'];
				$game_players_array[$i]['degree'] = $players_level['degree'];
			}

			// resort the game players array by level then degree
			foreach ($game_players_array as $key => $row) {
				$level[$key]  = $row['level'];
				$degree[$key] = $row['degree'];
				$register_time[$key] = $row['register_time'];
			}

			array_multisort($level, SORT_DESC, $degree, SORT_DESC, $register_time, SORT_ASC, $game_players_array);
			
			// Get last player
			$last_player = $game_players_array[count($game_players_array) - 1];
			$last_player_id = $last_player['player_id'];
			$last_player_priority = players_priority($last_player_id, $credits_per_degree);
		}
		
		if ($game_alternates_array) {
			// add the level and degree of each alternate in the game to the array
			for ($i = 0; $i <= $num_alternates - 1; $i++) {
				$players_level = players_priority($game_alternates_array[$i]['player_id'], $credits_per_degree);

				$game_alternates_array[$i]['level'] = $players_level['level'];
				$game_alternates_array[$i]['degree'] = $players_level['degree'];
			}

			// resort the game alternates array by level then degree then alternate order
			foreach ($game_alternates_array as $key => $row) {
				$alternate_level[$key]  = $row['level'];
				$alternate_degree[$key] = $row['degree'];
				$alternate_register_time[$key] = $row['register_time'];
				$alternate_order[$key] = $row['alternate_order'];
			}

			array_multisort($alternate_level, SORT_DESC, $alternate_degree, SORT_DESC, $alternate_register_time, SORT_ASC, $alternate_order, SORT_ASC, $game_alternates_array);
			
			// Get the first alternate
			$first_alternate = $game_alternates_array[0];
			$first_alternate_priority = players_priority($first_alternate['player_id'], $credits_per_degree);
		}
	}
	
	// If the form is submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$action = $_POST['action'];
		
		// Add a record to game_players and update the num_players count
		// in the games table if the register button is selected. Otherwise,
		// delete record from game_players and update the num_players count in the games table.
		switch ($action) {
			case "register":
				set_game_players_add($game_id, $player_id, 0, 1);
				break;
			
			case "unregister":
				if ($game_player['alternate_order'] == 0) {
					$is_alternate = 0;
				} else {
					$is_alternate = 1;
				}
				set_game_players_delete($game_id, $player_id, $is_alternate, 1);
				
				if ($game_player['alternate_order'] == 0 && $num_alternates > 0) {
					set_game_players_alternate_to_player($game_id, $game_alternates_array[0]['game_players_id'], $game_alternates_array[0]['player_id']);
				}
				break;
			
				case "register_priority":
					set_game_players_move($game_id, $last_player_id, $num_alternates + 1, 0, 1);
					set_game_players_add($game_id, $player_id, 0, 1);
					break;
			
			default:
				set_game_players_add($game_id, $player_id, $game_alternates_array[$num_alternates - 1]['alternate_order'] + 1, 1);
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
				<div class="ui-bar ui-bar-a ui-corner-all normal">
					<h2><?php echo $game_name; ?><span class="game_name"><?php echo (!empty($game_name_more)) ? '  [' . $game_name_more . ']' : ''; ?></span></h2>
				</div>
				<div class="comment ui-bar ui-bar-b ui-corner-all"><?php echo 'Game starts at ' . time_to_php($game_array['game_time']); ?></div>
				<?php if ($player_id) { ?>
					<?php if ($game_player) { ?>
					<form action="<?php echo $form_action; ?>" id="unregister" name="unregister" method="POST">
						<button type="button" data-iconpos="top" data-icon="delete" onclick="setRequestButton(this, this.form);"><?php echo ($game_player['alternate_order'] == 0) ? 'Unregister' : 'Unregister Alternate'; ?></button>
						<input type="hidden" name="action" value="unregister">
					</form>
					<?php } else { ?>
						<?php if ($num_players == $max_players) { ?>
							<?php if ($credits_per_degree > 0 && $this_player_priority['level'] >= $last_player_priority['level'] && $this_player_priority['degree'] > $last_player_priority['degree']) { ?>
							<form action="<?php echo $form_action; ?>" id="register_priority" name="register_priority" method="POST">
								<button type="button" data-iconpos="top" data-icon="check" onclick="setRequestButton(this, this.form);">Register</button>
								<input type="hidden" name="action" value="register_priority">
							</form>
							<?php } else { ?>
							<form action="<?php echo $form_action; ?>" id="register_alternate" name="register_alternate" method="POST">
								<button type="button" data-iconpos="top" data-icon="check" onclick="setRequestButton(this, this.form);">Register Alternate</button>
								<input type="hidden" name="action" value="register_alternate">
							</form>
							<?php } ?>
						<?php } else { ?>
						<form action="<?php echo $form_action; ?>" id="register" name="register" method="POST">
							<button type="button" data-iconpos="top" data-icon="check" onclick="setRequestButton(this, this.form);">Register</button>
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
									<h2>
										<?php echo $game_players_array[$i]['full_name']; ?>
										<?php if ($credits_per_degree > 0 && $game_players_array[$i]['player_id'] > 0) { ?>
										<span class="<?php echo 'level_' . $game_players_array[$i]['level']; ?>"><?php echo $game_players_array[$i]['degree']; ?></span>
										<?php } ?>
									</h2>
									<p class="stat_note"> (<?php echo timestamp_to_php($game_players_array[$i]['register_time']); ?>)</p>
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
									<h2>
										<?php echo $game_alternates_array[$i]['full_name']; ?>
										<?php if ($credits_per_degree > 0 && $game_alternates_array[$i]['player_id'] > 0) { ?>
										<span class="<?php echo 'level_' . $game_alternates_array[$i]['level']; ?>"><?php echo $game_alternates_array[$i]['degree']; ?></span>
										<?php } ?>
									</h2>
									<p class="stat_note"> (<?php echo timestamp_to_php($game_alternates_array[$i]['register_time']); ?>)</p>
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
