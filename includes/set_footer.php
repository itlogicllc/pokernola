<a href="#date_panel" class="ui-btn-left" data-role="button" data-icon="<?php echo ($date_pick_on == 1 ? 'calendar' : 'forbidden'); ?>" data-iconpos="notext" data-transition="fade">Calendar</a>
<h6>
	<?php
		require_once('includes/get_players.php');
		$seasons = settings_all();
		$season_name = "";
		
		for ($i = 0; $i <= count($seasons) - 1; $i++) {
			if (($seasons[$i]['start_date'] == $_SESSION['from_date']) && ($seasons[$i]['end_date'] == $_SESSION['to_date'])) {
				$season_name = $seasons[$i]['season_name'];
				$credits_per_degree = $seasons[$i]['credits_per_degree'];
			}
		}

		if (isset($_SESSION['player_first'])) {
			echo "Welcome " . $_SESSION['player_first'] . "!";
			
			if ($credits_per_degree > 0) {
				$player_id = $_SESSION['player_logged_in'];
				$players_priority = players_priority($player_id, $credits_per_degree);
				echo " <span class='level_" . $players_priority['level'] . "'>" .  $players_priority['degree'] . "</span>";
			}
		} else {
			echo "PokerNOLA";
		}
	?>
	<br />
	<?php if ($season_name == "") { ?>
		<span class="date_from date_range"><?php echo date_to_php($_SESSION['from_date']) ?></span> | <span class="date_to date_range"><?php echo date_to_php($_SESSION['to_date']) ?></span>
	<?php } else { ?>
		<span class="season_range date_range" id="season_name"><?php echo $season_name ?></span>
	<?php } ?>
</h6>
<?php if ($player_logged_in_id) { ?>
	<a href="player_profile.php?player_id=<?php echo $player_logged_in_id ?>" class="ui-btn-right" data-role="button" data-icon="bars" data-iconpos="notext" data-transition="fade">Edit Profile</a>
<?php } else { ?>
	<a href="index.php" class="ui-btn-right" data-role="button" data-icon="lock" data-iconpos="notext" data-transition="fade">Log in</a>
<?php } ?>

<?php mysqli_close($db_connect); ?>