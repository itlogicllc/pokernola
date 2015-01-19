<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<!DOCTYPE html>
<html>
<head>
<?php require('includes/set_head.php'); ?>
</head>
<body>
<div data-role="page" id="access_denied">
	<div data-role="header" data-position="fixed">
		<h1>Access Denied</h1>
		<?php require('includes/set_home.php'); ?>
	</div>
	<div role="main" class="ui-content">
		<div class="ui-body ui-body-a ui-corner-all alert" align="center"><?php echo (isset($_GET['message'])) ? $_GET['message'] : "The message is missing"; ?></div>
	</div>
	<div data-role="footer" data-position="fixed">
		<?php require('includes/set_footer.php'); ?>
	</div>
</div>
</body>
</html>
