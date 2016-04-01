<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_winners.php');
	require('includes/get_game_players.php');
	require('includes/set_credits.php');
	
	$page_access_type = 'admin';
	set_page_access($page_access_type);
	
	$winners_list = winners_by_game($game_id);
	//$ko_list = winners_ko_by_game($game_id);
	$players_list = players_all();

	$game_players_alternates_array = game_players_alternates_by_game($game_id);
	$game_players_alternates_count = count($game_players_alternates_array);

	$game_players_array = game_players_by_game($game_id);
	if ($game_players_array) {
		$game_players_count = count($game_players_array);

		// resort the game players array by full name
		foreach ($game_players_array as $key => $row) {
			$full_name[$key]  = $row['full_name'];
		}

		array_multisort($full_name, SORT_ASC, $game_players_array);
	} else {
		$game_players_count = 0;
	}

	$settings_array[0] = settings_current($game['settings_id']);
	$max_players = $settings_array[0]['max_players'];

	$game_name = date_to_php($game['game_name']);
	$game_name_more = $game['game_name_more'];
	$game_time = time_to_php($game['game_time']);
	
	// Check if game has started
	$game_start_time = $game['game_start_time'];
	$game_seconds_to_start = get_seconds_between($game_start_time, 0);
	
	// If form is submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Update the games table.
		if (isset($_POST['status'])) {
			$status = 1;
		} else {
			$status = 0;
		}
		
		// Update the game table
		$query = "UPDATE games
					 SET status='$status'
					 WHERE game_id='$game_id'";

		$db_action = mysqli_query($db_connect, $query);
		
		set_credits($game_id, $status);
		
		header("Location: game_update.php?game_id=$game_id");
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
				<div class="ui-bar ui-bar-a ui-corner-all normal">
					<h2><?php echo $game_name; ?><span class="game_name"><?php echo (!empty($game_name_more)) ? '  [' . $game_name_more . ']' : ''; ?></span></h2>
				</div>
				<?php if ($game['status'] == 1 && $game_seconds_to_start > 0) { ?>
					<div class="comment ui-bar ui-corner-all"><?php echo 'Game starts at ' . time_to_php($game['game_time']); ?></div>
				<?php } elseif ($game['status'] == 1 && $game_seconds_to_start == 0) { ?>
					<div class="alert ui-bar ui-corner-all"><?php echo 'This game is in progress'; ?></div>
				<?php } else { ?>
					<div class="alert ui-bar ui-corner-all"><?php echo 'This game has ended'; ?></div>
				<?php }  ?>
				
				<form action="<?php echo 'game_update.php?game_id=' . $game_id ?>" method="POST" name="game_form" id="game_form">
					<div align="center">
						<input name="status" id="status" type="checkbox" data-role="flipswitch" <?php echo ($game['status'] == 1) ? 'checked=""' : ''; ?> onchange="setGameStatus(this.form);">
						<br>
						<div class="alert" id="game_status_note"></div>
					</div>
				</form>
					<div <?php echo ($game['status'] == 0) ? 'class="hidden"' : ''; ?>>
