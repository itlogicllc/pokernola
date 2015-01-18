<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php
// *** Validate request to login to this site.

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
   $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_POST['email'])) {
   $loginUsername = $_POST['email'];
   $password = $_POST['password'];
   $MM_fldUserAuthorization = "access_level";

   mysql_select_db($database_poker_db, $poker_db);

   $LoginRS__query = sprintf("SELECT email, password, access_level, player_id, first_name FROM players WHERE email=%s AND password=%s", GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"));

   $LoginRS = mysql_query($LoginRS__query, $poker_db) or die(mysql_error());
   $row_LoginRS = mysql_fetch_assoc($LoginRS);
   $loginFoundUser = mysql_num_rows($LoginRS);
   if ($loginFoundUser) {
//      if (PHP_VERSION >= 5.1) {
//         session_regenerate_id(true);
//      } else {
//         session_regenerate_id();
//      }
      //declare two session variables and assign them
      $_SESSION['MM_Username'] = $loginUsername;
      $_SESSION['player_logged_in'] = $row_LoginRS['player_id'];
      $_SESSION['player_first'] = $row_LoginRS['first_name'];
      $_SESSION['player_access'] = $row_LoginRS['access_level'];

      //header("location: $editFormAction");
   }
}
?>
<!DOCTYPE html>
<html>
   <head>
      <?php require('includes/set_head.php'); ?>
   </head>
   <body>
      <div data-role="page" id="login">
         <?php require('includes/set_panel_date.php'); ?>
         <div data-role="header" data-position="fixed">
            <h1>Login</h1>
            <?php require('includes/set_home.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <form ACTION="<?php echo $editFormAction; ?>" id="login" name="login" method="POST">
               <label for="email">Email:</label>
               <input name="email" type="email" id="email" value="<?php echo (isset($_COOKIE['pokernola_player']) ? $_COOKIE['pokernola_player'] : ''); ?>" maxlength="30"  />
               <label for="password">Password:</label>
               <input name="password" type="password" id="password" value="<?php echo (isset($_COOKIE['pokernola_pass']) ? $_COOKIE['pokernola_pass'] : ''); ?>" maxlength="30"  />
               <label for="remember">Remember Me</label>
               <input name="remember" id="remember" type="checkbox" value="yes" <?php echo (isset($_COOKIE['pokernola_player']) ? 'checked' : ''); ?> />
               <br />
               <div data-role="controlgroup" data-type="horizontal">
                  <input name="submit" type="submit" value="Log In" />
                  <input name="cancel" id="cancel" type="button" value="Cancel" data-rel="close" />
               </div>
            </form>
         </div>
         <div data-role="footer" data-position="fixed">
            <?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
