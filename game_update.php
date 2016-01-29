<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access(1);
	require('includes/get_games.php');
	require('includes/get_winners.php');
	require('includes/get_players.php');
	require('includes/get_game_players.php');
	
	// Make sure the query string has a player_id, if not redirect to access denied.
	if (!empty($_GET['game_id'])) {
		$game_id = trim($_GET['game_id']);
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}

	// Get the game details based on the game id.
	$game = games_by_id($game_id);
	
	// If the game details were found.
	if ($game) {
		$winners_list = winners_by_game($game_id);
		$ko_list = winners_ko_by_game($game_id);
		$players_list = players_all();
		
		$game_players_alternates_array = game_players_alternates_by_game($game_id);
		$game_players_alternates_count = count($game_players_alternates_array);
		
		$game_players_array = game_players_by_game($game_id);
		$game_players_count = count($game_players_array);
		
		$settings_array[0] = settings_current($game['settings_id']);
		$max_players = $settings_array[0]['max_players'];
		
		$game_name = date_to_php($game['game_name']);
	// If the game details were not found go to access denied.
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Game Update</title>
	</head>
	<body onload="setChipcountEnabled(<?php echo ($settings_array[0]['split_type'] == 'even' ? '1' : ''); ?>);">
		<div data-role="page" id="winner_update" data-dom-cache="false">
			<div data-role="header" data-position="fixed">
				<h1>Game Update</h1>
				<?php require('includes/set_game_update.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<div class="ui-bar ui-bar-a ui-corner-all normal"><h2><?php echo $game_name; ?></h2></div>

<!--				Start Game Details section.-->
				<div data-role="collapsible-set">
					<div data-role="collapsible" data-collapsed="true">
						<h3>Game Details</h3>
						<form action="<?php echo 'game_update_details.php?game_id=' . $game_id ?>" method="POST" name="details_form" id="details_form">
							<div  class="ui-field-contain">
								<label for="status">Active Game:</label>
								<input name="status" id="status" type="checkbox" data-role="flipswitch" <?php echo ($game['status'] == 1) ? 'checked=""' : ''; ?>>
							</div>
							<div  class="ui-field-contain">
								<label for="registration">Closed Game:</label>
								<input name="registration" id="registration" type="checkbox" data-role="flipswitch" <?php echo ($game['registration'] == 1) ? 'checked=""' : ''; ?>>
							</div>
							<div  class="ui-field-contain">
								<label for="game_name">Game Date:</label>
								<input id="game_name" name="game_name" type="text" data-role="datebox" data-options='{"mode":"calbox", "useFocus":true, "defaultValue":"<?php echo $game['game_name']; ?>",  "showInitialValue":true}'>
							</div>
							<div  class="ui-field-contain">
								<label for="total_players">Total Players:</label>
								<input type="number" min="<?php echo $game_players_count; ?>" step="1" name="total_players" id="total_players" value="<?php echo $game['num_players']; ?>"  />
							</div>
							<div  class="ui-field-contain">
								<label for="total_pot">Total Pot:</label>
								<input type="number" min="0.00" step="0.01" name="total_pot" id="total_pot" value="<?php echo $game['total_pot']; ?>"  />
							</div>
							<input type="submit" name="submit" value="Update" data-inline="true" onclick="return getPlayersOverVerify(total_players.value, <?php echo $max_players ?>);"/>
						</form>
					</div>

<!--					Start Players section.-->
					<div data-role="collapsible" data-collapsed="true">
						<h3>Players</h3>
						<form method="POST" name="players_form" id="players_form" action="game_update_players.php?game_id=<?php echo $game_id; ?>&action=player_add">
							<?php for ($i = 0; $i <= count($players_list) - 1; $i++) { ?>
								<input type="hidden" name="players_list[]" value="<?php echo $players_list[$i]['player_id'] . ',' . $players_list[$i]['full_name']; ?>"  />
							<?php } ?>
							<select name="players_select" onfocus="setOptions(this, document.getElementsByName('players_list[]'), document.getElementsByName('game_players[]'));" >
								<option value="0">Guest</option>
							</select>
							<input type="submit" name="submit" value="Add" data-inline="true" onclick="return getPlayersOverVerify(<?php echo $game_players_count + 1 ?>, <?php echo $max_players ?>);" />
							<br />
							<?php if (!empty($game_players_array)) { ?>
							<ol data-role="listview" data-inset="true" data-count-theme="a" data-icon="delete" data-split-icon="action">
								<?php for ($i = 0; $i <= count($game_players_array) - 1; $i++) { ?>
								<li>
									<a href="game_update_players.php?game_id=<?php echo $game_id; ?>&player_id=<?php echo $game_players_array[$i]['player_id']; ?>&alternate=0"> 
										<h3><?php echo $game_players_array[$i]['full_name']; ?></h3>
										<input type="hidden" name="game_players[]" value="<?php echo $game_players_array[$i]['player_id'] . ',' . $game_players_array[$i]['full_name']; ?>"  />
									</a>
									<a href="game_update_players.php?game_id=<?php echo $game_id; ?>&player_id=<?php echo $game_players_array[$i]['player_id']; ?>&action=player_move"></a>
								</li>
								<?php } ?>
							</ol>
							<?php } ?>
						</form>
					</div>

<!--					Start Alternates section.-->
					<div data-role="collapsible" data-collapsed="true">
						<h3>Alternates</h3>
						<form method="POST" name="players_form" id="players_form" action="game_update_players.php?game_id=<?php echo $game_id; ?>&action=alternate_add">
							<?php for ($i = 0; $i <= count($players_list) - 1; $i++) { ?>
								<input type="hidden" name="players_list[]" value="<?php echo $players_list[$i]['player_id'] . ',' . $players_list[$i]['full_name']; ?>"  />
							<?php } ?>
							<select name="players_select" onfocus="setOptions(this, document.getElementsByName('players_list[]'), document.getElementsByName('game_players[]'));" >
								<option value="0">Guest</option>
							</select>
							<input type="submit" name="submit" value="Add" data-inline="true" />
							<br />
							<?php if (!empty($game_players_alternates_array)) { ?>
							<ol data-role="listview" data-inset="true" data-count-theme="a" data-split-icon="action">
								<?php for ($i = 0; $i <= count($game_players_alternates_array) - 1; $i++) { ?>
								<li>
									<a href="game_update_players.php?game_id=<?php echo $game_id; ?>&player_id=<?php echo $game_players_alternates_array[$i]['player_id']; ?>&alternate=1">
										<h3><?php echo $game_players_alternates_array[$i]['full_name']; ?></h3>
										<input type="hidden" name="game_players[]" value="<?php echo $game_players_alternates_array[$i]['player_id'] . ',' . $game_players_alternates_array[$i]['full_name']; ?>"  />
									</a>
									<a href="game_update_players.php?game_id=<?php echo $game_id; ?>&player_id=<?php echo $game_players_alternates_array[$i]['player_id']; ?>&action=alternate_move" onclick="return getPlayersOverVerify(<?php echo $game_players_count + 1 ?>, <?php echo $max_players ?>);"></a>
								</li>
								<?php } ?>
							</ol>
							<?php } ?>
						</form>
					</div>

<!--					Start Winners section.-->
					<div data-role="collapsible" data-collapsed="true">
						<h3>Winners</h3>
						<form method="POST" name="winners_form" id="winners_form" action="game_update_winners.php?game_id=<?php echo $game_id; ?>">
							<!-- For each item in the winners_list array, create a select menu to record a winner for each place. Count each place in descending order -->
							<?php for ($i = count($winners_list) - 1; $i >= 0; $i--) { ?>
							<!-- The div that contains the heading of which winner this select menu represents -->
							<div class="ui-bar ui-bar-a ui-corner-all">
								<h3>Winner: <?php echo $i + 1 ?></h3>
							</div>
							<!-- The div that contains the select menu, checkbox for marking splitting players and textbox for the players chip count when splitting -->
							<div class="ui-body ui-body-a ui-corner-all">
								<!-- Create the select object and add an onfocus listener that sets the options by calling the setOptions function in the custom.js file -->
								<select name="winners_select[]" onfocus="setOptions(this, document.getElementsByName('game_players[]'), document.getElementsByName('winners_select[]'));">
									<option value="0">Guest</option>
									<option value="<?php echo $winners_list[$i]['player_id']; ?>" selected="selected"><?php echo $winners_list[$i]['full_name']; ?></option>
								</select>
								<div class="ui-grid-a">
									<!-- create the checkbox object and add a listener to it's onclick event that enables the chip count text box when checked -->
									<div class="ui-block-a split">
										<input name="splits[]" type="checkbox" <?php echo ($i > '0' ? 'style="visibility:hidden"' : ''); ?> onclick="setChipcountEnabled(<?php echo ($settings_array[0]['split_type'] == 'even' ? '1' : ''); ?>); setSplitEnabled(<?php echo (count($winners_list) - 1) - $i; ?>);">
									</div>
									<div class="ui-block-b chipcount">
										<input name="chipcounts[]" type="text" value="0">
										<input name="original_splits[]" type="hidden" value="0">
									</div>
								</div>
							</div>
							<br />
							<?php } ?>
							<input type="submit" name="submit" value="Update" data-inline="true" onclick="return setSplits(<?php echo $settings_array[0]['third_pay'] . ',' . $settings_array[0]['second_pay'] . ',' . $settings_array[0]['first_pay'] ?>);" />
						</form>
					</div>

<!--					Start Knock Out section-->
					<div data-role="collapsible">
						<h3>Knock Outs</h3>
						<form method="POST" name="knockouts_form" id="knockouts_form" action="game_update_kos.php?game_id=<?php echo $game_id; ?>">
							<?php for ($i = 0; $i <= count($ko_list) - 1; $i++) { ?>
							<input type="hidden" name="ko_winner_id<?php echo $i; ?>" id="ko_winner_id" value="<?php echo $ko_list[$i]['winner_id']; ?>">
							<input type="hidden" name="ko_counter" id="ko_counter" value="<?php echo $i + 1; ?>">
							<div data-type="vertical" data-role="controlgroup">
								<select name="<?php echo 'koa' . $i ?>" id="<?php echo 'koa' . $i ?>" onFocus="set_player_select(this);" onChange="set_player_selected(this, getElementById('player<?php echo $i + 1; ?>'));" data-native-menu="true">
									<option value="0">Guest</option>
									<?php require('includes/get_koer_select.php'); ?>
								</select>
								<select name="<?php echo 'kob' . $i ?>" id="<?php echo 'kob' . $i ?>" onFocus="set_player_select(this);" onChange="set_player_selected(this, getElementById('player<?php echo $i + 1; ?>'));" data-native-menu="true">
									<option value="0">Guest</option>
									<?php require('includes/get_ko_select.php'); ?>
								</select>                     
							</div>
							<?php } ?>
							<div data-type="horizontal" data-role="controlgroup">
								<input type="submit" name="update" value="Update" data-inline="true" />	
								<input type="submit" name="add" value="Add" data-inline="true" />	
								<input type="submit" name="delete" value="Delete" data-inline="true" />	
							</div>
						</form>
					</div>
				</div>
			</div>
			<div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>

