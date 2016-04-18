<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_invitation.php');
	require('includes/set_emails.php');
	require('includes/get_messages.php');
	
	$page_access_type = 'player';
	set_page_access($page_access_type);
	
	// Check to make sure the players count is less than the max members allowed and that
	// the max_members setting is not 0, which means unlimited allowed
	if (count(players_all()) >= $settings_array['max_members'] && $settings_array['max_members'] > 0){
		header("Location: access_denied.php?message=max_members");
		exit();
	}
	
	// If the form was submitted.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$first_name = trim($_POST['first_name']);
		$last_name = trim($_POST['last_name']);
		$email = trim($_POST['email']);
		
		// The function is true and the email value has changed from the existing email, 
		// exit the script and report it already exists.
		if (players_email_duplicate($email)) {
			$messages[] = get_message("email_exists", array($email));
		}
		
		// If no email already exists message.
		if (empty($messages)) {
			// Check if the submitted email already has a pending invitation.
			// Return true if it exists, false if not.
			$invited_before = invitations_email_duplicate($email);
			
			// If invited_before is true, get the invitation details associated with
			// the email. After, get the details og the inviter based on the player_id
			// of who sent the invitation. Fianlly, notify the user that the email given
			// already has a pending invitation.
			if ($invited_before) {
				$invitation = invitations_by_email($email);
				
				$invitation_code = $invitation['invitation_code'];
				$player_id = $invitation['player_id'];
				$invitation_id = $invitation['invitation_id'];

				$inviter = players_by_id($player_id);

				$messages[] = get_message("invitation_pending", array($email));
			// If invited_before is false, get the player details of the currentlu logged in player.
			// Create the authentication code and insert the invitation record into the database.
			// Get the invitation details that were just added to the database the
			// Notify the user that the invitation was sent.
			} else {
				$player_id = $player_logged_in_id;
				
				$inviter = players_by_id($player_id);
				
				$invitation_code = substr(md5(mt_rand()), -20);
				$invitation_email = $email;
				
				// Insert the new invitation into the database
				$query = "INSERT INTO invitations
							 (player_id, invitation_first, invitation_last, invitation_email, invitation_code)
							 VALUES
							 ('$player_id', '$first_name', '$last_name', '$email', '$invitation_code')";

				$db_action = mysqli_query($db_connect, $query);

				// Refresh the invitations recordset to include the invitation that was just added.
				invitations_refresh();
				$invitation = invitations_all();
				$invitation_id = $invitation[0]['invitation_id'];
				
				$messages[] = get_message("invitation_email", array($first_name, $email));
			}
			
			// Email the player the invitation
			player_emails("invitation", array($email), array($first_name, $inviter['full_name'], $player_id, $invitation_code, $invitation_id));
			
			// Email the owner the system message that an invitation was sent
			system_emails("invitation_sent", array($inviter['full_name'], $first_name, $last_name));
		}
	}
?>
<!DOCTYPE html>
<html>
   <head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Invitation to Join</title>
   </head>
   <body>
      <div data-role="page" id="player_invite">
         <div data-role="header" data-position="fixed">
            <h1>Invite Player</h1>
				<?php require('includes/set_home.php'); ?>
         </div>
         <div role="main" class="ui-content">
				<?php require('includes/set_messages.php'); ?>
            <form action="" id="invite" name="intive" method="POST">
               <label for="first_name">Invitee's First Name:</label>
               <input name="first_name" type="text" id="first_name" value="" maxlength="30" required  />
               <label for="last_name">Invitee's Last Name:</label>
               <input name="last_name" type="text" id="last_name" value="" maxlength="30" required  />
               <label for="email">Invitee's Email:</label>
               <input name="email" type="email" id="email" value="" maxlength="30" required  />
               <br />
               <div data-role="controlgroup" data-type="horizontal">
                  <input name="Submit" type="submit" value="Send Invitation" />
               </div>
            </form>
         </div>
         <div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
