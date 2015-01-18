<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php
// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="player_add.php?add_message=" . $_POST['email'] . " already exists!";
  $loginUsername = $_POST['email'];
  $LoginRS__query = sprintf("SELECT email FROM players WHERE email=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_poker_db, $poker_db);
  $LoginRS=mysql_query($LoginRS__query, $poker_db) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// TODO add encryption and salt field to password
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "registration")) {
  $insertSQL = sprintf("INSERT INTO players (first_name, last_name, email, password, date_added) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['first_name'], "text"),
                       GetSQLValueString($_POST['last_name'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['password1'], "text"),
                       GetSQLValueString($_POST['add_date'], "date"));

  mysql_select_db($database_poker_db, $poker_db);
  $Result1 = mysql_query($insertSQL, $poker_db) or die(mysql_error());

  $insertGoTo = "players.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
		<div class="alert" align="center"><?php echo $_GET['add_message'] ?></div>
		<form id="profile" name="profile" method="POST" action="<?php echo $editFormAction; ?>">
			<div data-role="fieldcontain">
				<label for="first_name">First Name:</label>
				<input name="first_name" type="text" id="first_name" value="" maxlength="30" required  />
				<label for="last_name">Last Name:</label>
				<input name="last_name" type="text" id="last_name" value="" maxlength="30" required  />
				<label for="email">Email:</label>
				<input name="email" type="email" id="email" value="" maxlength="30" required  />
				<label for="password1">Password:</label>
				<input name="password1" type="password" id="password1" value="" required  />
				<label for="password2">Verify Password:</label>
				<input name="password2" type="password" id="password2" value="" required  />
				<label for="add_date" class="ui-hide-label"></label>
				<input name="add_date" type="hidden" id="add_date" value="<?php echo date('Y-m-d', time()); ?>" />
			</div>
			<div data-role="controlgroup" data-type="horizontal">
				<input name="Submit" type="submit" value="Submit" onClick="return validateForm(6);" />
				<input name="Reset" type="reset" value="Reset" />
			</div>
			<input type="hidden" name="MM_insert" value="registration" />
		</form>
		<script type="text/javascript">
			function validateForm(min_num)  {  
			var pass1 = document.getElementById('password1');
			var pass2 = document.getElementById('password2');
			var pass1_val = pass1.value;
			var pass1_num = pass1_val.length;
			var pass2_val = pass2.value;
			
				if (pass1_num < min_num)  {
					alert("Password must be at least " + min_num + " characters");
					return false;  
				}
				
				if (pass2_val != pass1_val) {
					alert ("The passwords do not match");
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