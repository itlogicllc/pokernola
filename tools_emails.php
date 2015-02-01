<?php require('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/set_emails.php'); ?>
<?php
	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if (!empty($_POST['message'])) {
		if ($_POST['send_to'] == "everyone") {
			$bcc = players_list();
			$sent_to = "everyone.";
		} else {
			$bcc = players_admins();
			$sent_to = "admins only.";
		}
		
		foreach($bcc as $value) {
			$bcc_array[] = $value['email'];
		}
		
		$arg_array = array($_POST['message'], "", "", "", "", "");

		player_emails($bcc_array, $arg_array, "distribution");

		echo '<script> window.location = "tools_emails.php?message=Your distribution has been sent to ' . $sent_to . '"; </script>';
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
