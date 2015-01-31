<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/set_access.php'); ?>
<?php get_access(0); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/get_invitation.php'); ?>
<?php require('includes/set_emails.php'); ?>
<?php
// *** Redirect if username exists

if (isset($_POST['email']) && ($_POST['email'] != "")) {
   //if there is a row in the database, the username was found - can not add the requested username
   if (players_email_duplicate($_POST['email'])) {
      header("Location: player_invite.php?message=The email " . $_POST['email'] . " already exists! If they are an existing user, please log in using the their existing account.");
      exit();
   }
}

if ((isset($_POST['MM_update']) && $_POST['MM_update'] == "invite") && isset($_SESSION['player_logged_in'])) {
   $invited_before = invitations_email_duplicate($_POST['email']);
   
   if ($invited_before) {
      $invitation = invitations_by_email($_POST['email']);
      $invitation_code = $invitation['invitation_code'];
      $player_id = $invitation['player_id'];
      $invitation_id = $invitation['invitation_id'];
      
      $inviter = players_player($invitation['player_id']);
      
      $message = "A previous invitation was already sent to " . $_POST['email'] . " and is still pending. That previous invitation was just sent again.";
   } else {
      $inviter = players_player($_SESSION['player_logged_in']);
      $invitation_code = substr(md5(mt_rand()), -20);
      $player_id = $inviter['player_id'];
      $message = "You have just sent an email invitation for " . $_POST['first_name'] . " at " . $_POST['email'] . " to join Poker NOLA.";
      $invitation_email = $_POST['email'];
      
      $insertSQL = sprintf("INSERT INTO invitations (player_id, invitation_first, invitation_last, invitation_email, invitation_code) VALUES (%s, %s, %s, %s, %s)",
            GetSQLValueString($player_id, "int"),
            GetSQLValueString($_POST['first_name'], "text"),
            GetSQLValueString($_POST['last_name'], "text"),
            GetSQLValueString($invitation_email, "text"),
            GetSQLValueString($invitation_code, "text"));

     mysql_select_db($database_poker_db, $poker_db);
     $Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());
     
     $invitation = invitations_last();
     $invitation_id = $invitation[0]['invitation_id'];
   }
	
	$to_array = array($_POST['email']);
	$player_arg_array = array($_POST['first_name'], $inviter['full_name'], $player_id, $invitation_code, $invitation_id, "");
	player_emails($to_array, $player_arg_array, "invitation");

	$system_arg_array = array($inviter['full_name'], $_POST['first_name'], $_POST['last_name'], "", "", "");
	system_emails($system_arg_array, "invitation_sent");

   echo '<script> window.location = "player_invite.php?message='. $message . '"; </script>';
   exit();
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
   $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<!DOCTYPE html>
<html>
   <head>
      <?php require('includes/set_head.php'); ?>
   </head>
   <body>
      <div data-role="page" id="player_invite">
         <div data-role="header" data-position="fixed">
            <h1>Invite Player</h1>
            <?php require('includes/set_home.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <?php if (isset($_GET['message']) && $_GET['message'] != "") { ?>
               <div class="ui-body ui-body-a ui-corner-all alert" align="center"><?php echo $_GET['message']; ?></div>
               <br />
            <?php } ?>
            <form action="<?php echo $editFormAction; ?>" id="invite" name="intive" method="POST">
               <label for="first_name">Invitee's First Name:</label>
               <input name="first_name" type="text" id="first_name" value="" maxlength="30" required  />
               <label for="last_name">Invitee's Last Name:</label>
               <input name="last_name" type="text" id="last_name" value="" maxlength="30" required  />
               <label for="email">Invitee's Email:</label>
               <input name="email" type="email" id="email" value="" maxlength="30" required  />
               <label for="player_id" class="ui-hidden-accessible">Text Input:</label>
               <input type="hidden" name="player_id" id="player_id" value="<?php echo $_SESSION['player_logged_in']; ?>"  />
               <br />
               <div data-role="controlgroup" data-type="horizontal">
                  <input name="Submit" type="submit" value="Submit" />
                  <input name="Reset" type="reset" value="Reset" />
               </div>
               <input type="hidden" name="MM_update" value="invite">
            </form>
         </div>
         <div data-role="footer" data-position="fixed">
            <?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
