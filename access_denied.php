<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<!DOCTYPE html>
<html>
<head>
<?php require('includes/set_head.php'); ?>
</head>
<body>
<div data-role="page" id="access_denied">
	<?php require('includes/set_panel_date.php'); ?>
	<?php require('includes/set_panel_login.php'); ?>
	<div data-role="header" data-position="fixed">
		<h1>Access Denied</h1>
		<?php require('includes/set_home.php'); ?>
	</div>
	<div role="main" class="ui-content">
		<div class="ui-body ui-body-a ui-corner-all alert" align="center"><?php echo $_GET['message'] ?></div>
	</div>
	<div data-role="footer" data-position="fixed">
		<?php require('includes/set_footer.php'); ?>
	</div>
</div>
</body>
</html>
