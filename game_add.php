<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access(1);
	require('includes/get_games.php');
	
	// Get the days between the season starting date and ending date
	$total_days = date_diff(date_create($settings_array['start_date']), date_create($settings_array['end_date']));
	$total_days = $total_days->days;
	
	// Get how many days today is from the season starting date and see if todays day is inverted, meaning a negative vale from the starting date.
	// Also get the value of the ended_early date. If the value is anything other that 000-00-00 then the season was ended before the end_date.
	$todays_day_obj = date_diff(date_create($settings_array['start_date']), date_create(date("Y-m-d")));
	$todays_day = $todays_day_obj->days;
	$todays_day_inverted = $todays_day_obj->invert;
	$ended_early_date = $settings_array['ended_early'];
	
	// If todays day is greater than the total days of the season, or a negative number then today is not within
	// the range of the latest season and no games can be created.
	if ($todays_day > $total_days || $todays_day_inverted == 1 || $ended_early_date != '0000-00-00') {
		header("Location: access_denied.php?message=season_ended");
		exit();
	}
	
	// This is the amount of days left in the season from today
	$max_days = $total_days - $todays_day;

	// If form is submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$game_date = date('Y-m-d', time());
		$game_name = date_to_mysql($_POST['game_name']);
		$game_name_more = $_POST['game_name_more'];
		$game_time = time_to_mysql($_POST['game_time']);
		$settings_id = $settings_array['settings_id'];

		// Insert new game into games table
		$query = "INSERT INTO games
					 (game_date, game_name, game_name_more, game_time, settings_id)
					 VALUES ('$game_date', '$game_name', '$game_name_more', '$game_time', '$settings_id')";

		$db_action = mysqli_query($db_connect, $query);

		// Refresh games recordset to include newly inserted game and reference
		// the newly inserted record.
		games_refresh();
		$games_array = games_all();
		$game_array = $games_array[0];
		
		// Create the variables needed to insert new records into the winners table, one
		// new record for each winning place.
		$game_id = $game_array['game_id'];
		$first_pay = $settings_array['first_pay'];
		$second_pay = $settings_array['second_pay'];
		$third_pay = $settings_array['third_pay'];

		// Create an array of points per place.
		$points_array = array(
			$settings_array['pt1'],
			$settings_array['pt2'],
			$settings_array['pt3'],
			$settings_array['pt4'],
			$settings_array['pt5'],
			$settings_array['pt6'],
			$settings_array['pt7'],
			$settings_array['pt8'],
			$settings_array['pt9'],
			$settings_array['pt10']
		);
		
		// Loop through each place inserting a new record into the winners table
		// fore each place with the proper settings for each place.
		$i = 0;
		do {
			$place = $i + 1;
			$points = $points_array[$i];
			
			switch ($i) {
				case 0:
					$query = "INSERT INTO winners
								 (game_id, points, amount, split_diff, place)
								 VALUES ('$game_id', '$points', '$first_pay', '$first_pay', '$place')";

					$db_action = mysqli_query($db_connect, $query);
					break;

				case 1:
					$query = "INSERT INTO winners
								 (game_id, points, amount, split_diff, place)
								 VALUES ('$game_id', '$points', '$second_pay', '$second_pay', '$place')";

					$db_action = mysqli_query($db_connect, $query);
					break;

				case 2:
					$query = "INSERT INTO winners
								 (game_id, points, amount, split_diff, place)
								 VALUES ('$game_id', '$points', '$third_pay', '$third_pay', '$place')";

					$db_action = mysqli_query($db_connect, $query);
					break;

				default:
					$query = "INSERT INTO winners
								 (game_id, points, place) 
								 VALUES ('$game_id', '$points', '$place')";

					$db_action = mysqli_query($db_connect, $query);
					break;
			}
			$i++;
		} while ($i < 10);
		
		// Redirect to games.php after all records are created
		header("Location: games.php");
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
		<title>PokerNOLA Add Game</title>
	</head>
	<body>
		<div data-role="page" id="add_game">
			<div data-role="header" data-position="fixed">
				<h1>Add Game</h1>
				<?php require('includes/set_games.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<form action="<?php echo $form_action; ?>" id="add_game_form" name="add_game_form" method="POST">
					<div>
						<label for="game_name_more">Game Name:</label>
						<input id='game_name_more' name='game_name_more' type ='text'>
						<label for="game_name">Game Date:</label>
						<input id="game_name" name="game_name" type="text" data-role="datebox" data-options='{"mode":"calbox", "useFocus":true ,"minDays":<?php echo $todays_day; ?>, "maxDays":<?php echo $max_days; ?>, "showInitialValue":true}'>
						<label for="game_time">Game Time:</label> 
						<input id="game_time" name="game_time" type="text" value="<?php echo time_to_php($settings_array['default_game_time']); ?>" data-role="datebox" data-options='{"mode":"timeflipbox", "useFocus":true, "overrideTimeFormat":12, "overrideTimeOutput":"%I:%M:%S %p", "defaultValue":"<?php echo $settings_array['default_game_time'] ?>"}' required />
					</div>
					<br />
					<div data-role="controlgroup" data-type="horizontal">
						<input name="submit" type="submit" value="Create Game" />
					</div>
				</form>
			</div>
			<div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>
