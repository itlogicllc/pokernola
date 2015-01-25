<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/get_invitation.php'); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
   $editFormAction .= "?" . $_SERVER['QUERY_STRING'];
}

// *** Redirect if username exists
if (isset($_POST['email']) && ($_POST['email'] != "")) {
   //if there is a row in the database, the username was found - can not add the requested username
   if (players_email_duplicate($_POST['email'])) {
      header("Location: " . $editFormAction . "&message=The email " . $_POST['email'] . " already exists! If this is an existing user, please log in using that existing account.");
      exit();
   }
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "registration")) {
   
   if (isset($_GET['player_id'])) {$player_id = $_GET['player_id'];}
   if (isset($_GET['invitation_code'])) {$auth_code = $_GET['invitation_code'];}
   if (isset($_GET['invitation_id'])) {$invitation_id = $_GET['invitation_id'];}

   $insertSQL = sprintf("INSERT INTO players (invitation_id, first_name, last_name, email, password, date_added) VALUES (%s, %s, %s, %s, SHA1(%s), %s)", GetSQLValueString($invitation_id, "int"), GetSQLValueString($_POST['first_name'], "text"), GetSQLValueString($_POST['last_name'], "text"), GetSQLValueString($_POST['email'], "text"), GetSQLValueString($_POST['password1'], "text"), GetSQLValueString($_POST['add_date'], "date"));

   mysql_select_db($database_poker_db, $poker_db);
   $Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());

   $updateSQL = sprintf("UPDATE invitations SET invitation_code=NULL, pending=0 WHERE player_id=%s AND invitation_code=%s", GetSQLValueString($player_id, "int"), GetSQLValueString($auth_code, "text"));

   mysql_select_db($database_poker_db, $poker_db);
   $Result2 = mysql_query($updateSQL, $poker_db) or die(mysql_error());

   header(sprintf("Location: index.php?message=Welcome to Poker NOLA! You are now officially a member and can log in with your new credentials."));
   exit();
}

$not_auth_message = "This is not an authorized invitation to join Poker NOLA! If you pasted the link you received into your browser's address bar, make sure you pasted the entire link. Otherwise, report to your local police station and surrender yourself as a cyber criminal.";

if ((isset($_GET['player_id']) && $_GET['player_id'] != "") && (isset($_GET['invitation_code']) && $_GET['invitation_code'] != "")) {
   $player_id = $_GET['player_id'];
   $auth_code = $_GET['invitation_code'];

   $invitation = invitations_invitation($player_id, $auth_code);

   if ($auth_code != $invitation['invitation_code']) {
      header("Location: access_denied.php?message=" . $not_auth_message);
      exit();
   }
} else {
   header("Location: access_denied.php?message=" . $not_auth_message);
   exit();
}
?>
<!DOCTYPE html>
<html>
   <head>
      <?php require('includes/set_head.php'); ?>
   </head>
   <body>
      <div data-role="page" id="add_player">
         <div data-role="header" data-position="fixed">
            <h1>Add Player</h1>
            <?php require('includes/set_players.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <?php if (isset($_GET['message']) && $_GET['message'] != "") { ?>
               <div class="ui-body ui-body-a ui-corner-all alert" align="center"><?php echo $_GET['message']; ?></div>
               <br />
            <?php } ?>
            <form id="profile" name="profile" method="POST" action="<?php echo $editFormAction; ?>">
               <label for="first_name">First Name:</label>
               <input name="first_name" type="text" id="first_name" value="<?php echo $invitation['invitation_first']; ?>" maxlength="30" required  />
               <label for="last_name">Last Name:</label>
               <input name="last_name" type="text" id="last_name" value="<?php echo $invitation['invitation_last']; ?>" maxlength="30" required  />
               <label for="email">Email:</label>
               <input name="email" type="email" id="email" value="<?php echo $invitation['invitation_email']; ?>" maxlength="30" required  />
               <label for="password1">Password:</label>
               <input name="password1" type="password" id="password1" value="" required  />
               <label for="password2">Verify Password:</label>
               <input name="password2" type="password" id="password2" value="" required  />
               <label for="add_date" class="ui-hide-label"></label>
               <input name="add_date" type="hidden" id="add_date" value="<?php echo date('Y-m-d', time()); ?>" />
               <br />
               <div data-role="controlgroup" data-type="horizontal">
                  <input name="Submit" type="submit" value="Submit" onClick="return validateForm(6);" />
                  <input name="Reset" type="reset" value="Reset" />
               </div>
               <input type="hidden" name="MM_insert" value="registration" />
            </form>
            <script type="text/javascript">
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