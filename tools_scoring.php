<?php require('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php
$settings_array = settings_list();
?>
<!DOCTYPE html>
<html>
<head>
<?php require('includes/set_head.php'); ?>
</head>
<body>
<div data-role="page" id="tools">
	<div data-role="header" data-position="fixed">
		<h1>Season Rules</h1>
		<?php require('includes/set_tools.php'); ?>
	</div>
	<div role="main" class="ui-content">
		<div data-role="collapsible-set">
			<?php for ($i = 0; $i <= count($settings_array) - 1; $i++) { ?>
			<div data-role="collapsible" <?php echo ($i == 0 ? 'data-collapsed="false"' : '') ?>>
				<h3><?php echo $settings_array[$i]['season_name']; ?></h3>
				<?php require('includes/get_scoring.php'); ?>
			</div>
			<?php } ?>
		</div>
	</div>
	<div data-role="footer" data-position="fixed">
		<?php require('includes/set_footer.php'); ?>
	</div>
</div>
</body>
</html>
