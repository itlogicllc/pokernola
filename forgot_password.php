<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_players.php'); ?>
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
              
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= "From: info@pokernola.com";
      $to = "xampp@localhost.com"; // uncomment for testing
      //$to = $loginUsername['email']; // uncomment for production
      $subject = "Your Poker NOLA password";
      $link = "http://localhost/pokernola/reset_password.php?player_id=" . $player_id . "&auth_code=" . $auth_code;
      $body = "Hey " . $loginUsername['first_name'] . ",<br \><br \>So, you forgot your Poker NOLA password did you? No problem!<br \><br \>Just click the link below or paste it into your browser's address bar. You will then be taken to a form where you can reset your password.<br \><br \>" . $link . "<br \><br \>Thanks for playing,<br \>Poker NOLA<br \><img height='80' width='80' src='http://pokernola.com/images/pokernola_logo.png'>";
      $body = wordwrap($body, 70);

      mail($to, $subject, $body, $headers);

      echo '<script> window.location = "forgot_password.php?message=An email with instruction for resetting your password has been sent to ' . $player_email . '. Don\'t forget to check your spam folder if it\'s not in your inbox."; </script>';
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
               <label for="email">Enter Your Registered Email Address:</label>
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
