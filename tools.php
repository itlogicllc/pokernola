<?php require('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<!DOCTYPE html>
<html>
<head>
<?php require('includes/set_head.php'); ?>
</head>
<body>
<div data-role="page" id="tools">
	<?php require('includes/set_panel_date.php'); ?>
	<div data-role="header" data-position="fixed">
		<h1>Tools</h1>
		<?php require('includes/set_tools.php'); ?>
	</div>
	<div role="main" class="ui-content">
                <?php if (isset($_SESSION['player_access']) && $_SESSION['player_access'] == 'admin') { ?>
                    <a href="tools_settings.php" data-role="button" data-transition="fade">Create New Season</a>
                <?php } ?>
		<a href="tools_scoring.php" data-role="button" data-transition="fade">Season Rules</a>
		<a href="tools_help.php" data-role="button" data-transition="fade">Help</a>
		<a href="tools_about.php" data-role="button" data-transition="fade">About</a>
	</div>
	<div data-role="footer" data-position="fixed">
		<?php require('includes/set_footer.php'); ?>
	</div>
</div>
</body>
</html>
