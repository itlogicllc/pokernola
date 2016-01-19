<?php 
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_messages.php');
?>
<!DOCTYPE html>
<html>
<head>
	<?php require('includes/set_head.php'); ?>
	<title>Poker NOLA Access Denied</title>
</head>
<body>
<div data-role="page" id="access_denied">
	<div data-role="header" data-position="fixed">
		<h1>Access Denied</h1>
		<?php require('includes/set_home.php'); ?>
	</div>
	<div role="main" class="ui-content">
		<?php require('includes/set_messages.php'); ?>
	</div>
	<div data-role="footer" data-position="fixed">
		<?php require('includes/set_footer.php'); ?>
	</div>
</div>
</body>
</html>
