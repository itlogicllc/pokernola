<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_players.php');
	require('includes/set_emails.php');
	
	// Make sure the query string has a player_id and auth_code value.
	// If not redirect to access denied.
	if (!empty($_GET['player_id']) && !empty($_GET['auth_code'])) {
		$player_id = trim($_GET['player_id']);
		$auth_code = trim($_GET['auth_code']);

		$player = players_by_id($player_id);

		// Check the auth_code in the database against the auth_code in
		// the query srtring. If they don't match redirect to access denied.
		if ($auth_code != $player['auth_code']) {
			header("Location: access_denied.php?message=unauthorized");
			exit();
		}
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}

	// If the form was submitted update the password to the new password
	// and set the aut_code back to NULL
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$password = trim($_POST['password1']);

		$query = "UPDATE players
					 SET password=SHA1('$password'),
						  auth_code=NULL
					 WHERE player_id='$player_id'";

		$db_action = mysqli_query($db_connect, $query);

		// Send a system email to notify the owner of the event
		system_emails("password_changed", array($player['full_name']));

		// redirect to the index.php so the user can log in
		header("Location: index.php?message=password_reset");
		exit();
	}

	$form_action = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$form_action .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
?>
<!DOCTYPE html>
<html>
   <head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Reset Password</title>
   </head>
   <body>
      <div data-role="page" id="reset_password">
         <div data-role="header" data-position="fixed">
            <h1>Reset Password</h1>
				<?php require('includes/set_home.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <form action="<?php echo $form_action; ?>" id="reset_pass" name="reset_pass" method="POST">
               <label for="password1">New Password: <span class="input_note">must be at least 6 characters</span></label>
               <input name="password1" type="password" id="password1" value="" required />
               <label for="password2">Verify Password:</label>
               <input name="password2" type="password" id="password2" value="" required />

               <input type="hidden" name="player_id" id="player_id" value="<?php $player_id; ?>"  />
               <br />
               <div data-role="controlgroup" data-type="horizontal">
                  <input name="Submit" type="submit" value="Submit" onClick="return passwordValidate(this.form);" />
               </div>
            </form>
         </div>
         <div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
