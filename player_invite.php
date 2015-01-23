<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/set_access.php'); ?>
<?php get_access(0); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/get_invitation.php'); ?>
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
   
   $headers = "MIME-Version: 1.0" . "\r\n";
   $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
   $headers .= "From: info@pokernola.com";
   $to = "xampp@localhost.com"; // uncomment for testing
   //$to = $_POST['email']; // uncomment for production
   $subject = "Invitation to join Poker NOLA";
   $link = "http://localhost/pokernola/player_add.php?player_id=" . $player_id . "&invitation_code=" . $invitation_code . "&invitation_id=" . $invitation_id;  // uncomment for testing
   //$link = "http://www.pokernola.com/player_add.php?player_id=" . $player_id . "&invitation_code=" . $invitation_code . "&invitation_id=" . $invitation_id;  // uncomment for production
   $body = "Welcome " . $_POST['first_name'] . ",<p>Good news! " . $inviter['full_name'] . " would like to invite you to join Poker NOLA. New members are always welcome and we hope you accept this invitation to join. To do so, simply click on or paste the link at the end of this email into your browser's address bar. You will then be taken to a new player registration form. Fill it out, submit it and then just like that, you will become a Poker NOLA member.</p><p>As soon as you are registered you will be able to log in, register for games, have your performance tracked, score points, get ranked and be able to send others invitations just like this one.</p><p>We would be happy to have you, so join today and good luck!</p><p>" . $link . "</p><br /><br />Thanks for playing,<br />Poker NOLA<br /><img height='100' width='100' src='http://pokernola.com/images/pokernola_logo.png'>";
   $body = wordwrap($body, 70);

   mail($to, $subject, $body, $headers);

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
