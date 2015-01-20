<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/set_access.php'); ?>
<?php get_access(0); ?>
<?php require('includes/get_players.php'); ?>
<?php
// *** Redirect if username exists

if (isset($_POST['email']) && ($_POST['email'] != "")) {
   //if there is a row in the database, the username was found - can not add the requested username
   if (players_email_duplicate($_POST['email']) && $_POST['email'] != $_POST['email_exist']) {
      header("Location: player_profile.php?message=The email " . $_POST['email'] . " already exists!");
      exit();
   }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
   $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "profile")) {
   $updateSQL = sprintf("UPDATE players SET first_name=%s, last_name=%s, email=%s, password=SHA1(%s), `comment`=%s WHERE player_id=%s", GetSQLValueString($_POST['first_name'], "text"), GetSQLValueString($_POST['last_name'], "text"), GetSQLValueString($_POST['email'], "text"), GetSQLValueString($_POST['password1'], "text"), GetSQLValueString($_POST['textarea'], "text"), GetSQLValueString($_POST['player_id'], "int"));

   mysql_select_db($database_poker_db, $poker_db);
   $Result1 = mysql_query($updateSQL, $poker_db) or die(mysql_error());

   $updateGoTo = "player_profile.php";
   if (isset($_SERVER['QUERY_STRING'])) {
      $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
      $updateGoTo .= $_SERVER['QUERY_STRING'];
      $updateGoTo .= "&message=";
   }
   header(sprintf("Location: %s", $updateGoTo));
   exit();
}

$player_array = players_player($_SESSION['player_logged_in']);
?>
<!DOCTYPE html>
<html>
   <head>
<?php require('includes/set_head.php'); ?>
   </head>
   <body>
      <div data-role="page" id="profile">
         <div data-role="header" data-position="fixed">
            <h1>My Profile</h1>
      <?php require('includes/set_profile.php'); ?>
         </div>
         <div role="main" class="ui-content">
<?php if (isset($_GET['message']) && $_GET['message'] != "") { ?>
               <div class="ui-body ui-body-a ui-corner-all alert" align="center"><?php echo $_GET['message']; ?></div>
               <br />
            <?php } ?>
            <form action="<?php echo $editFormAction; ?>" id="profile" name="profile" method="POST">
               <label for="first_name">First Name:</label>
               <input name="first_name" type="text" id="first_name" value="<?php echo $player_array['first_name']; ?>" maxlength="30" required  />
               <label for="last_name">Last Name:</label>
               <input name="last_name" type="text" id="last_name" value="<?php echo $player_array['last_name']; ?>" maxlength="30" required  />
               <label for="email">Email:</label>
               <input name="email" type="email" id="email" value="<?php echo $player_array['email']; ?>" maxlength="30" required  />
               <label for="password1">Password:</label>
               <input name="password1" type="password" id="password1" value="<?php echo $player_array['password']; ?>" required />
               <label for="password2">Verify Password:</label>
               <input name="password2" type="password" id="password2" value="<?php echo $player_array['password']; ?>" required />
               <label for="textarea">Comment:</label>
               <textarea cols="40" rows="8" name="textarea" id="textarea"><?php echo $player_array['comment']; ?></textarea>
               <label for="player_id" class="ui-hidden-accessible">Text Input:</label>
               <input type="hidden" name="player_id" id="player_id" value="<?php echo $_SESSION['player_logged_in']; ?>"  />
               <label for="email_exist" class="ui-hidden-accessible">Email:</label>
               <input name="email_exist" type="hidden" id="email_exist" value="<?php echo $player_array['email']; ?>"  />
               <br />
               <div data-role="controlgroup" data-type="horizontal">
                  <input name="Submit" type="submit" value="Submit" onClick="return validateForm(6);" />
                  <input name="Reset" type="reset" value="Reset" />
               </div>
               <input type="hidden" name="MM_update" value="profile">
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
