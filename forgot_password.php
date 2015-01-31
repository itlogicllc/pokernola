<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/set_emails.php'); ?>
<?php
// *** Validate request to login to this site.

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
   $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_POST['email']) && $_POST['email'] != "") {
   $loginUsername = players_by_email($_POST['email']);
   
   if (count($loginUsername) != 0) {
      $player_name = $loginUsername['first_name'];
      $player_id = $loginUsername['player_id'];
      $player_email = $loginUsername['email'];
      $auth_code = substr(md5(mt_rand()), -20);
      
      $updateAuthCode__query = sprintf("UPDATE players SET auth_code=%s WHERE player_id=%s",
            GetSQLValueString($auth_code, "text"),
            GetSQLValueString($player_id, "int"));
       
      $updateAuthCode = mysql_query($updateAuthCode__query, $poker_db) or die(mysql_error());
              
		$to_array = array($loginUsername['email']);
		$player_arg_array = array($loginUsername['first_name'], $player_id, $auth_code, "", "", "");
		player_emails($to_array, $player_arg_array, "password");
		
		$system_arg_array = array($loginUsername['full_name'], "", "", "", "", "");
		system_emails($system_arg_array, "password_request");

      echo '<script> window.location = "forgot_password.php?message=An email with instruction for resetting your password has been sent to ' . $player_email . '. Don\'t forget to check your spam folder if it\'s not in your inbox. It could take up to 24 hours to receive."; </script>';
      exit();
   } else {
      echo '<script> window.location = "forgot_password.php?message=The email address ' . $_POST['email'] . ' does not exist! Please try again."; </script>';
      exit();
   }
}
?>
<!DOCTYPE html>
<html>
   <head>
<?php require('includes/set_head.php'); ?>
   </head>
   <body>
      <div data-role="page" id="forgot_password">
         <div data-role="header" data-position="fixed">
            <h1>Forgot Password</h1>
<?php require('includes/set_home.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <?php if (isset($_GET['message']) && $_GET['message'] != "") { ?>
               <div class="ui-body ui-body-a ui-corner-all alert" align="center"><?php echo $_GET['message']; ?></div>
               <br />
            <?php } ?>
            <form action="<?php echo $editFormAction; ?>" id="forgot_pass" name="forgot_pass" method="POST">
               <label for="email">Enter Your Login Email Address:</label>
               <input name="email" type="email" id="email" value="" maxlength="30"  />
               <br />
               <div data-role="controlgroup" data-type="horizontal">
                  <input name="submit" type="submit" value="Submit" />
                  <input name="Reset" type="reset" value="Reset" />
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
