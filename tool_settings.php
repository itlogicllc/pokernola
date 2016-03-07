<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access(1);
	
	$current_settings = settings_current();

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$season_name = $_POST['season_name'];
		$start_date = date_to_mysql($_POST['start_date']);
		$end_date = date_to_mysql($_POST['end_date']);
		$default_game_time = time_to_mysql($_POST['default_game_time']);
		$max_players = $_POST['max_players'];
		$first_pay = $_POST['first_pay'];
		$second_pay = $_POST['second_pay'];
		$third_pay = $_POST['third_pay'];
		$pt1 = $_POST['pt1'];
		$pt2 = $_POST['pt2'];
		$pt3 = $_POST['pt3'];
		$pt4 = $_POST['pt4'];
		$pt5 = $_POST['pt5'];
		$pt6 = $_POST['pt6'];
		$pt7 = $_POST['pt7'];
		$pt8 = $_POST['pt8'];
		$pt9 = $_POST['pt9'];
		$pt10 = $_POST['pt10'];
		$threshold = $_POST['threshold'];
		$multiplyer = $_POST['multiplier'];
		$max_increase = $_POST['max_increase'];
		$ptplay = $_POST['ptplay'];
		$split_type = $_POST['split_type'];
		
		if (isset($_POST['split_points'])) {
			$split_points = 1;
		} else {
			$split_points = 0;
		}
		
		$ko = $_POST['ko'];
		$bounty = $_POST['bounty'];

		$query = "INSERT INTO settings
				 (season_name, start_date, end_date, default_game_time, max_players, first_pay, second_pay, third_pay, pt1, pt2, pt3, pt4, pt5, pt6, pt7, pt8, pt9, pt10, threshold, multiplier, max_increase, ptplay, split_type, split_points, ko, bounty)
				 VALUES
				 ('$season_name', '$start_date', '$end_date', '$default_game_time', '$max_players', '$first_pay', '$second_pay', '$third_pay', '$pt1', '$pt2', '$pt3', '$pt4', '$pt5', '$pt6', '$pt7', '$pt8', '$pt9', '$pt10', '$threshold', '$multiplyer', $max_increase, '$ptplay', '$split_type', '$split_points', '$ko', '$bounty')";

		$db_action = mysqli_query($db_connect, $query);
		
		header("Location: tools.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Season Settings</title>
	</head>
	<body>
		<div data-role="page" id="setings">
			<div data-role="header" data-position="fixed">
				<h1>Create New Season</h1>
				<?php require('includes/set_tools.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<form action="tool_settings.php" id="settings_form" name="settings_form" method="POST">
					<div class="ui-bar ui-bar-a ui-corner-all info"><h2>ALL fields are required!</h2></div>
					<div data-role="fieldcontain">
						<label for="season_name">Season Name:</label>
						<input id="season_name" name="season_name" type="text" value="" required />
					</div>
					<div data-role="fieldcontain">
						<label for="start_date">Season Start Date:</label>
						<input id="start_date" name="start_date" type="text" data-role="datebox" data-options='{"mode":"flipbox", "useFocus":true, "showInitialValue":true, "calUsePickers":true, "calNoHeader":true, "overrideDateFormat":"%m-%d-%Y"}' required />
						<label for="end_date">Season End Date:</label>
						<input id="end_date" name="end_date" type="text" data-role="datebox" data-options='{"mode":"flipbox", "useFocus":true, "showInitialValue":true, "calUsePickers":true, "calNoHeader":true, "overrideDateFormat":"%m-%d-%Y"}' required />
					</div>
					<div data-role="fieldcontain">
						<label for="default_game_time">Default Game Start Time:</label>
						<input id="default_game_time" name="default_game_time" type="text" value="<?php echo time_to_php($current_settings['default_game_time']); ?>" data-role="datebox" data-options='{"mode":"timeflipbox", "useFocus":true, "overrideTimeFormat":12, "overrideTimeOutput":"%I:%M:%S %p", "defaultValue":"<?php echo $current_settings['default_game_time'] ?>"}' required />   
					</div>
					<div data-role="fieldcontain">
						<label for="max_players">Maximum Players:</label>
						<input name="max_players" id="max_players" type="text" value="<?php echo $current_settings['max_players']; ?>" required />    
					</div>
					<div data-role="fieldcontain">
						<label for="first_pay">First Payout:</label>
						<input name="first_pay" id="first_pay" type="text" value="<?php echo $current_settings['first_pay']; ?>"  required />
						<label for="second_pay">Second Payout:</label>
						<input name="second_pay" id="second_pay" type="text" value="<?php echo $current_settings['second_pay']; ?>" required />
						<label for="third_pay">Third Payout:</label>
						<input type="text" name="third_pay" id="third_pay" value="<?php echo $current_settings['third_pay']; ?>" required/>
					</div>
					<div data-role="fieldcontain">
						<div class="label_div">Points Per Place:</div>
						<div class="ui-grid-b">
							<div class="ui-block-a">
								<label for="pt1">1st:</label>
								<input type="text" name="pt1" id="pt1" value="<?php echo $current_settings['pt1']; ?>" required />
							</div>
							<div class="ui-block-b">
								<label for="pt2">2nd:</label>
								<input type="text" name="pt2" id="pt2" value="<?php echo $current_settings['pt2']; ?>" required />	
							</div>
							<div class="ui-block-c">
								<label for="pt3">3rd:</label>
								<input type="text" name="pt3" id="pt3" value="<?php echo $current_settings['pt3']; ?>" required />
							</div>
							<div class="ui-block-a">
								<label for="pt4">4th:</label>
								<input type="text" name="pt4" id="pt4" value="<?php echo $current_settings['pt4']; ?>" required />
							</div>
							<div class="ui-block-b">
								<label for="pt5">5th:</label>
								<input type="text" name="pt5" id="pt5" value="<?php echo $current_settings['pt5']; ?>" required />
							</div>
							<div class="ui-block-c">
								<label for="pt6">6th:</label>
								<input type="text" name="pt6" id="pt6" value="<?php echo $current_settings['pt6']; ?>" required />
							</div>
							<div class="ui-block-a">
								<label for="pt7">7th:</label>
								<input type="text" name="pt7" id="pt7" value="<?php echo $current_settings['pt7']; ?>" required />
							</div>
							<div class="ui-block-b">
								<label for="pt8">8th:</label>
								<input type="text" name="pt8" id="pt8" value="<?php echo $current_settings['pt8']; ?>" required />
							</div>
							<div class="ui-block-c">
								<label for="pt9">9th:</label>
								<input type="text" name="pt9" id="pt9" value="<?php echo $current_settings['pt9']; ?>" required />
							</div>
							<div class="ui-block-a">
								<label for="pt10">10th:</label>
								<input type="text" name="pt10" id="pt10" value="<?php echo $current_settings['pt10']; ?>" required />
							</div>
							<div class="ui-block-b">
							</div>
							<div class="ui-block-c">
							</div>
						</div>
					</div>
					<div data-role="fieldcontain">
						<label for="threshold">Player Threshold:</label>
						<input type="text" name="threshold" id="threshold" value="<?php echo $current_settings['threshold']; ?>" required />
						<label for="multiplier">Point Multiplier:</label>
						<input type="text" name="multiplier" id="multiplier" value="<?php echo $current_settings['multiplier']; ?>" required />
						<label for="max_increase">Times To Increase Points: <span class='input_note'>0 = unlimited</span></label>
						<input type="text" name="max_increase" id="max_increase" value="<?php echo $current_settings['max_increase']; ?>" required />
					</div>
					<div data-role="fieldcontain">
						<label for="ptplay">Points for Playing:</label>
						<input type="text" name="ptplay" id="ptplay" value="<?php echo $current_settings['ptplay']; ?>" required />
					</div>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal">
							<legend>Split Type:</legend>
							<input type="radio" name="split_type" id="evenly" value="even" <?php echo ($current_settings['split_type'] == 'even' ? 'checked' : ''); ?> />
							<label for="evenly">Evenly</label>
							<input type="radio" name="split_type" id="percentage" value="percent" <?php echo ($current_settings['split_type'] == 'percent' ? 'checked' : ''); ?> />
							<label for="percentage">Percent</label>
							<input type="checkbox" name="split_points" id="split_points" <?php echo ($current_settings['split_points'] == '1' ? 'checked' : ''); ?> />
							<label for="split_points">Split Points</label>
						</fieldset>
					</div>
					<div data-role="fieldcontain">
						<label for="ko">Knock Out Points:</label>
						<input type="text" name="ko" id="ko" value="<?php echo $current_settings['ko']; ?>" required />
						<label for="bounty">Bounty Points:</label>
						<input type="text" name="bounty" id="bounty" value="<?php echo $current_settings['bounty']; ?>" required />	
					</div>
					<div data-role="controlgroup" data-type="horizontal">
						<input type="hidden" name="settings_id" id="settings_id" value="<?php echo $current_settings['settings_id']; ?>" />
						<input name="Submit" type="submit" value="Create New Season" onclick="return getSeasonCreateVerify();" />
					</div>
				</form>
			</div>
			<div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>
