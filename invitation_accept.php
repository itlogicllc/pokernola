<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_invitation.php');
	require('includes/set_emails.php');
	require('includes/get_messages.php');
	
	$page_access_type = 'all';
	set_page_access($page_access_type);

	// Make sure the query string has a player_id, invitation_code and invitation_id value.
	// If not redirect to access denied.
	if (!empty($_GET['player_id']) && !empty($_GET['invitation_code']) && !empty($_GET['invitation_id'])) {
		$player_id = trim($_GET['player_id']);
		$invitation_code = trim($_GET['invitation_code']);
		$invitation_id = trim($_GET['invitation_id']);

		$invitation = invitations_by_id($invitation_id);

		// Check the invitation_code and player_id in the database against the invitation_code
		// and player_id in the query srtring. If they don't match redirect to access denied.
		if ($invitation_code != $invitation['invitation_code'] || $player_id != $invitation['player_id']) {
			header("Location: access_denied.php?message=unauthorized");
			exit();
		}
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}

	// If the form was submitted.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$first_name = trim($_POST['first_name']);
		$last_name = trim($_POST['last_name']);
		$email = trim($_POST['email']);
		$password = trim($_POST['password1']);
		$date_accepted = date('Y-m-d', time());

		// The function is true if the given email already exists in the database. 
		// Exit the script and report it already exists.
		if (players_email_duplicate($email)) {
			$messages[] = get_message("email_exists", array($email));
		}

		// If no email already exists message.
		if (empty($messages)) {
			$player = players_by_id($player_id);

			// Insert the new player record into the database
			$query1 = "INSERT INTO players
						  (invitation_id, first_name, last_name, email, password, date_added)
						  VALUES
						  ('$invitation_id', '$first_name', '$last_name', '$email', SHA1('$password'), '$date_accepted')";

			$db_action1 = mysqli_query($db_connect, $query1);

			// Update the invitation record to remove the invitation code and turn pending off
			$query2 = "UPDATE invitations
						  SET invitation_code='NULL', pending='0'
						  WHERE invitation_id='$invitation_id'";

			$db_action2 = mysqli_query($db_connect, $query2);

			// Email the owner a system message that the new player just joined
			system_emails("invitation_accepted", array($first_name, $last_name, $player['full_name']));

			header("Location: index.php?message=welcome");
			exit();
		}
	}
	
	$form_action = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$form_action .= "?" . $_SERVER['QUERY_STRING'];
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Accept Invitation</title>
	</head>
	<body>
		<div data-role="page" id="add_player">
			<div data-role="header" data-position="fixed">
				<h1>Add Player</h1>
				<?php require('includes/set_players.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<?php require('includes/set_messages.php'); ?>
				<form id="profile" name="profile" method="POST" action="<?php echo $form_action; ?>">
					<label for="first_name">First Name:</label>
					<input name="first_name" type="text" id="first_name" value="<?php echo $invitation['invitation_first']; ?>" maxlength="30" required  />
					<label for="last_name">Last Name:</label>
					<input name="last_name" type="text" id="last_name" value="<?php echo $invitation['invitation_last']; ?>" maxlength="30" required  />
					<label for="email">Email:</label>
					<input name="email" type="email" id="email" value="<?php echo $invitation['invitation_email']; ?>" maxlength="30" required  />
					<label for="password1">Password: <span class="input_note">must be at least 6 characters</span></label>
					<input name="password1" type="password" id="password1" value="" required  />
					<label for="password2">Verify Password:</label>
					<input name="password2" type="password" id="password2" value="" required  />
					<br />
					<div data-role="controlgroup" data-type="horizontal">
						<input name="Submit" type="submit" value="Accept Invitation" onClick="return passwordValidate(document.getElementById('password1'), document.getElementById('password2'), 6);" />
					</div>
				</form>
			</div>
			<div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>