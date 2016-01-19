<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_players.php');
	require('includes/get_messages.php');
	
	// If the player is already logged in, redirect to home.php
	if (isset($_SESSION['player_logged_in'])) {
		header("Location: home.php");
		exit();
	}

	// If the form was submitted, check the email and
	// password against the database. If a match is found, set the
	// session and redirect to home. Otherwise, report invalid credentials
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$login_email = trim($_POST['email']);
		$login_password = trim($_POST['password']);
		
		$logged_in = players_login($login_email, sha1($login_password));

		// If an array is returned, user is authenticated
		if ($logged_in) {
			$_SESSION['player_email'] = $login_email;
			$_SESSION['player_logged_in'] = $logged_in['player_id'];
			$_SESSION['player_first'] = $logged_in['first_name'];
			$_SESSION['player_access'] = $logged_in['access_level'];
			
			header("Location: home.php");
			exit();
		} else {
			$messages[] = get_message("invalid_credentials");
		}
	}
?>
<!DOCTYPE html>
<html>
   <head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Login</title>
   </head>
   <body>
      <div data-role="page" id="login">
         <div data-role="header" data-position="fixed">
            <h1>Login</h1>
				<?php require('includes/set_home.php'); ?>
         </div>
         <div role="main" class="ui-content">
				<div role="main" class="ui-content">
					<?php require('includes/set_messages.php'); ?>
					<form action="index.php" id="login_form" name="login_form" method="POST">
						<label for="email">Email:</label>
						<input name="email" type="email" id="email" value="<?php echo (isset($_COOKIE['pokernola_player']) ? $_COOKIE['pokernola_player'] : ''); ?>" maxlength="30"  />
						<label for="password">Password:</label>
						<input name="password" type="password" id="password" value="<?php echo (isset($_COOKIE['pokernola_pass']) ? $_COOKIE['pokernola_pass'] : ''); ?>" maxlength="30"  />
						<label for="remember">Remember Me</label>
						<input name="remember" id="remember" type="checkbox" data-mini="true" value="yes" <?php echo (isset($_COOKIE['pokernola_player']) ? 'checked' : ''); ?> />
						<br />
						<div data-role="controlgroup" data-type="horizontal">
							<input name="submit" type="submit" value="Log In" />
							<a class="ui-btn" href="password_send.php">Forgot Password?</a>
						</div>
					</form>
					<div data-role="footer" data-position="fixed">
						<?php require('includes/set_footer.php'); ?>
					</div>
				</div>
			</div>
		</div>
   </body>
</html>
