<?php 
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	//get_access();
	require('includes/get_players.php');
	require('includes/set_emails.php');
	require('includes/get_messages.php');
	
	if(!empty($_SESSION['player_logged_in'])) {
		$player_id = $_SESSION['player_logged_in'];
		$player = players_by_id($player_id);
	}
	
	// Check for form submission
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$from = trim($player['email']);
		$name = trim($player['full_name']);
		$body = trim($_POST['message']);

		// Send the message from the contact form to the owner.
		contact_emails($from, $name, $body);
		
		// Set the message to display to the user to notify that message was sent.
		$messages[] = get_message("contact_sent", array($name, $from));
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Contact Form</title>
	</head>
	<body>
		<div data-role="page" id="tools">
			<div data-role="header" data-position="fixed">
				<h1>Contact</h1>
				<?php require('includes/set_tools.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<?php require('includes/set_messages.php'); ?>
				<form action="tool_contact.php" id="contact" name="contact" method="POST">
					<label for="message">Type a message to PokerNOLA below:</label>
					<textarea name="message" id="message" rows="10"></textarea>
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
