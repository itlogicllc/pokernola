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
   $loginUsername = $_POST['email'];
   $password = $_POST['password'];
   $logged_in = players_login($loginUsername, sha1($password));
   
   if (count($logged_in) != 0) {

      $_SESSION['MM_Username'] = $loginUsername;
      $_SESSION['player_logged_in'] = $logged_in['player_id'];
      $_SESSION['player_first'] = $logged_in['first_name'];
      $_SESSION['player_access'] = $logged_in['access_level'];

      echo '<script> window.location = "index.php"; </script>';
      exit();
   } else {
      echo '<script> window.location = "login.php?message=Invalid email or password!"; </script>';
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
      <div data-role="page" id="login">
         <div data-role="header" data-position="fixed">
            <h1>Login</h1>
            <?php require('includes/set_home.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <?php if (isset($_GET['message']) && $_GET['message'] != "") { ?>
               <div class="ui-body ui-body-a ui-corner-all alert" align="center"><?php echo $_GET['message']; ?></div>
               <br />
            <?php } ?>
            <form action="<?php echo $editFormAction; ?>" id="login" name="login" method="POST">
               <label for="email">Email:</label>
               <input name="email" type="email" id="email" value="<?php echo (isset($_COOKIE['pokernola_player']) ? $_COOKIE['pokernola_player'] : ''); ?>" maxlength="30"  />
               <label for="password">Password:</label>
               <input name="password" type="password" id="password" value="<?php echo (isset($_COOKIE['pokernola_pass']) ? $_COOKIE['pokernola_pass'] : ''); ?>" maxlength="30"  />
               <label for="remember">Remember Me</label>
               <input name="remember" id="remember" type="checkbox" data-mini="true" value="yes" <?php echo (isset($_COOKIE['pokernola_player']) ? 'checked' : ''); ?> />
               <br />
               <div data-role="controlgroup" data-type="horizontal">
                  <input name="submit" type="submit" value="Log In" />
                  <a class="ui-btn" href="forgot_password.php">Forgot Password</a>
               </div>
            </form>
            <div data-role="footer" data-position="fixed">
               <?php require('includes/set_footer.php'); ?>
            </div>
         </div>
      </div>
   </body>
</html>
