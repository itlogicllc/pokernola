<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php
// *** Redirect if username exists
$not_auth_message = "You are not authorized to reset this password! If you pasted the link you received into your browser's address bar, make sure you pasted the entire link. Otherwise, report to your local police station and surrender yourself as a cyber criminal.";

if (isset($_GET['player_id']) && (isset($_GET['auth_code']) && $_GET['auth_code'] != "")) {
   $player_id = $_GET['player_id'];
   $auth_code = $_GET['auth_code'];

   $authcodeRS__query = sprintf("SELECT auth_code FROM players WHERE player_id=%s", GetSQLValueString($player_id, "int"));

   mysql_select_db($database_poker_db, $poker_db);
   $authcodeRS = mysql_query($authcodeRS__query, $poker_db) or die(mysql_error());
   $db_auth_code = mysql_fetch_assoc($authcodeRS);

   if ($auth_code != $db_auth_code['auth_code']) {
      header("Location: access_denied.php?message=" . $not_auth_message);
      exit();
   }
} else {
   header("Location: access_denied.php?message=" . $not_auth_message);
   exit();
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
   $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "password")) {
   $updateSQL = sprintf("UPDATE players SET password=SHA1(%s), auth_code=NULL WHERE player_id=%s", 
           GetSQLValueString($_POST['password1'], "text"),
           GetSQLValueString($player_id, "int"));

   mysql_select_db($database_poker_db, $poker_db);
   $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());

   header(sprintf("Location: login.php?message=You have successfully reset your password! You may now log in with your new credentials."));
   exit();
}
?>
<!DOCTYPE html>
<html>
   <head>
<?php require('includes/set_head.php'); ?>
   </head>
   <body>
      <div data-role="page" id="reset_password">
         <div data-role="header" data-position="fixed">
            <h1>Reset Password</h1>
<?php require('includes/set_home.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <?php if (isset($_GET['message']) && $_GET['message'] != "") { ?>
               <div class="ui-body ui-body-a ui-corner-all alert" align="center"><?php echo $_GET['message']; ?></div>
            <?php } ?>
            <form action="<?php echo $editFormAction; ?>" id="reset_pass" name="reset_pass" method="POST">
               <label for="password1">New Password:</label>
               <input name="password1" type="password" id="password1" value="" required />
               <label for="password2">Verify Password:</label>
               <input name="password2" type="password" id="password2" value="" required />
               <label for="player_id" class="ui-hidden-accessible">Text Input:</label>
               <input type="hidden" name="player_id" id="player_id" value="<?php $player_id; ?>"  />
               <br />
               <div data-role="controlgroup" data-type="horizontal">
                  <input name="Submit" type="submit" value="Submit" onClick="return validateForm(6);" />
                  <input name="Reset" type="reset" value="Reset" />
               </div>
               <input type="hidden" name="MM_update" value="password">
            </form>
            <script>
               function validateForm(min_num) {
                  var pass1 = document.getElementById('password1');
                  var pass2 = document.getElementById('password2');
                  var pass1_val = pass1.value;
                  var pass1_num = pass1_val.length;
                  var pass2_val = pass2.value;

                  if (pass1_num < min_num) {
                     alert("Password must be at least " + min_num + " characters");
                     return false;
                  }

                  if (pass2_val != pass1_val) {
                     alert("The passwords do not match");
                     return false;
                  }
               }
            </script>
         </div>
         <div data-role="footer" data-position="fixed">
<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