<!--				Start Game Details section.-->
					<div data-role="collapsible-set">
						<div data-role="collapsible" data-collapsed="true">
							<h3>Game Details</h3>
							<form action="<?php echo 'game_update_details.php?game_id=' . $game_id ?>" method="POST" name="details_form" id="details_form">
								<div  class="ui-field-contain">
									<label for="registration">Registration:</label>
									<input name="registration" id="registration" type="checkbox" data-role="flipswitch" <?php echo ($game['registration'] == 1) ? 'checked=""' : ''; ?>>
								</div>
								<div  class="ui-field-contain">
									<label for="game_name_more">Name:</label>
									<input type="text" name="game_name_more" id="game_name_more" value="<?php echo $game['game_name_more']; ?>"  />
								</div>
								<div  class="ui-field-contain">
									<label for="game_name">Date:</label>
									<input id="game_name" name="game_name" type="text"  value="<?php echo date_to_php($game['game_name']); ?>" data-role="datebox" data-options='{"mode":"calbox", "useFocus":true, "overrideDateFormat":"%m-%d-%Y", "defaultValue":"<?php echo $game['game_name']; ?>"}'>
								</div>
								<div data-role="fieldcontain">
									<label for="game_time">Time:</label>
									<input id="game_time" name="game_time" type="text" value="<?php echo time_to_php($game['game_time']); ?>" data-role="datebox" data-options='{"mode":"timeflipbox", "useFocus":true, "overrideTimeFormat":12, "overrideTimeOutput":"%I:%M:%S %p", "defaultValue":"<?php echo $game['game_time'] ?>"}' required />   
								</div>
								<div  class="ui-field-contain">
									<label for="total_players">Total Players:</label>
									<input type="number" min="<?php echo $game_players_count; ?>" step="1" name="total_players" id="total_players" value="<?php echo $game['num_players']; ?>"  />
								</div>
								<div  class="ui-field-contain">
									<label for="total_pot">Total Pot:</label>
									<input type="number" min="0.00" step="0.01" name="total_pot" id="total_pot" value="<?php echo $game['total_pot']; ?>"  />
								</div>
								<div data-type="horizontal" data-role="controlgroup">
									<input type="hidden" name="max_players" id="max_players" value="<?php echo $max_players ?>" />
									<input type="submit" name="submit" value="Update Details" data-inline="true" onclick="return getPlayersOverVerify(this.form);" />
								</div>
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
								<input type="hidden" name="total_players" id="total_players" value="<?php echo $game_players_count + 1 ?>" />
								<input type="hidden" name="max_players" id="max_players" value="<?php echo $max_players ?>" />
								<input type="submit" name="submit" value="Add Player" data-inline="true" onclick="return getPlayersOverVerify(this.form);" />
								<br />
								<?php if (!empty($game_players_array)) { ?>
								<ol data-role="listview" data-inset="true" data-count-theme="a" data-icon="delete" data-split-icon="action">
									<?php for ($i = 0; $i <= count($game_players_array) - 1; $i++) { ?>
									<li>
										<a href="game_update_players.php?game_id=<?php echo $game_id; ?>&player_id=<?php echo $game_players_array[$i]['player_id']; ?>">
											<img class="ui-li-icon ui-corner-none" alt="delete" src="images/icons/delete-white.png">
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
							<form method="POST" name="alternates_form" id="alternates_form" action="game_update_players.php?game_id=<?php echo $game_id; ?>&action=alternate_add">
								<?php for ($i = 0; $i <= count($players_list) - 1; $i++) { ?>
									<input type="hidden" name="alternates_list[]" value="<?php echo $players_list[$i]['player_id'] . ',' . $players_list[$i]['full_name']; ?>"  />
								<?php } ?>
								<select name="players_select" onfocus="setOptions(this, document.getElementsByName('alternates_list[]'), document.getElementsByName('game_players[]'));" >
									<option value="0">Guest</option>
								</select>
								<input type="hidden" name="total_players" id="total_players" value="<?php echo $game_players_count + 1 ?>" />
								<input type="hidden" name="max_players" id="max_players" value="<?php echo $max_players ?>" />
								<input type="submit" name="submit" value="Add Alternate" data-inline="true" />
								<br />
								<?php if (!empty($game_players_alternates_array)) { ?>
								<ol data-role="listview" data-inset="true" data-count-theme="a" data-split-icon="action">
									<?php for ($i = 0; $i <= count($game_players_alternates_array) - 1; $i++) { ?>
									<li>
										<a href="game_update_players.php?game_id=<?php echo $game_id; ?>&player_id=<?php echo $game_players_alternates_array[$i]['player_id']; ?>&alternate=1">
											<img class="ui-li-icon ui-corner-none" alt="delete" src="images/icons/delete-white.png">
											<h3><?php echo $game_players_alternates_array[$i]['full_name']; ?></h3>
											<input type="hidden" name="game_players[]" value="<?php echo $game_players_alternates_array[$i]['player_id'] . ',' . $game_players_alternates_array[$i]['full_name']; ?>;"  />
										</a>
										<a href="game_update_players.php?game_id=<?php echo $game_id; ?>&player_id=<?php echo $game_players_alternates_array[$i]['player_id']; ?>&action=alternate_move" onclick="return getPlayersOverVerify((getElementById('alternates_form')));"></a>
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
								<input type="submit" name="submit" value="Update Winners" data-inline="true" onclick="return setSplits(<?php echo $settings_array[0]['third_pay'] . ',' . $settings_array[0]['second_pay'] . ',' . $settings_array[0]['first_pay'] ?>);" />
							</form>
						</div>

	<!--					Start Knock Out section-->
	<!--					<div data-role="collapsible">
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
						</div>-->
					</div>
				</div>
			</div>
			<div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>

