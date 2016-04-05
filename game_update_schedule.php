<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_emails.php');
	
	$page_access_type = 'admin';
	set_page_access($page_access_type);
	
	if (isset($_POST['notify'])) {
		$notify_members = true;
	} else {
		$notify_members = false;
	}

	$game_name = date_to_mysql($_POST['game_name']);
	$game_name_more = $_POST['game_name_more'];
	$game_time = time_to_mysql($_POST['game_time']);
	
	// Update the game table
	$query = "UPDATE games
				 SET game_name='$game_name', game_name_more='$game_name_more', game_time='$game_time'
				 WHERE game_id='$game_id'";

	$db_action = mysqli_query($db_connect, $query);
	
	// Send email notification to members if selected
	if ($notify_members) {
		// Set up email
		$bcc = players_all();

		foreach($bcc as $value) {
			$bcc_array[] = $value['email'];
		}

		$message = "<p>Be aware, the <b>" . date_to_php($game['game_name']) . " " . $game['game_name_more'] . " game at " . time_to_php($game['game_time']) . "</b> has been rescheduled to <b>" . date_to_php($game_name) . " " . $game_name_more . " game at " . time_to_php($game_time) . "</b>!</p><p>If you were registered to play, make sure you are still able. If not, please be courtious and unregister so others may play. If you were not registered and the change now fits your schedule, go register now. In either case, <a href='http://pokernola.com/games.php'>go to the Games at pokernola.com to make your changes</a>.</p><p>Good luck!</p>";

		// Send email distribution.
		player_emails("distribution", $bcc_array, array($message));
	}
	
	// Send system email
	system_emails("game_rescheduled", array(date_to_php($game['game_name']), $game['game_name_more'], time_to_php($game['game_time']), date_to_php($game_name), $game_name_more, time_to_php($game_time)));
	
	header("Location: game_update.php?game_id=$game_id");
	exit();
