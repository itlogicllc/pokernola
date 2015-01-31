<?php require('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/set_emails.php'); ?>
<?php
	if(!empty($_SESSION['player_logged_in'])) {
		$player_id = $_SESSION['player_logged_in'];
		$player = players_player($player_id);
	}
	
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if (!empty($_POST['message'])) {
		$from = $player['email'];
		$name = $player['full_name'];
		$body = $_POST['message'];

		contact_emails($from, $name, $body);

		echo '<script> window.location = "tools_contact.php?message=You\'ve just sent your message to Poker NOLA from ' . $name . ' at ' . $from . '. We will respond ASAP!"; </script>';
		exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
	</head>
	<body>
		<div data-role="page" id="tools">
			<div data-role="header" data-position="fixed">
				<h1>Contact</h1>
				<?php require('includes/set_tools.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<?php if (isset($_GET['message']) && $_GET['message'] != "") { ?>
					<div class="ui-body ui-body-a ui-corner-all alert" align="center"><?php echo $_GET['message']; ?></div>
					<br />
				<?php } ?>
				<form action="<?php echo $editFormAction; ?>" id="contact" name="contact" method="POST">
					<label for="message">Type a message to Poker NOLA below:</label>
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
