<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	//get_access();
	require('includes/get_invitation.php');
	require('includes/get_players.php');
	require('includes/get_messages.php');

	// Check for form submission
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$first_name = trim($_POST['first_name']);
		$last_name = trim($_POST['last_name']);
		$email = trim($_POST['email']);
		$password1 = trim($_POST['password1']);
		$password2 = trim($_POST['password2']);
		$nickname = trim($_POST['nickname']);
		$player_id = trim($_POST['player_id']);
		$email_existing = trim($_POST['email_exist']);

		// The function is true and the email value has changed from the existing email, 
		// exit the script and report it already exists.
		if ((players_email_duplicate($email)) && ($email != $email_existing)) {
			$messages[] = get_message("email_exists", array($email));
		}

		// Update the player's record with the form data. Update the password only if it isn't empty.
		if (empty($messages)) {
			if ($password1 == "") {
				$query =	"UPDATE players
							 SET first_name='$first_name',
								  last_name='$last_name', 
								  email='$email', 
								  comment='$nickname' 
							 WHERE player_id='$player_id'";
			} else {
				$query =	"UPDATE players
							 SET first_name='$first_name',
								  last_name='$last_name', 
								  email='$email', 
								  password=SHA1('$password1'),
								  comment='$nickname' 
							 WHERE player_id='$player_id'";
			}

			$db_action = mysqli_query($db_connect, $query);
			
			players_refresh();

			if ($db_action) {
				$messages[] = get_message("updated");
			} else {
				$messages[] = get_message("not_updated");
			}
		}
	}

	// Create the form action that redirects back to this page saving any query string data.
	$form_action = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$form_action .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	$player_array = players_by_id($_SESSION['player_logged_in']);
	$invited = invitations_invited($_SESSION['player_logged_in']);

	if ($player_array['invitation_id'] == 0) {
		$inviter_name = "PokerNOLA";
	} else {
		$invitation = invitations_by_id($player_array['invitation_id']);
		$inviter_id = $invitation['player_id'];
		$inviter = players_player($inviter_id);
		$inviter_name = $inviter['full_name'];
	}
?>
<!DOCTYPE html>
<html>
   <head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Player Profile</title>
   </head>
   <body>
      <div data-role="page" id="profile">
         <div data-role="header" data-position="fixed">
            <h1>My Profile</h1>
				<?php require('includes/set_profile.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <div data-role="tabs">
               <div data-role="navbar" data-iconpos="bottom">
                  <ul>
                     <li><a href="#details" data-ajax="false" class="ui-btn-active ui-state-persist" data-icon="edit">Details</a></li>
                     <li><a href="#invitations" data-ajax="false" data-icon="mail">Invitations</a></li>
                  </ul>
               </div>
               <div id="details">
                  <br />
						<?php require('includes/set_messages.php'); ?>
                  <form action="<?php echo $form_action; ?>" id="profile_form" name="profile_form" method="POST">
                     <label for="first_name">First Name:</label>
                     <input name="first_name" type="text" id="first_name" value="<?php echo $player_array['first_name']; ?>" maxlength="30" required  />
                     <label for="last_name">Last Name:</label>
                     <input name="last_name" type="text" id="last_name" value="<?php echo $player_array['last_name']; ?>" maxlength="30" required  />
                     <label for="email">Email:</label>
                     <input name="email" type="email" id="email" value="<?php echo $player_array['email']; ?>" maxlength="30" required  />
                     <label for="password1">Password: <span class="input_note">must be at least 6 characters</span></label>
                     <input name="password1" type="password" id="password1" value="" placeholder="Leave this blank to keep current password" />
                     <label for="password2">Verify Password:</label>
                     <input name="password2" type="password" id="password2" value="" />
                     <label for="nickname">Nickname:</label>
                     <input name="nickname" type="text" id="nickname" value="<?php echo $player_array['comment']; ?>" />
                     
							<input type="hidden" name="player_id" id="player_id" value="<?php echo $player_array['player_id']; ?>"  />
                     <input type="hidden" name="email_exist" id="email_exist" value="<?php echo $player_array['email']; ?>"  />
                     <br />
                     <div data-role="controlgroup" data-type="horizontal">
                        <input name="submit" type="submit" value="Update" onclick="return validateProfileForm(this.form);" />
                        <input name="reset" type="reset" value="Reset" />
                     </div>
                  </form>
               </div>
               <div id="invitations">
                  <br />
						<div class="ui-body ui-body-a ui-corner-all info">You were invited by <?php echo $inviter_name ?></div>
						<?php if (count($invited) == 0) { ?>
							<br />
							<div class="ui-body ui-body-a ui-corner-all alert">You haven't sent any invitation to anyone. Get to it! Don't you have any friends?</div>
						<?php } else { ?>
							<ul data-role="listview" data-inset="true" data-icon="delete">
							<?php for ($i = 0; $i <= count($invited) - 1; $i++) { ?>
								<li>
								<?php if ($invited[$i]['pending'] == 0) { ?>
									<img class="ui-li-icon" alt="accepted" src="images/accepted.png">
									<?php echo $invited[$i]['full_name']; ?> <br /><?php echo $invited[$i]['invitation_email']; ?>
								<?php } else { ?>
									<a href="invitation_delete.php?invitation_id=<?php echo $invited[$i]['invitation_id']; ?>"> 
										<img class="ui-li-icon" alt="pending" src="images/pending.png">
										<?php echo $invited[$i]['full_name']; ?> <br /><?php echo $invited[$i]['invitation_email']; ?>
									</a>
								<?php } ?>
								</li>
							<?php } ?>
							</ul>
						<?php } ?>
					</div>
				</div>
				<div data-role="footer" data-position="fixed">
					<?php require('includes/set_footer.php'); ?>
            </div>
         </div>
      </div>
   </body>
</html>
