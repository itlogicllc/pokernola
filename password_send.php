<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_players.php');
	require('includes/set_emails.php');
	require('includes/get_messages.php');

	// If the form was submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$form_email = trim($_POST['email']);
		$player = players_by_email($form_email);

		if ($player) {
			$player_first = $player['first_name'];
			$player_full = $player['full_name'];
			$player_id = $player['player_id'];
			$player_email = $player['email'];
			$auth_code = substr(md5(mt_rand()), -20);

			// update the player's record with a new auth_code
			$query = "UPDATE players
						 SET auth_code='$auth_code'
						 WHERE player_id='$player_id'";

			$db_action = mysqli_query($db_connect, $query);

			// Send an email to the player with the link to cjange their password
			player_emails("password", array($player_email), array($player_first, $player_id, $auth_code));

			// Send an email to the owner to notify them of the event
			system_emails("password_request", array($player_full));

			$messages[] = get_message("password_email", array($player_email));
		} else {
			$messages[] = get_messages("email_not_exists", array($form_email));
		}
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
		<title>PokerNOLA Forgotten Password</title>
   </head>
   <body>
      <div data-role="page" id="forgot_password">
         <div data-role="header" data-position="fixed">
            <h1>Forgot Password</h1>
				<?php require('includes/set_home.php'); ?>
         </div>
         <div role="main" class="ui-content">
				<?php require('includes/set_messages.php'); ?>
            <form action="<?php echo $form_action; ?>" id="forgot_pass" name="forgot_pass" method="POST">
               <label for="email">Enter Your Login Email Address:</label>
               <input name="email" type="email" id="email" value="" maxlength="30"  />
               <br />
               <div data-role="controlgroup" data-type="horizontal">
                  <input name="submit" type="submit" value="Submit" />
               </div>
            </form>
            <br />
         </div>
         <div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
