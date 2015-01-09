<?php
// *** Logout the current user.
$logoutGoTo = "index.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
$_SESSION['player_first'] = NULL;
$_SESSION['player_id'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
unset($_SESSION['player_first']);
unset($_SESSION['player_logged_in']);
unset($_SESSION['player_access']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>

<body>
</body>
</html>