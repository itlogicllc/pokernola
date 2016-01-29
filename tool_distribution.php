<?php 
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	get_access(1);
	require('includes/get_players.php');
	require('includes/set_emails.php');
	require('includes/get_messages.php');
	
	// Check for form submission.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Set for everyone or admins only.
		if ($_POST['send_to'] == "everyone") {
			$bcc = players_all();
			$sent_to = "everyone.";
		} else {
			$bcc = players_admins();
			$sent_to = "admins only.";
		}
		
		// Create an array of email addresses to send to.
		foreach($bcc as $value) {
			$bcc_array[] = $value['email'];
		}

		// Send email distribution.
		player_emails("distribution", $bcc_array, array($_POST['message']));

		// Set messade to notify user the distribution has been sent.
		$messages[] = get_message("distribution_sent", array($sent_to));
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Email Distribution</title>
	</head>
	<body>
		<div data-role="page" id="tools">
			<div data-role="header" data-position="fixed">
				<h1>Contact</h1>
				<?php require('includes/set_tools.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<?php require('includes/set_messages.php'); ?>
				<form action="tool_distribution.php" id="contact" name="contact" method="POST">
					<fieldset data-role="controlgroup" data-type="horizontal">
					  <legend>Send To:</legend>
					  <label for="everyone">Everyone</label>
					  <input name="send_to" id="everyone" type="radio" checked="checked" value="everyone" />
					  <label for="admins">Admins Only</label>
					  <input name="send_to" id="admins" type="radio" value="admins" />
					</fieldset>
					<textarea name="message" id="message"></textarea>
					<br />
					<div data-role="controlgroup" data-type="horizontal">
						<input name="submit" type="submit" value="Submit" />
						<input name="reset" type="reset" value="Reset" />
					</div>
				</form>
			</div>
			<div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>
