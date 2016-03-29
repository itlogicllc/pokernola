<?php 
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	
	$page_access_type = 'member';
	set_page_access($page_access_type);
	
	$todays_date = date("Y-m-d");
	$current_settings = settings_current();
	$settings_id = $current_settings['settings_id'];
	$end_date = $current_settings['end_date'];
	
	// If the end current season form was posted, set the end_early date to today's date
	// to signify the current season has ended. Refresh the settings array and reprocess the page
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$query =	"UPDATE settings SET
						ended_early='$todays_date'
					 WHERE settings_id='$settings_id'";

		$db_action = mysqli_query($db_connect, $query);
		
		settings_refresh();
	}
	
	$current_settings = settings_current();
	
	$end_date = $current_settings['end_date'];
	$ended_early_date = $current_settings['ended_early'];
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Tools</title>
	</head>
	<body>
		<div data-role="page" id="tools">
			<div data-role="header" data-position="fixed">
				<h1>Tools</h1>
				<?php require('includes/set_tools.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<?php if (isset($_SESSION['player_access']) && $_SESSION['player_access'] == 'admin') {
					// If the season end date is less than today's date or the eand_early date has a value
					// then show the Create new season button because the latest season has ended. Otherwise show the
					// End Season Now button because the latest season is still active.
					if ($end_date < $todays_date || $ended_early_date != '0000-00-00') { ?>
						<a href="tool_settings.php?player_id=<?php echo $player_logged_in_id ?>" data-role="button" data-theme="b" data-transition="fade">Create New Season</a>
					<?php } else { ?>
						<form action="tools.php" id="end_date_form" name="end_date_form" method="POST">
							<input name="Submit" type="submit" value="End Current Season" data-theme="b" onclick="return getSeasonEndVerify();" />
						</form>
					<?php }
				} ?>
				<?php if (isset($_SESSION['player_access']) && $_SESSION['player_access'] == 'admin') { ?>
					<a href="tool_distribution.php?player_id=<?php echo $player_logged_in_id ?>" data-role="button" data-theme="b" data-transition="fade">Email Distribution</a>
				<?php } ?>
				<?php if (isset($_SESSION['player_access'])) { ?>
					<a href="tool_scoring.php" data-role="button" data-transition="fade">Season Rules</a>
				<?php } ?>
				<?php if (isset($_SESSION['player_access'])) { ?>
					<a href="tool_contact.php" data-role="button" data-transition="fade">Contact</a>
				<?php } ?>
				<?php if (isset($_SESSION['player_access'])) { ?>
					<a href="tool_help.php" data-role="button" data-transition="fade">Help</a>
				<?php } ?>
				<a href="tool_about.php" data-role="button" data-transition="fade">About</a>
			</div>
			<div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>
